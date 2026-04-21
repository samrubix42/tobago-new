<?php

namespace App\Http\Controllers\Payment;

use App\Contracts\PaymentGatewayInterface;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderStatusLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
        $orderNumber = (string) data_get($request->all(), 'payload.merchantTransactionId', '');

        if ($orderNumber === '') {
            return response()->json(['success' => false, 'message' => 'Missing merchantTransactionId'], 422);
        }

        $order = Order::query()->where('order_number', $orderNumber)->first();

        if (! $order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $verification = $this->paymentGateway->verifyPayment($order->order_number);
        $this->syncOrderPaymentState($order, $verification);

        return response()->json([
            'success' => true,
            'payment_status' => $order->payment_status,
        ]);
    }

    protected function syncOrderPaymentState(Order $order, array $verification): void
    {
        if ($verification['success'] ?? false) {
            $order->update([
                'payment_status' => 'paid',
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

        $order->update([
            'payment_status' => 'failed',
        ]);

        OrderStatusLog::query()->create([
            'order_id' => $order->id,
            'status' => $order->status,
            'note' => 'PhonePe payment verification failed. Status: ' . (string) ($verification['status'] ?? 'UNKNOWN'),
            'source' => 'system',
            'logged_at' => now(),
        ]);
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
}
