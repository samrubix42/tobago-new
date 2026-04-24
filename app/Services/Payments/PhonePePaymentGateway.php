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
        $auth = $this->getAccessToken();
        if (! ($auth['success'] ?? false)) {
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

            return [
                'success' => true,
                'redirect_url' => $redirectUrl,
                'gateway_order_id' => (string) (data_get($body, 'orderId') ?? ''),
                'status' => $state,
                'payload' => is_array($body) ? $body : [],
                'transaction_id' => (string) (data_get($body, 'orderId') ?? $merchantOrderId),
            ];
        } catch (\Throwable $e) {
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
        $auth = $this->getAccessToken();
        if (! ($auth['success'] ?? false)) {
            return [
                'success' => false,
                'status' => 'CONFIG_ERROR',
                'message' => (string) ($auth['message'] ?? 'PhonePe is not configured.'),
            ];
        }

        $statusEndpoint = (string) config('services.phonepe.order_status_endpoint', '/checkout/v2/order/{merchantOrderId}/status');
        $statusPath = str_replace('{merchantOrderId}', rawurlencode($merchantTransactionId), $statusEndpoint);
        $url = $this->pgBaseUrl() . $statusPath;

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

            return [
                'success' => $isSuccess,
                'status' => $state,
                'payload' => is_array($body) ? $body : [],
                'message' => (string) data_get($body, 'message', ''),
            ];
        } catch (\Throwable $e) {
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
                return [
                    'success' => true,
                    'token' => $token,
                    'token_type' => $tokenType,
                ];
            }
        }

        $authEndpoint = (string) config('services.phonepe.auth_endpoint', '/v1/oauth/token');
        $url = $this->authBaseUrl() . $authEndpoint;

        try {
            $response = Http::timeout(15)
                ->retry(2, 250)
                ->asForm()
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ])
                ->post($url, [
                    'client_id' => $clientId,
                    'client_version' => $clientVersion,
                    'client_secret' => $clientSecret,
                    'grant_type' => 'client_credentials',
                ]);

            $body = $response->json();
            $token = (string) data_get($body, 'access_token', '');
            $tokenType = (string) data_get($body, 'token_type', 'O-Bearer');
            $expiresAt = (int) data_get($body, 'expires_at', 0);

            if (! $response->successful() || $token === '') {
                Log::warning('PhonePe auth token fetch failed', [
                    'status' => $response->status(),
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

            return [
                'success' => true,
                'token' => $token,
                'token_type' => $tokenType,
            ];
        } catch (\Throwable $e) {
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
}
