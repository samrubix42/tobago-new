<?php

use App\Models\Order;
use App\Models\OrderStatusLog;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::admin')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = 'all';
    public string $deliveryType = 'all';
    public int $perPage = 15;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingDeliveryType(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->status = 'all';
        $this->deliveryType = 'all';
        $this->perPage = 15;
        $this->resetPage();
    }

    public function updateStatus(int $orderId, string $status): void
    {
        $allowed = ['pending', 'confirmed', 'packed', 'shipped', 'on-the-way', 'delivered', 'returned', 'cancelled'];
        if (! in_array($status, $allowed, true)) {
            return;
        }

        $order = Order::query()->whereKey($orderId)->firstOrFail();
        $oldStatus = $order->status;

        if ($oldStatus === $status) {
            return;
        }

        $order->update(['status' => $status]);

        OrderStatusLog::query()->create([
            'order_id' => $order->id,
            'status' => $status,
            'note' => $this->customerStatusNote($oldStatus, $status),
            'source' => 'admin',
            'logged_at' => now(),
        ]);

        $this->dispatch('toast-show', [
            'message' => 'Order status updated.',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    protected function customerStatusNote(string $oldStatus, string $newStatus): string
    {
        return match ($newStatus) {
            'pending' => 'Your order is pending and will be reviewed shortly.',
            'confirmed' => 'Your order has been confirmed and is now being prepared.',
            'packed' => 'Your order has been packed and is ready for dispatch.',
            'shipped' => 'Your order has been shipped and is on its way.',
            'on-the-way' => 'Your order is out for delivery and will arrive soon.',
            'delivered' => 'Your order has been delivered successfully.',
            'returned' => 'Your order has been marked as returned.',
            'cancelled' => 'Your order has been cancelled. Please contact support if you need help.',
            default => 'Your order status has been updated from ' . $this->statusLabel($oldStatus) . ' to ' . $this->statusLabel($newStatus) . '.',
        };
    }

    protected function statusLabel(string $status): string
    {
        return ucwords(str_replace('-', ' ', $status));
    }

    public function render()
    {
        $baseQuery = Order::query()
            ->withCount('items')
            ->when($this->search !== '', function ($query): void {
                $query->where(function ($inner): void {
                    $inner->where('order_number', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                        ->orWhere('customer_phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status !== 'all', fn ($query) => $query->where('status', $this->status))
            ->when($this->deliveryType !== 'all', fn ($query) => $query->where('delivery_type', $this->deliveryType));

        $orders = (clone $baseQuery)
            ->latest('id')
            ->paginate($this->perPage);

        return view('components.admin.order.order-list.order-list', [
            'orders' => $orders,
        ]);
    }
};
