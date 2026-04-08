
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Account</p>
            <h1 class="text-2xl sm:text-3xl font-semibold text-white mt-1">My Orders</h1>
            <p class="text-sm text-slate-400 mt-2">Track all your placed orders and payment status.</p>
        </div>
        <a href="{{ route('demo.products') }}" wire:navigate class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-white hover:bg-white/10 transition">
            <i class="ri-shopping-bag-3-line"></i>
            Shop More
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="rounded-2xl border border-dashed border-white/15 bg-white/3 p-10 text-center text-slate-300">
            You have not placed any orders yet.
            <div class="mt-4">
                <a href="{{ route('demo.products') }}" wire:navigate class="inline-flex rounded-md bg-blue-600 px-4 py-2 text-sm text-white">Browse Products</a>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <section wire:key="order-{{ $order->id }}" class="rounded-2xl border border-white/10 bg-[#0b0d0f] overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-white/10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="text-xs text-slate-400">Order Number</p>
                            <p class="text-sm font-semibold text-white">{{ $order->order_number }}</p>
                            <p class="text-xs text-slate-500 mt-1">Placed: {{ optional($order->placed_at)->format('d M Y, h:i A') ?? $order->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs px-2.5 py-1 rounded-full {{ $order->status === 'delivered' ? 'bg-emerald-500/15 text-emerald-300' : ($order->status === 'cancelled' ? 'bg-rose-500/15 text-rose-300' : 'bg-amber-500/15 text-amber-300') }}">{{ ucfirst($order->status) }}</span>
                            <span class="text-xs px-2.5 py-1 rounded-full {{ $order->payment_status === 'paid' ? 'bg-emerald-500/15 text-emerald-300' : 'bg-slate-500/20 text-slate-300' }}">Payment: {{ ucfirst($order->payment_status) }}</span>
                            <span class="text-xs px-2.5 py-1 rounded-full bg-blue-500/15 text-blue-300">{{ strtoupper($order->payment_method) }}</span>
                            <span class="text-xs px-2.5 py-1 rounded-full bg-indigo-500/15 text-indigo-300">{{ $order->delivery_type === 'third_party' ? '3rd Party' : 'In-hand' }}</span>
                        </div>
                    </div>

                    <div class="p-4 sm:p-5">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                            <div class="rounded-lg border border-white/10 bg-white/2 p-3">
                                <p class="text-slate-400">Items</p>
                                <p class="text-white font-semibold mt-1">{{ $order->items->sum('quantity') }}</p>
                            </div>
                            <div class="rounded-lg border border-white/10 bg-white/2 p-3">
                                <p class="text-slate-400">Subtotal</p>
                                <p class="text-white font-semibold mt-1">Rs {{ number_format((float) $order->subtotal, 2) }}</p>
                            </div>
                            <div class="rounded-lg border border-white/10 bg-white/2 p-3">
                                <p class="text-slate-400">Shipping</p>
                                <p class="text-white font-semibold mt-1">Rs {{ number_format((float) $order->shipping_amount, 2) }}</p>
                            </div>
                            <div class="rounded-lg border border-white/10 bg-white/2 p-3">
                                <p class="text-slate-400">Total</p>
                                <p class="text-white font-semibold mt-1">Rs {{ number_format((float) $order->total, 2) }}</p>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <a href="{{ route('user.orders.info', $order->id) }}" wire:navigate class="inline-flex items-center gap-2 rounded-md border border-white/20 px-3 py-2 text-xs text-white hover:bg-white/5">
                                View Full Order Details
                                <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                    </div>
                </section>
            @endforeach
        </div>

        <div class="pt-2">
            {{ $orders->onEachSide(1)->links() }}
        </div>
    @endif
</div>