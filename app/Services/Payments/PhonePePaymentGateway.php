<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PhonePePaymentGateway implements PaymentGatewayInterface
{
    public function initiatePayment(Order $order): array
    {
        $this->logInfo('initiate:start', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'total' => (float) $order->total,
            'mode' => $this->isTestMode() ? 'test' : 'live',
        ]);

        $auth = $this->getAccessToken();
        if (! ($auth['success'] ?? false)) {
            $this->logWarning('initiate:auth_failed', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'message' => (string) ($auth['message'] ?? 'PhonePe is not configured.'),
            ]);
            return [
                'success' => false,
                'message' => (string) ($auth['message'] ?? 'PhonePe is not configured.'),
            ];
        }

        $merchantOrderId = (string) $order->order_number;
        $amountInPaise = (int) round(((float) $order->total) * 100);
        if ($amountInPaise <= 0) {
            return [
                'success' => false,
                'message' => 'Invalid payment amount.',
            ];
        }

        $payload = [
            'merchantOrderId' => $merchantOrderId,
            'amount' => $amountInPaise,
            'expireAfter' => max((int) config('services.phonepe.expire_after', 1200), 300),
            'paymentFlow' => [
                'type' => 'PG_CHECKOUT',
                'message' => 'Payment for order ' . $merchantOrderId,
                'merchantUrls' => [
                    'redirectUrl' => route('payment.phonepe.return', ['orderNumber' => $order->order_number]),
                ],
            ],
            'metaInfo' => [
                'udf1' => (string) $order->order_number,
                'udf2' => (string) $order->id,
            ],
        ];

        $prefillPhone = $this->formatPhoneForPrefill((string) $order->customer_phone);
        if ($prefillPhone !== null) {
            $payload['prefillUserLoginDetails'] = [
                'phoneNumber' => $prefillPhone,
            ];
        }

        $checkoutEndpoint = (string) config('services.phonepe.checkout_endpoint', '/checkout/v2/pay');
        $url = $this->pgBaseUrl() . $checkoutEndpoint;
        $this->logInfo('initiate:request', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'url' => $url,
            'payload' => $this->maskPayload($payload),
            'token_type' => (string) ($auth['token_type'] ?? 'O-Bearer'),
        ]);

        try {
            $response = Http::timeout(20)
                ->retry(2, 300)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => trim((string) ($auth['token_type'] ?? 'O-Bearer')) . ' ' . (string) ($auth['token'] ?? ''),
                ])
                ->post($url, $payload);

            $body = $response->json();
            $redirectUrl = (string) (data_get($body, 'redirectUrl') ?? data_get($body, 'data.redirectUrl', ''));
            $state = strtoupper((string) (data_get($body, 'state') ?? data_get($body, 'data.state', '')));
            $success = $response->successful()
                && in_array($state, ['PENDING', 'COMPLETED'], true)
                && $redirectUrl !== '';

            if (! $success) {
                $this->logWarning('initiate:failed', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'http_status' => $response->status(),
                    'state' => $state,
                    'url' => $url,
                    'response' => $body,
                ]);
                Log::warning('PhonePe initiate payment failed', [
                    'order_id' => $order->id,
                    'response' => $body,
                    'status' => $response->status(),
                    'url' => $url,
                ]);

                return [
                    'success' => false,
                    'gateway_order_id' => (string) (data_get($body, 'orderId') ?? ''),
                    'status' => $state !== '' ? $state : 'FAILED',
                    'payload' => is_array($body) ? $body : [],
                    'message' => (string) (data_get($body, 'message')
                        ?? data_get($body, 'error.message')
                        ?? 'Unable to initiate PhonePe payment.'),
                ];
            }

            $this->logInfo('initiate:success', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'http_status' => $response->status(),
                'state' => $state,
                'redirect_url' => $redirectUrl,
                'gateway_order_id' => (string) (data_get($body, 'orderId') ?? ''),
            ]);
            return [
                'success' => true,
                'redirect_url' => $redirectUrl,
                'gateway_order_id' => (string) (data_get($body, 'orderId') ?? ''),
                'status' => $state,
                'payload' => is_array($body) ? $body : [],
                'transaction_id' => (string) (data_get($body, 'orderId') ?? $merchantOrderId),
            ];
        } catch (\Throwable $e) {
            $this->logError('initiate:exception', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'error' => $e->getMessage(),
            ]);
            Log::error('PhonePe initiate exception', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Payment service is temporarily unavailable.',
            ];
        }
    }

    public function verifyPayment(string $merchantTransactionId): array
    {
        $this->logInfo('verify:start', [
            'merchant_transaction_id' => $merchantTransactionId,
            'mode' => $this->isTestMode() ? 'test' : 'live',
        ]);

        $auth = $this->getAccessToken();
        if (! ($auth['success'] ?? false)) {
            $this->logWarning('verify:auth_failed', [
                'merchant_transaction_id' => $merchantTransactionId,
                'message' => (string) ($auth['message'] ?? 'PhonePe is not configured.'),
            ]);
            return [
                'success' => false,
                'status' => 'CONFIG_ERROR',
                'message' => (string) ($auth['message'] ?? 'PhonePe is not configured.'),
            ];
        }

        $statusEndpoint = (string) config('services.phonepe.order_status_endpoint', '/checkout/v2/order/{merchantOrderId}/status');
        $statusPath = str_replace('{merchantOrderId}', rawurlencode($merchantTransactionId), $statusEndpoint);
        $url = $this->pgBaseUrl() . $statusPath;
        $this->logInfo('verify:request', [
            'merchant_transaction_id' => $merchantTransactionId,
            'url' => $url,
        ]);

        try {
            $response = Http::timeout(20)
                ->retry(2, 300)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => trim((string) ($auth['token_type'] ?? 'O-Bearer')) . ' ' . (string) ($auth['token'] ?? ''),
                ])
                ->get($url, [
                    'details' => 'true',
                    'errorContext' => 'true',
                ]);

            $body = $response->json();
            $state = strtoupper((string) data_get($body, 'state', 'UNKNOWN'));
            $isSuccess = $response->successful() && $state === 'COMPLETED';
            $this->logInfo('verify:response', [
                'merchant_transaction_id' => $merchantTransactionId,
                'http_status' => $response->status(),
                'state' => $state,
                'success' => $isSuccess,
                'response' => $body,
            ]);

            return [
                'success' => $isSuccess,
                'status' => $state,
                'payload' => is_array($body) ? $body : [],
                'message' => (string) data_get($body, 'message', ''),
            ];
        } catch (\Throwable $e) {
            $this->logError('verify:exception', [
                'merchant_transaction_id' => $merchantTransactionId,
                'error' => $e->getMessage(),
            ]);
            Log::error('PhonePe verify exception', [
                'transaction_id' => $merchantTransactionId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'status' => 'ERROR',
                'message' => 'Unable to verify payment at this moment.',
            ];
        }
    }

    protected function getAccessToken(): array
    {
        $clientId = trim((string) config('services.phonepe.client_id', config('services.phonepe.merchant_id', '')));
        $clientVersion = trim((string) config('services.phonepe.client_version', config('services.phonepe.salt_index', '1')));
        $clientSecret = trim((string) config('services.phonepe.client_secret', config('services.phonepe.salt_key', '')));

        if ($clientId === '' || $clientVersion === '' || $clientSecret === '') {
            $this->logWarning('auth:config_missing', [
                'client_id_present' => $clientId !== '',
                'client_version_present' => $clientVersion !== '',
                'client_secret_present' => $clientSecret !== '',
            ]);
            return [
                'success' => false,
                'message' => 'PhonePe client credentials are not configured.',
            ];
        }

        $mode = $this->isTestMode() ? 'test' : 'live';
        $cacheKey = 'phonepe:oauth:' . md5($mode . '|' . $clientId . '|' . $clientVersion);
        $cached = Cache::get($cacheKey);
        if (is_array($cached)) {
            $token = (string) ($cached['token'] ?? '');
            $tokenType = (string) ($cached['token_type'] ?? 'O-Bearer');
            $expiresAt = (int) ($cached['expires_at'] ?? 0);

            if ($token !== '' && ($expiresAt === 0 || $expiresAt > (time() + 60))) {
                $this->logInfo('auth:cache_hit', [
                    'mode' => $mode,
                    'client_id' => $this->maskString($clientId),
                    'expires_at' => $expiresAt,
                ]);
                return [
                    'success' => true,
                    'token' => $token,
                    'token_type' => $tokenType,
                ];
            }
        }

        $authEndpoint = (string) config('services.phonepe.auth_endpoint', '/v1/oauth/token');
        $url = $this->authBaseUrl() . $authEndpoint;
        $this->logInfo('auth:request', [
            'mode' => $mode,
            'url' => $url,
            'client_id' => $this->maskString($clientId),
            'client_version' => $clientVersion,
        ]);

        try {
            $authPayloads = [
                [
                    'client_id' => $clientId,
                    'client_version' => $clientVersion,
                    'client_secret' => $clientSecret,
                    'grant_type' => 'client_credentials',
                ],
                [
                    'clientId' => $clientId,
                    'clientVersion' => $clientVersion,
                    'clientSecret' => $clientSecret,
                    'grantType' => 'client_credentials',
                ],
            ];

            $response = null;
            $body = [];
            foreach ($authPayloads as $index => $authPayload) {
                $attempt = $index + 1;
                $response = Http::timeout(15)
                    ->retry(2, 250, null, false)
                    ->asForm()
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ])
                    ->post($url, $authPayload);

                $body = $response->json();
                $token = (string) data_get($body, 'access_token', '');

                if ($response->successful() && $token !== '') {
                    break;
                }

                $this->logWarning('auth:attempt_failed', [
                    'mode' => $mode,
                    'attempt' => $attempt,
                    'payload_style' => $attempt === 1 ? 'snake_case' : 'camelCase',
                    'http_status' => $response->status(),
                    'response' => $body,
                    'url' => $url,
                ]);
            }

            $body = is_array($body) ? $body : [];
            $token = (string) data_get($body, 'access_token', '');
            $tokenType = (string) data_get($body, 'token_type', 'O-Bearer');
            $expiresAt = (int) data_get($body, 'expires_at', 0);

            if (! $response || ! $response->successful() || $token === '') {
                $this->logWarning('auth:failed', [
                    'mode' => $mode,
                    'http_status' => $response?->status(),
                    'response' => $body,
                    'url' => $url,
                ]);
                Log::warning('PhonePe auth token fetch failed', [
                    'status' => $response?->status(),
                    'response' => $body,
                    'url' => $url,
                ]);

                return [
                    'success' => false,
                    'message' => (string) (data_get($body, 'message') ?? 'Unable to authenticate with PhonePe.'),
                ];
            }

            $ttlSeconds = $expiresAt > time()
                ? max(60, $expiresAt - time() - 60)
                : 2700;

            Cache::put($cacheKey, [
                'token' => $token,
                'token_type' => $tokenType,
                'expires_at' => $expiresAt > 0 ? $expiresAt : (time() + $ttlSeconds),
            ], now()->addSeconds($ttlSeconds));

            $this->logInfo('auth:success', [
                'mode' => $mode,
                'token_type' => $tokenType,
                'expires_at' => $expiresAt,
                'ttl_seconds' => $ttlSeconds,
            ]);

            return [
                'success' => true,
                'token' => $token,
                'token_type' => $tokenType,
            ];
        } catch (\Throwable $e) {
            $this->logError('auth:exception', [
                'mode' => $mode,
                'error' => $e->getMessage(),
                'url' => $url,
            ]);
            Log::error('PhonePe auth token fetch exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Unable to authenticate with PhonePe right now.',
            ];
        }
    }

    protected function authBaseUrl(): string
    {
        $configured = trim((string) config('services.phonepe.auth_base_url', ''));
        if ($configured !== '') {
            return rtrim($configured, '/');
        }

        if ($this->isTestMode()) {
            return 'https://api-preprod.phonepe.com/apis/pg-sandbox';
        }

        return 'https://api.phonepe.com/apis/identity-manager';
    }

    protected function pgBaseUrl(): string
    {
        $configured = trim((string) config('services.phonepe.base_url', ''));
        if ($configured !== '') {
            return rtrim($configured, '/');
        }

        if ($this->isTestMode()) {
            return 'https://api-preprod.phonepe.com/apis/pg-sandbox';
        }

        return 'https://api.phonepe.com/apis/pg';
    }

    protected function isTestMode(): bool
    {
        return (bool) config('services.phonepe.test_mode', true);
    }

    protected function formatPhoneForPrefill(string $rawPhone): ?string
    {
        $digits = preg_replace('/\D+/', '', $rawPhone);
        if ($digits === '') {
            return null;
        }

        if (strlen($digits) === 10) {
            return '+91' . $digits;
        }

        if (strlen($digits) > 10 && str_starts_with($digits, '91')) {
            return '+' . $digits;
        }

        return null;
    }

    protected function logInfo(string $event, array $context = []): void
    {
        Log::info('PhonePe ' . $event, $context);
    }

    protected function logWarning(string $event, array $context = []): void
    {
        Log::warning('PhonePe ' . $event, $context);
    }

    protected function logError(string $event, array $context = []): void
    {
        Log::error('PhonePe ' . $event, $context);
    }

    protected function maskString(string $value, int $visible = 4): string
    {
        $len = strlen($value);
        if ($len <= $visible) {
            return str_repeat('*', $len);
        }

        return str_repeat('*', max(0, $len - $visible)) . substr($value, -$visible);
    }

    protected function maskPayload(array $payload): array
    {
        if (isset($payload['prefillUserLoginDetails']['phoneNumber'])) {
            $payload['prefillUserLoginDetails']['phoneNumber'] = $this->maskString((string) $payload['prefillUserLoginDetails']['phoneNumber'], 2);
        }

        return $payload;
    }
}
