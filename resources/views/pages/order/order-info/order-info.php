<?php

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public int $orderId;

    public function mount(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function render()
    {
        $order = Order::query()
            ->with(['items.product.images', 'statusLogs'])
            ->where('user_id', (int) Auth::id())
            ->whereKey($this->orderId)
            ->firstOrFail();

        return view('pages.order.order-info.order-info', [
            'order' => $order,
        ]);
    }
};
