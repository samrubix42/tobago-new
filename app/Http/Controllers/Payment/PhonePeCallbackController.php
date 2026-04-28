<?php

namespace App\Http\Controllers\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderStatusLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PhonePeCallbackController extends Controller
{
    public function __construct(private readonly PaymentGatewayInterface $paymentGateway)
    {
    }

    public function handleReturn(Request $request, string $orderNumber): RedirectResponse
    {
        Log::info('PhonePe return hit', [
            'order_number' => $orderNumber,
            'query' => $request->query(),
            'ip' => $request->ip(),
        ]);

        $order = Order::query()->where('order_number', $orderNumber)->first();

        if (! $order) {
            Log::warning('PhonePe return order not found', [
                'order_number' => $orderNumber,
            ]);
            return redirect()->route('order.checkout')->with('error', 'Order not found for payment verification.');
        }

        $verification = $this->paymentGateway->verifyPayment($order->order_number);
        Log::info('PhonePe return verification result', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'verification_status' => (string) ($verification['status'] ?? 'UNKNOWN'),
            'verification_success' => (bool) ($verification['success'] ?? false),
        ]);
        $this->syncOrderPaymentState($order, $verification);

        if ($verification['success'] ?? false) {
            return redirect()->route('order.checkout')->with([
                'success' => 'Payment completed successfully.',
                'placed_order_number' => $order->order_number
            ]);
        }

