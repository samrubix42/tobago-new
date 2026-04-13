<?php

use App\Models\Order;
use App\Models\OrderStatusLog;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::admin')] class extends Component
{
    public int $id;

    public string $status = 'pending';
    public string $deliveryType = 'in_hand_delivery';

    public ?string $deliveryPartner = null;
    public ?string $deliveryBoyName = null;
    public ?string $deliveryBoyPhone = null;
    public ?string $awbNumber = null;
    public ?string $trackingUrl = null;
    public ?string $estimatedDeliveryAt = null;
    public ?string $statusNote = null;

    public function mount(int $id): void
    {
        $this->id = $id;

        $order = $this->resolveOrder();
        $this->status = $order->status;
        $this->deliveryType = $order->delivery_type;
        $this->deliveryPartner = $order->delivery_partner;
        $this->deliveryBoyName = $order->delivery_boy_name;
        $this->deliveryBoyPhone = $order->delivery_boy_phone;
        $this->awbNumber = $order->awb_number;
        $this->trackingUrl = $order->tracking_url;
        $this->estimatedDeliveryAt = optional($order->estimated_delivery_at)->format('Y-m-d\\TH:i');
    }

    public function updateOrder(): void
    {
        $rules = [
            'status' => ['required', 'in:pending,confirmed,packed,shipped,on-the-way,delivered,returned,cancelled'],
            'deliveryType' => ['required', 'in:in_hand_delivery,third_party'],
            'estimatedDeliveryAt' => ['nullable', 'date'],
            'statusNote' => ['nullable', 'string', 'max:500'],
        ];

        if ($this->deliveryType === 'third_party') {
            $rules['deliveryPartner'] = ['nullable', 'string', 'max:255'];
            $rules['awbNumber'] = ['nullable', 'string', 'max:255'];
            $rules['trackingUrl'] = ['nullable', 'url', 'max:500'];
        } else {
            $rules['deliveryBoyName'] = ['nullable', 'string', 'max:255'];
            $rules['deliveryBoyPhone'] = ['nullable', 'regex:/^[0-9]{10}$/'];
        }

        $this->validate($rules, [
            'deliveryBoyPhone.regex' => 'Delivery boy phone must be 10 digits.',
        ]);

        $order = $this->resolveOrder();
        $oldStatus = $order->status;

        $order->update([
            'status' => $this->status,
            'delivery_type' => $this->deliveryType,
            'delivery_partner' => $this->deliveryType === 'third_party' ? $this->deliveryPartner : null,
            'awb_number' => $this->deliveryType === 'third_party' ? $this->awbNumber : null,
            'tracking_url' => $this->deliveryType === 'third_party' ? $this->trackingUrl : null,
            'delivery_boy_name' => $this->deliveryType === 'in_hand_delivery' ? $this->deliveryBoyName : null,
            'delivery_boy_phone' => $this->deliveryType === 'in_hand_delivery' ? $this->deliveryBoyPhone : null,
            'estimated_delivery_at' => $this->estimatedDeliveryAt ?: null,
        ]);

        if ($oldStatus !== $this->status || ! empty($this->statusNote)) {
            OrderStatusLog::query()->create([
                'order_id' => $order->id,
                'status' => $this->status,
                'note' => $this->statusNote ?: ('Status updated to ' . ucwords(str_replace('-', ' ', $this->status))),
                'source' => 'admin',
                'logged_at' => now(),
            ]);
        }

        $this->statusNote = null;

        $this->dispatch('toast-show', [
            'message' => 'Order updated successfully.',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    protected function resolveOrder(): Order
    {
        return Order::query()
            ->with(['items.product.images', 'statusLogs'])
            ->whereKey($this->id)
            ->firstOrFail();
    }

    public function render()
    {
        return view('components.admin.order.order-management.order-management', [
            'order' => $this->resolveOrder(),
        ]);
    }
};