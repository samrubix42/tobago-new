<?php

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public int $perPage = 10;

    public function retryPayment(int $orderId, \App\Contracts\PaymentGatewayInterface $paymentGateway)
    {
        $order = Order::query()
            ->where('user_id', (int) Auth::id())
            ->whereKey($orderId)
            ->firstOrFail();

        if ($order->payment_status === 'paid' || $order->status === 'cancelled') {
            $this->dispatch('toast-show', [
                'message' => 'Payment already completed or order cancelled.',
                'type' => 'warning',
                'position' => 'top-right',
            ]);
            return null;
        }

        $paymentResponse = $paymentGateway->initiatePayment($order);

        $order->update([
            'payment_gateway' => 'phonepe',
            'payment_gateway_order_id' => (string) ($paymentResponse['gateway_order_id'] ?? $order->payment_gateway_order_id),
            'payment_state' => (string) ($paymentResponse['status'] ?? $order->payment_state),
            'payment_response_payload' => is_array($paymentResponse['payload'] ?? null) ? $paymentResponse['payload'] : null,
        ]);

        if (! ($paymentResponse['success'] ?? false) || empty($paymentResponse['redirect_url'])) {
            $this->dispatch('toast-show', [
                'message' => (string) ($paymentResponse['message'] ?? 'Unable to initiate payment. Please try again later.'),
                'type' => 'error',
                'position' => 'top-right',
            ]);
            return null;
        }

        return redirect()->away((string) $paymentResponse['redirect_url']);
    }

    public function render()
    {
        $orders = Order::query()
            ->with(['items.product.images', 'statusLogs'])
            ->where('user_id', (int) Auth::id())
            ->orderByDesc('id')
            ->paginate($this->perPage);

        return view('pages.order.my-order.my-order', [
            'orders' => $orders,
        ]);
    }
};