<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 space-y-6">
    @php
        $statusOrder = ['pending', 'confirmed', 'packed', 'shipped', 'on-the-way', 'delivered', 'returned', 'cancelled'];
        $statusRank = array_flip($statusOrder);
        $timelineLogs = $order->statusLogs->sortBy(fn($log) => $statusRank[$log->status] ?? 999)->values();

        if ($timelineLogs->isEmpty()) {
            $timelineLogs = collect([(object) [
                'status' => $order->status,
                'note' => 'Order status updated.',
                'source' => 'system',
                'logged_at' => $order->placed_at ?? $order->created_at,
            ]]);
        }

        $shippedRank = $statusRank['shipped'] ?? 999;
    @endphp

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Order Info</p>
            <h1 class="text-2xl sm:text-3xl font-semibold text-white mt-1">{{ $order->order_number }}</h1>
            <p class="text-sm text-slate-400 mt-2">Placed on {{ optional($order->placed_at)->format('d M Y, h:i A') ?? $order->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <button
                type="button"
                wire:click="downloadBill"
                wire:loading.attr="disabled"
                wire:target="downloadBill"
                class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-white hover:bg-white/10 transition disabled:opacity-60 disabled:cursor-not-allowed"
            >
                <i class="ri-file-download-line" wire:loading.remove wire:target="downloadBill"></i>
                <i class="ri-loader-4-line animate-spin" wire:loading wire:target="downloadBill"></i>
                <span wire:loading.remove wire:target="downloadBill">Download Bill</span>
                <span wire:loading wire:target="downloadBill">Preparing PDF...</span>
            </button>

            <a href="{{ route('user.orders') }}" wire:navigate class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-white hover:bg-white/10 transition">
                <i class="ri-arrow-left-line"></i>
                Back to Orders
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <section class="xl:col-span-2 space-y-5">
            <div class="rounded-2xl border border-white/10 bg-[#0b0d0f] p-5">
                <div class="flex items-center justify-between gap-2 mb-4">
                    <h2 class="text-base font-semibold text-white">Ordered Items</h2>
                    <p class="text-xs text-slate-400">{{ $order->items->count() }} item(s)</p>
                </div>

                <div class="space-y-3">
                    @foreach($order->items as $item)
                        @php
                            $image = $item->product_image
                                ?? $item->product?->images?->firstWhere('is_primary', true)?->image
                                ?? $item->product?->images?->first()?->image;
                        @endphp
                        <article class="flex items-start justify-between gap-3 border border-white/10 rounded-xl p-3 bg-white/2">
                            <div class="flex items-start gap-3 min-w-0">
                                <div class="h-16 w-16 rounded-lg overflow-hidden border border-white/10 bg-white/4 shrink-0 flex items-center justify-center">
                                    @if($image)
                                        <img src="{{ str_starts_with($image, 'http') ? $image : asset('storage/' . ltrim($image, '/')) }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover">
                                    @else
                                        <img src="{{ asset('images/hero.png') }}" alt="{{ $item->product_name }}" class="h-10 object-contain opacity-80">
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-white truncate">{{ $item->product_name }}</p>
                                    <p class="text-xs text-slate-400 mt-1">Category: {{ $item->product_category ?: ($item->product?->category?->title ?: 'General') }}</p>
                                    <p class="text-xs text-slate-300 mt-1">Qty: {{ $item->quantity }} • Unit: Rs {{ number_format((float) $item->price, 2) }}</p>
                                    @if($item->sku)
                                        <p class="text-[11px] text-slate-500 mt-1">SKU: {{ $item->sku }}</p>
                                    @endif
                                </div>
                            </div>
                            <p class="text-sm font-semibold text-white whitespace-nowrap">Rs {{ number_format((float) $item->total, 2) }}</p>
                        </article>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-[#0b0d0f] p-5">
                <h2 class="text-base font-semibold text-white mb-4">Order Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="rounded-lg border border-white/10 bg-white/2 p-3">
                        <p class="text-slate-400">Order Number</p>
                        <p class="text-white mt-1 font-semibold">{{ $order->order_number }}</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/2 p-3">
                        <p class="text-slate-400">Order Date</p>
                        <p class="text-white mt-1">{{ optional($order->placed_at)->format('d M Y, h:i A') ?? $order->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/2 p-3">
                        <p class="text-slate-400">Payment Method</p>
                        <p class="text-white mt-1 uppercase">{{ $order->payment_method }}</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/2 p-3">
                        <p class="text-slate-400">Payment Status</p>
                        <p class="text-white mt-1 capitalize">{{ $order->payment_status }}</p>
                    </div>
                </div>

                @if($order->payment_gateway || $order->payment_gateway_transaction_id || $order->payment_failure_reason)
                    <div class="rounded-lg border border-white/10 bg-white/2 p-3 mt-3 text-xs space-y-1.5">
                        <p class="text-slate-400">Payment Response</p>
                        @if($order->payment_gateway)
                            <p class="text-slate-300">Gateway: <span class="text-white uppercase">{{ $order->payment_gateway }}</span></p>
                        @endif
                        @if($order->payment_state)
                            <p class="text-slate-300">Gateway State: <span class="text-white">{{ $order->payment_state }}</span></p>
                        @endif
                        @if($order->payment_gateway_transaction_id)
                            <p class="text-slate-300 break-all">Transaction ID: <span class="text-white">{{ $order->payment_gateway_transaction_id }}</span></p>
                        @endif
                        @if($order->payment_gateway_order_id)
                            <p class="text-slate-300 break-all">Gateway Order ID: <span class="text-white">{{ $order->payment_gateway_order_id }}</span></p>
                        @endif
                        @if($order->payment_verified_at)
                            <p class="text-slate-300">Verified At: <span class="text-white">{{ optional($order->payment_verified_at)->format('d M Y, h:i A') }}</span></p>
                        @endif
                        @if($order->payment_failure_reason)
                            <p class="text-rose-300">Reason: {{ $order->payment_failure_reason }}</p>
                        @endif
                    </div>
                @endif

                <div class="rounded-lg border border-white/10 bg-white/2 p-3 mt-3 text-xs">
                    <p class="text-slate-400">Delivery Address</p>
                    <p class="text-white mt-1 font-semibold">{{ $order->customer_name }} ({{ $order->customer_phone }})</p>
                    <p class="text-slate-300 mt-1">{{ $order->address_line1 }}{{ $order->address_line2 ? ', ' . $order->address_line2 : '' }}</p>
                    <p class="text-slate-300">{{ $order->city }}, {{ $order->state }}, {{ $order->country }} - {{ $order->pincode }}</p>
                </div>
            </div>
        </section>

        <aside class="rounded-2xl border border-white/10 bg-[#0b0d0f] p-5 space-y-4 h-fit xl:sticky xl:top-24 text-xs">
            <h3 class="text-sm font-semibold text-white">Tracking Status</h3>

            <div class="rounded-lg border border-white/10 bg-white/2 p-3">
                <p class="text-slate-400">Delivery Type</p>
                <p class="text-white mt-1 font-semibold">{{ $order->delivery_type === 'third_party' ? '3rd Party Courier' : 'In-hand Delivery' }}</p>
                <p class="text-slate-400 mt-2">Current Status</p>
                <p class="text-emerald-300 mt-1 font-semibold">{{ ucwords(str_replace('-', ' ', $order->status)) }}</p>
            </div>

            <div class="rounded-lg border border-white/10 bg-white/2 p-4">
                <div class="space-y-0">
                    @foreach($timelineLogs as $index => $log)
                        @php
                            $logRank = $statusRank[$log->status] ?? 999;
                            $isAfterShipping = $logRank >= $shippedRank;
                            $isLast = $index === $timelineLogs->count() - 1;
                        @endphp

                        <div class="relative pl-8 {{ $isLast ? '' : 'pb-6' }}">
                            @if(! $isLast)
                                <span class="absolute left-2.75 top-5 bottom-0 w-px bg-white/15"></span>
                            @endif

                            <span class="absolute left-0 top-1 inline-flex h-6 w-6 items-center justify-center rounded-full border border-emerald-400/40 bg-emerald-500/20 text-emerald-200">
                                <i class="ri-check-line text-xs"></i>
                            </span>

                            <p class="text-white font-semibold text-[13px]">{{ ucwords(str_replace('-', ' ', $log->status)) }}</p>
                            <p class="text-slate-400 text-[11px] mt-0.5">{{ optional($log->logged_at)->format('d M Y, h:i A') ?: '-' }}</p>

                            @if($log->note)
                                <p class="text-slate-300 mt-1.5 text-[11px]">{{ $log->note }}</p>
                            @endif

                            @if($isAfterShipping)
                                @if($order->delivery_type === 'third_party')
                                    <div class="mt-2 rounded-md border border-white/10 bg-black/20 p-2.5 space-y-1">
                                        <p class="text-slate-400">AWB Number</p>
                                        <p class="text-white font-semibold">{{ $order->awb_number ?: 'Awaiting courier update' }}</p>
                                        @if($order->tracking_url)
                                            <a href="{{ $order->tracking_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-blue-300 hover:text-blue-200">
                                                Track package
                                                <i class="ri-external-link-line"></i>
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <div class="mt-2 rounded-md border border-white/10 bg-black/20 p-2.5 space-y-1">
                                        <p class="text-slate-400">Delivery Boy</p>
                                        <p class="text-white">{{ $order->delivery_boy_name ?: 'Will be assigned soon' }}</p>
                                        <p class="text-slate-400">Phone</p>
                                        <p class="text-white">{{ $order->delivery_boy_phone ?: 'Will be shared after dispatch' }}</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-lg border border-white/10 bg-white/2 p-3">
                <p class="text-slate-400">Estimated Delivery</p>
                <p class="text-white mt-1">{{ optional($order->estimated_delivery_at)->format('d M Y') ?: '-' }}</p>
            </div>

            <div class="rounded-lg border border-white/10 bg-white/2 p-3 space-y-1.5">
                <div class="flex items-center justify-between text-slate-300"><span>Subtotal</span><span>Rs {{ number_format((float) $order->subtotal, 2) }}</span></div>
                <div class="flex items-center justify-between text-slate-300"><span>Discount</span><span>- Rs {{ number_format((float) $order->discount, 2) }}</span></div>
                <div class="flex items-center justify-between text-slate-300"><span>Shipping</span><span>Rs {{ number_format((float) $order->shipping_amount, 2) }}</span></div>
                <div class="border-t border-white/10 pt-1.5 flex items-center justify-between text-white font-semibold"><span>Total</span><span>Rs {{ number_format((float) $order->total, 2) }}</span></div>
            </div>
        </aside>
    </div>
</div>
