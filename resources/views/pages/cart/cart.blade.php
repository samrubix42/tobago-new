<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-10 space-y-7">
    <div class="rounded-2xl border border-white/10 bg-[radial-gradient(circle_at_top_right,rgba(59,130,246,0.16),transparent_45%),#0b0d0f] px-4 sm:px-6 py-5 sm:py-6">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Cart</p>
                <h1 class="text-2xl sm:text-3xl font-semibold text-white mt-1">Your Shopping Cart</h1>
                <p class="text-sm text-slate-300/90 mt-2">Review items, update quantities, apply coupon, and proceed to secure checkout.</p>
            </div>

            <a
                href="{{ route('products') }}"
                wire:navigate
                class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-white hover:bg-white/10 transition"
            >
                <i class="ri-arrow-left-line"></i>
                Continue Shopping
            </a>
        </div>
    </div>

    @if($items->isEmpty())
        <div class="rounded-2xl border border-dashed border-white/15 bg-white/3 p-8 sm:p-10 text-center">
            <div class="mx-auto w-14 h-14 rounded-2xl border border-white/10 bg-white/5 flex items-center justify-center text-white/70">
                <i class="ri-shopping-cart-line text-2xl"></i>
            </div>
            <p class="text-slate-200 text-lg mt-4">Your cart is empty</p>
            <p class="text-sm text-slate-400 mt-1">Looks like you have not added anything yet.</p>
            <a href="{{ route('products') }}" wire:navigate class="mt-5 inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2.5 text-sm text-white hover:bg-blue-500 transition">
                <i class="ri-shopping-bag-3-line"></i>
                Browse Products
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 xl:gap-8">
            <section class="lg:col-span-2 space-y-3">
                @foreach($items as $item)
                    @php
                        $product = $item->product;
                        $image = $product?->images?->firstWhere('is_primary', true)?->image_path
                            ?? $product?->images?->firstWhere('is_primary', true)?->image
                            ?? $product?->images?->first()?->image_path
                            ?? $product?->images?->first()?->image;
                    @endphp

                    <article wire:key="cart-item-{{ $item->id }}" class="rounded-2xl border border-white/10 bg-[#0b0d0f] p-3.5 sm:p-4.5">
                        <div class="grid grid-cols-[80px_1fr] sm:grid-cols-[96px_1fr] gap-3 sm:gap-4 items-start">
                            <div class="h-20 w-20 sm:h-24 sm:w-24 rounded-xl bg-white/4 border border-white/10 flex items-center justify-center overflow-hidden">
                                @if($image)
                                    <img src="{{ str_starts_with($image, 'http') ? $image : asset('storage/' . ltrim($image, '/')) }}" alt="{{ $product?->name }}" class="h-full w-full object-cover">
                                @else
                                    <img src="{{ asset('images/hero.png') }}" alt="{{ $product?->name }}" class="h-14 object-contain opacity-80">
                                @endif
                            </div>

                            <div class="min-w-0">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-2.5">
                                    <div class="min-w-0">
                                        <h3 class="text-sm sm:text-[15px] font-semibold text-white leading-snug line-clamp-2">{{ $product?->name ?? 'Deleted product' }}</h3>
                                        <p class="text-xs text-slate-400 mt-1">Unit Price: Rs {{ number_format((float) $item->price, 2) }}</p>
                                    </div>
                                    <div class="inline-flex items-center gap-2 self-start rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-300 md:ml-3">
                                        <span>Item Total</span>
                                        <span class="text-white font-semibold">Rs {{ number_format((float) $item->total, 2) }}</span>
                                    </div>
                                </div>

                                <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="inline-flex items-center rounded-lg border border-white/15 overflow-hidden w-full sm:w-auto">
                                        <button type="button" wire:click="decrement({{ $item->id }})" class="px-3 py-2 text-slate-200 hover:bg-white/5 flex-1 sm:flex-none" aria-label="Decrease quantity">
                                            <i class="ri-subtract-line"></i>
                                        </button>
                                        <span class="px-4 text-sm text-white font-medium min-w-10 text-center flex-1 sm:flex-none">{{ $item->quantity }}</span>
                                        <button type="button" wire:click="increment({{ $item->id }})" class="px-3 py-2 text-slate-200 hover:bg-white/5 flex-1 sm:flex-none" aria-label="Increase quantity">
                                            <i class="ri-add-line"></i>
                                        </button>
                                    </div>

                                    <button type="button" wire:click="removeItem({{ $item->id }})" class="inline-flex items-center justify-center sm:justify-start gap-1.5 text-xs text-rose-300 hover:text-rose-200 w-full sm:w-auto">
                                        <i class="ri-delete-bin-line"></i>
                                        Remove item
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </section>

            <aside class="rounded-2xl border border-white/10 bg-[#0b0d0f] p-4 sm:p-5 space-y-5 h-fit lg:sticky lg:top-24">
                <h2 class="text-base font-semibold text-white">Order Summary</h2>

                <form wire:submit="applyCoupon" class="space-y-2.5">
                    <label for="couponCode" class="text-xs text-slate-400">Coupon Code</label>
                    <div class="flex gap-2">
                        <input
                            id="couponCode"
                            type="text"
                            wire:model.defer="couponCode"
                            placeholder="e.g. SAVE10"
                            class="w-full rounded-md border border-white/15 bg-white/3 px-3 py-2.5 text-sm text-white placeholder:text-slate-500 outline-none focus:border-blue-500"
                        >
                        <button type="submit" class="rounded-md bg-blue-600 px-3.5 py-2.5 text-xs font-semibold text-white hover:bg-blue-500 transition">Apply</button>
                    </div>
                    @error('couponCode') <p class="text-xs text-rose-300">{{ $message }}</p> @enderror
                </form>

                @if(session()->has('coupon_message'))
                    <div class="rounded-lg border px-3 py-2.5 text-xs leading-relaxed {{ session('coupon_message_type') === 'warning' ? 'border-amber-400/30 bg-amber-500/10 text-amber-200' : 'border-emerald-400/30 bg-emerald-500/10 text-emerald-200' }}">
                        {{ session('coupon_message') }}
                    </div>
                @endif

                @if($suggestedCoupons->isNotEmpty())
                    <div class="rounded-xl border border-white/10 bg-white/3 p-3 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <p class="text-xs uppercase tracking-[0.12em] text-slate-400">Suggested Coupons</p>
                            <span class="text-[11px] text-slate-500">Best for your cart</span>
                        </div>

                        <div class="space-y-2">
                            @foreach($suggestedCoupons as $suggestion)
                                <div class="rounded-lg border border-white/10 bg-[#0a0c0e] px-2.5 py-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <div class="flex flex-wrap items-center gap-1.5">
                                                <span class="inline-flex rounded-md border border-white/15 px-2 py-0.5 text-xs font-semibold text-white">{{ $suggestion['code'] }}</span>
                                                <span class="text-[11px] text-slate-400">
                                                    {{ $suggestion['type'] === 'percentage' ? rtrim(rtrim(number_format((float) $suggestion['value'], 2, '.', ''), '0'), '.') . '% OFF' : 'Rs ' . number_format((float) $suggestion['value'], 2) . ' OFF' }}
                                                </span>
                                            </div>

                                            @if($suggestion['is_applicable'])
                                                <p class="text-[11px] text-emerald-300 mt-1">Applicable now</p>
                                            @else
                                                <p class="text-[11px] text-amber-300 mt-1">Add Rs {{ number_format((float) $suggestion['remaining_amount'], 2) }} more to unlock</p>
                                            @endif
                                        </div>

                                        @if($suggestion['is_applicable'])
                                            <button type="button" wire:click="useSuggestedCoupon('{{ $suggestion['code'] }}')" class="text-[11px] rounded-md border border-white/15 px-2 py-1 text-slate-200 hover:bg-white/5">
                                                Use
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($cart?->coupon)
                    <div class="rounded-lg border border-emerald-400/30 bg-emerald-500/10 px-3 py-2.5 flex items-start justify-between gap-2">
                        <p class="text-xs text-emerald-200 leading-relaxed">
                            Coupon: <span class="font-semibold">{{ $cart->coupon->code }}</span>
                            ({{ $cart->coupon->type === 'percentage' ? rtrim(rtrim(number_format((float) $cart->coupon->value, 2, '.', ''), '0'), '.') . '% OFF' : 'Rs ' . number_format((float) $cart->coupon->value, 2) . ' OFF' }})
                        </p>
                        <button type="button" wire:click="removeCoupon" class="text-xs text-emerald-100 hover:text-white whitespace-nowrap">Remove</button>
                    </div>
                @endif

                <div class="space-y-2.5 text-sm">
                    <div class="flex items-center justify-between text-slate-300">
                        <span>Subtotal</span>
                        <span>Rs {{ number_format((float) ($cart?->subtotal ?? 0), 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-300">
                        <span>Discount</span>
                        <span>- Rs {{ number_format((float) ($cart?->discount ?? 0), 2) }}</span>
                    </div>
                    @if($cart?->coupon)
                        <div class="flex items-center justify-between text-xs text-slate-400">
                            <span>Discount Type</span>
                            <span>
                                @if($cart->coupon->type === 'percentage')
                                    {{ rtrim(rtrim(number_format((float) $cart->coupon->value, 2, '.', ''), '0'), '.') }}% of subtotal
                                @else
                                    Fixed Rs {{ number_format((float) $cart->coupon->value, 2) }}
                                @endif
                            </span>
                        </div>
                    @endif
                    <div class="flex items-center justify-between text-slate-300">
                        <span>Shipping</span>
                        <span>Rs {{ number_format((float) ($shippingAmount ?? 0), 2) }}</span>
                    </div>
                    <div class="border-t border-white/10 pt-2.5 flex items-center justify-between text-white font-semibold">
                        <span>Grand Total</span>
                        <span>Rs {{ number_format((float) ($grandTotal ?? 0), 2) }}</span>
                    </div>
                </div>

                <a href="{{ route('order.checkout') }}" wire:navigate class="inline-flex w-full items-center justify-center rounded-md bg-white text-black py-2.5 text-sm font-semibold hover:opacity-90 transition">
                    Proceed to Checkout
                </a>
            </aside>
        </div>
    @endif
</div>
