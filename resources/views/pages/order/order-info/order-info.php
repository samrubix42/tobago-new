<?php

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public int $orderId;

    public function mount(int $orderId): void
    {
        $this->orderId = $orderId;
    }

    public function downloadBill()
    {
        $order = $this->resolveOrder();

        $pdf = Pdf::loadView('pdf.order-invoice', [
            'order' => $order,
        ])->setPaper('a4');

        $filename = 'invoice-' . $order->order_number . '.pdf';

        return response()->streamDownload(function () use ($pdf): void {
            echo $pdf->output();
        }, $filename);
    }

    protected function resolveOrder(): Order
    {
        return Order::query()
            ->with(['items.product.images', 'statusLogs'])
            ->where('user_id', (int) Auth::id())
            ->whereKey($this->orderId)
            ->firstOrFail();
    }

    public function render()
    {
        $order = $this->resolveOrder();

        return view('pages.order.order-info.order-info', [
            'order' => $order,
        ]);
    }
};
