<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PhonePePaymentGateway implements PaymentGatewayInterface
{
    public function initiatePayment(Order $order): array
    {
        $merchantId = (string) config('services.phonepe.merchant_id');
        $saltKey = (string) config('services.phonepe.salt_key');
        $saltIndex = (string) config('services.phonepe.salt_index');
        $payEndpoint = (string) config('services.phonepe.pay_endpoint', '/pg/v1/pay');
        $baseUrl = $this->resolveBaseUrl();

        if ($merchantId === '' || $saltKey === '' || $saltIndex === '') {
            return [
                'success' => false,
                'message' => 'PhonePe is not configured. Please contact support.',
            ];
        }

        $merchantTransactionId = (string) $order->order_number;
        $merchantUserId = 'USER-' . ($order->user_id ?? $order->id);
        $amountInPaise = (int) round(((float) $order->total) * 100);

        $payload = [
            'merchantId' => $merchantId,
            'merchantTransactionId' => $merchantTransactionId,
            'merchantUserId' => $merchantUserId,
            'amount' => $amountInPaise,
            'redirectUrl' => route('payment.phonepe.return', ['orderNumber' => $order->order_number]),
            'redirectMode' => 'REDIRECT',
            'callbackUrl' => route('payment.phonepe.callback'),
            'mobileNumber' => preg_replace('/\D+/', '', (string) $order->customer_phone),
            'paymentInstrument' => [
                'type' => 'PAY_PAGE',
            ],
        ];

        $encodedPayload = base64_encode(json_encode($payload, JSON_UNESCAPED_SLASHES));
        $xVerify = hash('sha256', $encodedPayload . $payEndpoint . $saltKey) . '###' . $saltIndex;

        try {
            $response = Http::timeout(20)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-VERIFY' => $xVerify,
                    'accept' => 'application/json',
                ])
                ->post($baseUrl . $payEndpoint, [
                    'request' => $encodedPayload,
                ]);

            $body = $response->json();
            $redirectUrl = data_get($body, 'data.instrumentResponse.redirectInfo.url');

            if (! $response->successful() || ! data_get($body, 'success') || ! $redirectUrl) {
                Log::warning('PhonePe initiate payment failed', [
                    'order_id' => $order->id,
                    'response' => $body,
                    'status' => $response->status(),
                ]);

                $errorCode = (string) data_get($body, 'code', '');
                if ($errorCode === 'KEY_NOT_CONFIGURED') {
                    return [
                        'success' => false,
                        'message' => 'PhonePe key is not configured for this merchant in selected mode. Please use valid PG test credentials from PhonePe dashboard.',
                    ];
                }

                return [
                    'success' => false,
                    'message' => (string) data_get($body, 'message', 'Unable to initiate PhonePe payment.'),
                ];
            }

            return [
                'success' => true,
                'redirect_url' => $redirectUrl,
                'transaction_id' => $merchantTransactionId,
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
        $merchantId = (string) config('services.phonepe.merchant_id');
        $saltKey = (string) config('services.phonepe.salt_key');
        $saltIndex = (string) config('services.phonepe.salt_index');
        $statusPath = '/pg/v1/status/' . $merchantId . '/' . $merchantTransactionId;
        $baseUrl = $this->resolveBaseUrl();

        if ($merchantId === '' || $saltKey === '' || $saltIndex === '') {
            return [
                'success' => false,
                'status' => 'CONFIG_ERROR',
                'message' => 'PhonePe is not configured.',
            ];
        }

        $xVerify = hash('sha256', $statusPath . $saltKey) . '###' . $saltIndex;

        try {
            $response = Http::timeout(20)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-VERIFY' => $xVerify,
                    'X-MERCHANT-ID' => $merchantId,
                    'accept' => 'application/json',
                ])
                ->get($baseUrl . $statusPath);

            $body = $response->json();
            $state = (string) data_get($body, 'data.state', 'UNKNOWN');
            $isSuccess = $response->successful() && data_get($body, 'success') === true && $state === 'COMPLETED';

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

    protected function resolveBaseUrl(): string
    {
        $configured = trim((string) config('services.phonepe.base_url', ''));
        $isTestMode = (bool) config('services.phonepe.test_mode', true);

        if ($isTestMode) {
            if ($configured === '' || str_contains($configured, 'api.phonepe.com/apis/')) {
                return 'https://api-preprod.phonepe.com/apis/pg-sandbox';
            }
        }

        if ($configured !== '') {
            return rtrim($configured, '/');
        }

        if ($isTestMode) {
            return 'https://api-preprod.phonepe.com/apis/pg-sandbox';
        }

        return 'https://api.phonepe.com/apis/hermes';
    }
}
