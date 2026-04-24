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
        $order = Order::query()->where('order_number', $orderNumber)->first();

        if (! $order) {
            return redirect()->route('order.checkout')->with('error', 'Order not found for payment verification.');
        }

        $verification = $this->paymentGateway->verifyPayment($order->order_number);
        $this->syncOrderPaymentState($order, $verification);

        if ($verification['success'] ?? false) {
            return redirect()->route('order.checkout')->with('success', 'Payment completed successfully.');
        }

        return redirect()->route('order.checkout')->with('error', 'Payment was not completed. You can try again.');
    }

    public function handleCallback(Request $request)
    {
        if (! $this->isValidWebhookAuthorization($request)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized webhook request'], 401);
        }

        $payload = $request->all();
        $orderNumber = (string) (data_get($payload, 'payload.merchantOrderId')
            ?? data_get($payload, 'payload.merchantTransactionId')
            ?? '');

        if ($orderNumber === '') {
            return response()->json(['success' => false, 'message' => 'Missing merchantOrderId'], 422);
        }

        $order = Order::query()->where('order_number', $orderNumber)->first();

        if (! $order) {
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
            $verification = $this->paymentGateway->verifyPayment($order->order_number);
        }

        $this->syncOrderPaymentState($order, $verification);

        return response()->json([
            'success' => true,
            'payment_status' => $order->payment_status,
        ]);
    }

    protected function syncOrderPaymentState(Order $order, array $verification): void
    {
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
                'status' => $order->status === 'pending' ? 'confirmed' : $order->status,
            ]);

            OrderStatusLog::query()->create([
                'order_id' => $order->id,
                'status' => $order->status,
                'note' => 'PhonePe payment verified successfully.',
                'source' => 'system',
                'logged_at' => now(),
            ]);

            $this->clearCartForOrder($order);

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

        if ($order->payment_status !== 'failed') {
            $order->update([
                'payment_gateway' => 'phonepe',
                'payment_gateway_order_id' => $gatewayOrderId !== '' ? $gatewayOrderId : $order->payment_gateway_order_id,
                'payment_gateway_transaction_id' => $gatewayTransactionId !== '' ? $gatewayTransactionId : $order->payment_gateway_transaction_id,
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
        }
    }

    protected function clearCartForOrder(Order $order): void
    {
        if ($order->user_id) {
            Cart::query()->where('user_id', $order->user_id)->delete();
            return;
        }

        if ($order->session_id) {
            Cart::query()
                ->whereNull('user_id')
                ->where('session_id', $order->session_id)
                ->delete();
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
                return true;
            }

            Log::error('PhonePe webhook credentials are missing in live mode.');
            return false;
        }

        if ($incoming === '') {
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
                return true;
            }
        }

        return false;
    }
}