        return redirect()->route('order.checkout')->with([
            'error' => 'Payment was not completed. You can try again.',
            'failed_order_number' => $order->order_number
        ]);
    }

    public function handleCallback(Request $request)
    {
        Log::info('PhonePe webhook hit', [
            'ip' => $request->ip(),
            'headers' => [
                'user-agent' => (string) $request->header('User-Agent', ''),
                'authorization_present' => $request->header('Authorization') !== null,
                'content-type' => (string) $request->header('Content-Type', ''),
            ],
            'payload' => $request->all(),
        ]);

        if (! $this->isValidWebhookAuthorization($request)) {
            Log::warning('PhonePe webhook unauthorized', [
                'ip' => $request->ip(),
            ]);
            return response()->json(['success' => false, 'message' => 'Unauthorized webhook request'], 401);
        }

        $payload = $request->all();
        $orderNumber = (string) (data_get($payload, 'payload.merchantOrderId')
            ?? data_get($payload, 'payload.merchantTransactionId')
            ?? '');

        if ($orderNumber === '') {
            Log::warning('PhonePe webhook missing merchantOrderId', [
                'payload' => $payload,
            ]);
            return response()->json(['success' => false, 'message' => 'Missing merchantOrderId'], 422);
        }

        $order = Order::query()->where('order_number', $orderNumber)->first();

        if (! $order) {
            Log::warning('PhonePe webhook order not found', [
                'order_number' => $orderNumber,
            ]);
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $event = (string) data_get($payload, 'event', '');
        $webhookState = strtoupper((string) data_get($payload, 'payload.state', ''));
        $isOrderEvent = in_array($event, ['checkout.order.completed', 'checkout.order.failed'], true);

        if ($isOrderEvent && in_array($webhookState, ['COMPLETED', 'FAILED', 'PENDING'], true)) {
            $verification = [
                'success' => $webhookState === 'COMPLETED',
                'status' => $webhookState,
                'payload' => is_array($payload) ? $payload : [],
                'message' => $event,
            ];
        } else {
            Log::info('PhonePe webhook falling back to verify API', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'event' => $event,
                'state' => $webhookState,
            ]);
            $verification = $this->paymentGateway->verifyPayment($order->order_number);
        }

        Log::info('PhonePe webhook verification result', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'verification_status' => (string) ($verification['status'] ?? 'UNKNOWN'),
            'verification_success' => (bool) ($verification['success'] ?? false),
        ]);
        $this->syncOrderPaymentState($order, $verification);

        return response()->json([
            'success' => true,
            'payment_status' => $order->payment_status,
        ]);
    }

    protected function syncOrderPaymentState(Order $order, array $verification): void
    {
        Log::info('PhonePe syncOrderPaymentState called', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'incoming_status' => (string) ($verification['status'] ?? 'UNKNOWN'),
            'incoming_success' => (bool) ($verification['success'] ?? false),
            'current_payment_status' => (string) $order->payment_status,
        ]);

        $verificationPayload = is_array($verification['payload'] ?? null) ? $verification['payload'] : [];
        $paymentState = strtoupper((string) ($verification['status'] ?? 'UNKNOWN'));
        $gatewayOrderId = (string) (data_get($verificationPayload, 'orderId')
            ?? data_get($verificationPayload, 'payload.orderId')
            ?? $order->payment_gateway_order_id
            ?? '');
        $gatewayTransactionId = (string) (data_get($verificationPayload, 'paymentDetails.0.transactionId')
            ?? data_get($verificationPayload, 'payload.paymentDetails.0.transactionId')
            ?? $order->payment_gateway_transaction_id
            ?? '');

        if (($verification['success'] ?? false) === true) {
            if ($order->payment_status === 'paid') {
                Log::info('PhonePe sync skipped: already paid', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ]);
                return;
            }


            $order->update([
                'payment_gateway' => 'phonepe',
                'payment_gateway_order_id' => $gatewayOrderId !== '' ? $gatewayOrderId : $order->payment_gateway_order_id,
                'payment_gateway_transaction_id' => $gatewayTransactionId !== '' ? $gatewayTransactionId : $order->payment_gateway_transaction_id,
                'payment_status' => 'paid',
                'payment_state' => $paymentState,
                'payment_failure_reason' => null,
                'payment_response_payload' => $verificationPayload ?: $order->payment_response_payload,
                'payment_verified_at' => now(),
                'status' => 'confirmed',
            ]);

            OrderStatusLog::query()->create([
                'order_id' => $order->id,
                'status' => 'confirmed',
                'note' => 'PhonePe payment verified successfully.',
                'source' => 'system',
                'logged_at' => now(),
            ]);

            $this->clearCartForOrder($order);
            Log::info('PhonePe payment marked paid', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'payment_state' => $paymentState,
            ]);

            return;
        }

        $failureState = $paymentState;
        if (! in_array($failureState, ['FAILED'], true)) {
            Log::warning('PhonePe verification non-terminal status, skipping failure update', [
                'order_id' => $order->id,
                'status' => $failureState,
            ]);
            return;
        }

        if ($order->payment_status === 'paid') {
            Log::warning('Ignoring failed PhonePe callback for already paid order', [
                'order_id' => $order->id,
                'status' => $failureState,
            ]);
            return;
        }

        $order->update([
            'payment_gateway' => 'phonepe',
            'payment_gateway_order_id' => $gatewayOrderId !== '' ? $gatewayOrderId : $order->payment_gateway_order_id,
            'payment_gateway_transaction_id' => $gatewayTransactionId !== '' ? $gatewayTransactionId : $order->payment_gateway_transaction_id,
            'status' => 'pending',
            'payment_status' => 'failed',
            'payment_state' => $failureState,
            'payment_failure_reason' => 'PhonePe status: ' . $failureState,
            'payment_response_payload' => $verificationPayload ?: $order->payment_response_payload,
        ]);

        OrderStatusLog::query()->create([
            'order_id' => $order->id,
            'status' => $order->status,
            'note' => 'PhonePe payment verification failed. Status: ' . $failureState,
            'source' => 'system',
            'logged_at' => now(),
        ]);

        Log::warning('PhonePe payment marked failed with order pending', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'payment_state' => $failureState,
        ]);
    }

    protected function clearCartForOrder(Order $order): void
    {
        if ($order->user_id) {
            Cart::query()->where('user_id', $order->user_id)->delete();
            Log::info('PhonePe cart cleared for user', [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
            ]);
            return;
        }

        if ($order->session_id) {
            Cart::query()
                ->whereNull('user_id')
                ->where('session_id', $order->session_id)
                ->delete();

            Log::info('PhonePe cart cleared for guest session', [
                'order_id' => $order->id,
                'session_id' => $order->session_id,
            ]);
        }
    }

    protected function isValidWebhookAuthorization(Request $request): bool
    {
        $username = trim((string) config('services.phonepe.webhook_username', ''));
        $password = trim((string) config('services.phonepe.webhook_password', ''));
        $incoming = trim((string) $request->header('Authorization', ''));
        $isTestMode = (bool) config('services.phonepe.test_mode', true);

        if ($username === '' || $password === '') {
            if ($isTestMode) {
                Log::info('PhonePe webhook auth bypassed in test mode due to empty credentials.');
                return true;
            }

            Log::error('PhonePe webhook credentials are missing in live mode.');
            return false;
        }

        if ($incoming === '') {
            Log::warning('PhonePe webhook authorization header missing');
            return false;
        }

        $expectedHash = hash('sha256', $username . ':' . $password);
        $normalizedIncoming = Str::lower(trim((string) (preg_replace('/^sha256[\s:(]*/i', '', rtrim($incoming, ')')) ?? '')));

        $candidates = [
            Str::lower($expectedHash),
            Str::lower('SHA256 ' . $expectedHash),
            Str::lower('SHA256(' . $expectedHash . ')'),
            Str::lower($username . ':' . $password),
            Str::lower('SHA256(' . $username . ':' . $password . ')'),
        ];

        foreach ($candidates as $candidate) {
            if (hash_equals($candidate, $normalizedIncoming)) {
                Log::info('PhonePe webhook authorization validated.');
                return true;
            }
        }

        Log::warning('PhonePe webhook authorization did not match expected hash.');
        return false;
    }
}
