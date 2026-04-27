<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use PhonePe\Env;
use PhonePe\common\exceptions\PhonePeException;
use PhonePe\payments\v2\models\request\builders\StandardCheckoutPayRequestBuilder;
use PhonePe\payments\v2\standardCheckout\StandardCheckoutClient;

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

        $clientInit = $this->resolveClient();
        if (! ($clientInit['success'] ?? false)) {
            $this->logWarning('initiate:client_init_failed', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'message' => (string) ($clientInit['message'] ?? 'PhonePe is not configured.'),
            ]);

            return [
                'success' => false,
                'message' => (string) ($clientInit['message'] ?? 'PhonePe is not configured.'),
            ];
        }

        /** @var StandardCheckoutClient $client */
        $client = $clientInit['client'];

        $merchantOrderId = (string) $order->order_number;
        $amountInPaise = (int) round(((float) $order->total) * 100);
        if ($amountInPaise < 100) {
            return [
                'success' => false,
                'message' => 'Invalid payment amount. Minimum payable amount is Rs 1.00.',
            ];
        }

        try {
            $payRequest = StandardCheckoutPayRequestBuilder::builder()
                ->merchantOrderId($merchantOrderId)
                ->amount($amountInPaise)
                ->redirectUrl(route('payment.phonepe.return', ['orderNumber' => $order->order_number]))
                ->message('Payment for order ' . $merchantOrderId)
                ->udf1((string) $order->order_number)
                ->udf2((string) $order->id)
                ->build();

            $payResponse = $client->pay($payRequest);
            $payload = $this->normalizeSdkResponse($payResponse);

            $redirectUrl = (string) $payResponse->getRedirectUrl();
            $state = strtoupper((string) $payResponse->getState());
            $gatewayOrderId = (string) $payResponse->getOrderId();
            $success = in_array($state, ['PENDING', 'COMPLETED'], true) && $redirectUrl !== '';

            if (! $success) {
                $this->logWarning('initiate:failed', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'state' => $state,
                    'response' => $payload,
                ]);

                return [
                    'success' => false,
                    'gateway_order_id' => $gatewayOrderId,
                    'status' => $state !== '' ? $state : 'FAILED',
                    'payload' => $payload,
                    'message' => 'Unable to initiate PhonePe payment.',
                ];
            }

            $this->logInfo('initiate:success', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'state' => $state,
                'redirect_url' => $redirectUrl,
                'gateway_order_id' => $gatewayOrderId,
            ]);

            return [
                'success' => true,
                'redirect_url' => $redirectUrl,
                'gateway_order_id' => $gatewayOrderId,
                'status' => $state,
                'payload' => $payload,
                'transaction_id' => $gatewayOrderId !== '' ? $gatewayOrderId : $merchantOrderId,
            ];
        } catch (PhonePeException $e) {
            $statusCode = (int) $e->getHttpStatusCode();
            $errorData = $e->getData();

            $this->logError('initiate:sdk_exception', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'http_status' => $statusCode,
                'error' => $e->getMessage(),
                'data' => $errorData,
            ]);

            return [
                'success' => false,
                'message' => $statusCode === 400 || $statusCode === 401
                    ? 'Unable to authenticate with PhonePe right now.'
                    : 'Payment service is temporarily unavailable.',
                'status' => 'ERROR',
                'payload' => $this->normalizeSdkResponse($errorData),
            ];
        } catch (\Throwable $e) {
            $this->logError('initiate:exception', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
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

        $clientInit = $this->resolveClient();
        if (! ($clientInit['success'] ?? false)) {
            $this->logWarning('verify:client_init_failed', [
                'merchant_transaction_id' => $merchantTransactionId,
                'message' => (string) ($clientInit['message'] ?? 'PhonePe is not configured.'),
            ]);

            return [
                'success' => false,
                'status' => 'CONFIG_ERROR',
                'message' => (string) ($clientInit['message'] ?? 'PhonePe is not configured.'),
            ];
        }

        /** @var StandardCheckoutClient $client */
        $client = $clientInit['client'];

        try {
            $statusResponse = $client->getOrderStatus($merchantTransactionId, true);
            $payload = $this->normalizeSdkResponse($statusResponse);
            $state = strtoupper((string) ($statusResponse->getState() ?? 'UNKNOWN'));
            $isSuccess = $state === 'COMPLETED';

            $this->logInfo('verify:response', [
                'merchant_transaction_id' => $merchantTransactionId,
                'state' => $state,
                'success' => $isSuccess,
            ]);

            return [
                'success' => $isSuccess,
                'status' => $state,
                'payload' => $payload,
                'message' => '',
            ];
        } catch (PhonePeException $e) {
            $statusCode = (int) $e->getHttpStatusCode();

            $this->logError('verify:sdk_exception', [
                'merchant_transaction_id' => $merchantTransactionId,
                'http_status' => $statusCode,
                'error' => $e->getMessage(),
                'data' => $e->getData(),
            ]);

            return [
                'success' => false,
                'status' => 'ERROR',
                'message' => $statusCode === 400 || $statusCode === 401
                    ? 'Unable to authenticate with PhonePe right now.'
                    : 'Unable to verify payment at this moment.',
                'payload' => $this->normalizeSdkResponse($e->getData()),
            ];
        } catch (\Throwable $e) {
            $this->logError('verify:exception', [
                'merchant_transaction_id' => $merchantTransactionId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'status' => 'ERROR',
                'message' => 'Unable to verify payment at this moment.',
            ];
        }
    }

    protected function resolveClient(): array
    {
        $clientId = trim((string) config('services.phonepe.client_id', config('services.phonepe.merchant_id', '')));
        $clientVersionRaw = trim((string) config('services.phonepe.client_version', config('services.phonepe.salt_index', '1')));
        $clientSecret = trim((string) config('services.phonepe.client_secret', config('services.phonepe.salt_key', '')));

        if ($clientId === '' || $clientVersionRaw === '' || $clientSecret === '') {
            return [
                'success' => false,
                'message' => 'PhonePe client credentials are not configured.',
            ];
        }

        $clientVersion = (int) $clientVersionRaw;
        if ($clientVersion <= 0) {
            return [
                'success' => false,
                'message' => 'PhonePe client version is invalid.',
            ];
        }

        $env = $this->isTestMode() ? Env::UAT : Env::PRODUCTION;

        try {
            $client = StandardCheckoutClient::getInstance(
                $clientId,
                $clientVersion,
                $clientSecret,
                $env,
                false
            );

            return [
                'success' => true,
                'client' => $client,
            ];
        } catch (\Throwable $e) {
            $this->logError('client:init_exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Unable to authenticate with PhonePe right now.',
            ];
        }
    }

    protected function isTestMode(): bool
    {
        return (bool) config('services.phonepe.test_mode', true);
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

    protected function normalizeSdkResponse(mixed $payload): array
    {
        if (is_array($payload)) {
            return $payload;
        }

        if ($payload instanceof \JsonSerializable) {
            $serialized = $payload->jsonSerialize();
            return is_array($serialized) ? $serialized : [];
        }

        if (is_object($payload)) {
            return json_decode(json_encode($payload), true) ?: [];
        }

        return [];
    }
}
