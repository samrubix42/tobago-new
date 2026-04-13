
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Account</p>
            <h1 class="text-2xl sm:text-3xl font-semibold text-white mt-1">My Orders</h1>
            <p class="text-sm text-slate-400 mt-2">Track all your placed orders and payment status.</p>
        </div>
        <a href="{{ route('demo.products') }}" wire:navigate class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-white hover:bg-white/10 transition">
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
                    <div class="p-4 sm:p-5 border-b border-white/10 grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                        <div class="min-w-0">
                            <p class="text-xs text-slate-400">Order ID</p>
                            <p class="text-sm font-semibold text-white">#{{ $order->id }}</p>
                            <p class="text-xs text-slate-500 mt-0.5 truncate">{{ $order->order_number }}</p>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs text-slate-400">Placed</p>
                            <p class="text-xs text-slate-300 mt-1">{{ optional($order->placed_at)->format('d M Y, h:i A') ?? $order->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="flex sm:justify-end flex-wrap items-center gap-2">
                            <span class="text-xs px-2.5 py-1 rounded-full {{ $order->status === 'delivered' ? 'bg-emerald-500/15 text-emerald-300' : ($order->status === 'cancelled' ? 'bg-rose-500/15 text-rose-300' : 'bg-amber-500/15 text-amber-300') }}">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>

                    <div class="p-4 sm:p-5 space-y-3">
                        @foreach($order->items as $item)
                            @php
                                $image = $item->product_image
                                    ?? $item->product?->images?->firstWhere('is_primary', true)?->image
                                    ?? $item->product?->images?->first()?->image;
                            @endphp

                            <article class="rounded-xl border border-white/10 bg-white/2 p-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0 w-full">
                                    <div class="h-14 w-14 rounded-lg overflow-hidden border border-white/10 bg-white/4 shrink-0 flex items-center justify-center">
                                        @if($image)
                                            <img src="{{ str_starts_with($image, 'http') ? $image : asset('storage/' . ltrim($image, '/')) }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover">
                                        @else
                                            <img src="{{ asset('images/hero.png') }}" alt="{{ $item->product_name }}" class="h-10 object-contain opacity-80">
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm text-white font-medium truncate">{{ $item->product_name }}</p>
                                        <p class="text-xs text-slate-400 mt-1">Qty: {{ $item->quantity }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('user.orders.info', $order->id) }}" wire:navigate class="text-xs text-blue-300 hover:text-blue-200 whitespace-nowrap sm:ml-3 self-start sm:self-center">
                                    View Details
                                </a>
                            </article>
                        @endforeach

                        <div class="pt-1 flex justify-end">
                            <a href="{{ route('user.orders.info', $order->id) }}" wire:navigate class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-md border border-white/20 px-3 py-2 text-xs text-white hover:bg-white/5">
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