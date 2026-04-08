<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 space-y-6">
    @php
        $statusSteps = ['pending', 'confirmed', 'packed', 'shipped', 'on-the-way', 'delivered'];
    @endphp

    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Order Info</p>
            <h1 class="text-2xl sm:text-3xl font-semibold text-white mt-1">{{ $order->order_number }}</h1>
            <p class="text-sm text-slate-400 mt-2">Placed on {{ optional($order->placed_at)->format('d M Y, h:i A') ?? $order->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <a href="{{ route('user.orders') }}" wire:navigate class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-white hover:bg-white/10 transition">
            <i class="ri-arrow-left-line"></i>
            Back to Orders
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <section class="lg:col-span-2 rounded-2xl border border-white/10 bg-[#0b0d0f] p-5 space-y-5">
            <div class="rounded-xl border border-white/10 bg-white/2 p-4">
                <h3 class="text-sm font-semibold text-white">Order Journey</h3>
                <div class="mt-4 overflow-x-auto">
                    <div class="min-w-170">
                        <div class="flex items-center">
                            @foreach($statusSteps as $idx => $step)
                                @php
                                    $stepDone = in_array($step, $order->statusLogs->pluck('status')->toArray(), true) || $order->status === $step;
                                    $isCurrent = $order->status === $step;
                                    $log = $order->statusLogs->firstWhere('status', $step);
                                @endphp
                                <div class="flex-1 flex items-center {{ $idx === count($statusSteps) - 1 ? '' : 'pr-2' }}">
                                    <div class="flex flex-col items-center text-center w-full">
                                        <div class="h-8 w-8 rounded-full border flex items-center justify-center text-xs font-semibold {{ $stepDone ? 'border-emerald-400 bg-emerald-500/20 text-emerald-200' : 'border-white/20 text-slate-400' }} {{ $isCurrent ? 'ring-2 ring-emerald-400/30' : '' }}">{{ $idx + 1 }}</div>
                                        <p class="mt-2 text-[11px] {{ $stepDone ? 'text-white' : 'text-slate-500' }}">{{ ucwords(str_replace('-', ' ', $step)) }}</p>
                                        <p class="text-[10px] text-slate-500 mt-0.5">{{ optional($log?->logged_at)->format('d M, h:i A') ?? '-' }}</p>
                                    </div>
                                    @if($idx !== count($statusSteps) - 1)
                                        <div class="h-0.5 flex-1 {{ $stepDone ? 'bg-emerald-400/60' : 'bg-white/15' }}"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                @foreach($order->items as $item)
                    @php
                        $image = $item->product?->images?->firstWhere('is_primary', true)?->image ?? $item->product?->images?->first()?->image;
                    @endphp
                    <div class="flex items-start justify-between gap-3 border border-white/10 rounded-lg p-3 bg-white/2">
                        <div class="flex items-start gap-3 min-w-0">
                            <div class="h-14 w-14 rounded-lg overflow-hidden border border-white/10 bg-white/4 shrink-0 flex items-center justify-center">
                                @if($image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $item->product_name }}" class="h-full w-full object-cover">
                                @else
                                    <img src="{{ asset('images/hero.png') }}" alt="{{ $item->product_name }}" class="h-10 object-contain opacity-80">
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $item->product_name }}</p>
                                <p class="text-xs text-slate-400 mt-1">Qty: {{ $item->quantity }} • Price: Rs {{ number_format((float) $item->price, 2) }}</p>
                                @if($item->sku)
                                    <p class="text-[11px] text-slate-500 mt-1">SKU: {{ $item->sku }}</p>
                                @endif
                            </div>
                        </div>
                        <p class="text-sm font-semibold text-white">Rs {{ number_format((float) $item->total, 2) }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <aside class="rounded-2xl border border-white/10 bg-[#0b0d0f] p-5 space-y-4 h-fit text-xs">
            <h3 class="text-sm font-semibold text-white">Delivery & Tracking</h3>

            <div class="rounded-lg border border-white/10 bg-white/2 p-3">
                <p class="text-slate-400">Delivery Type</p>
                <p class="text-white mt-1 font-semibold">{{ $order->delivery_type === 'third_party' ? '3rd Party Courier' : 'In-hand Delivery' }}</p>
            </div>

            @if($order->delivery_type === 'in_hand_delivery')
                <div class="rounded-lg border border-white/10 bg-white/2 p-3">
                    <p class="text-slate-400">Delivery Boy</p>
                    <p class="text-white mt-1">{{ $order->delivery_boy_name ?: 'Will be assigned soon' }}</p>
                    <p class="text-slate-400 mt-3">Phone</p>
                    <p class="text-white mt-1">{{ $order->delivery_boy_phone ?: '-' }}</p>
                </div>
            @else
                <div class="rounded-lg border border-white/10 bg-white/2 p-3">
                    <p class="text-slate-400">Courier Partner</p>
                    <p class="text-white mt-1">{{ $order->delivery_partner ?: '-' }}</p>
                    <p class="text-slate-400 mt-3">AWB Number</p>
                    <p class="text-white mt-1 font-semibold">{{ $order->awb_number ?: 'Not available yet' }}</p>
                    <p class="text-slate-400 mt-3">Tracking</p>
                    @if($order->tracking_url)
                        <a href="{{ $order->tracking_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-blue-300 hover:text-blue-200 mt-1">Track Package <i class="ri-external-link-line"></i></a>
                    @else
                        <p class="text-white mt-1">Tracking link will be added soon</p>
                    @endif
                </div>
            @endif

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
