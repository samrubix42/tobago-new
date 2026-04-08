<?php

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public int $perPage = 10;

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