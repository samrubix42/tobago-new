
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Checkout</p>
            <h1 class="text-2xl sm:text-3xl font-semibold text-white mt-1">Your Cart</h1>
            <p class="text-sm text-slate-400 mt-2">Coupons support both percentage and fixed discount values.</p>
        </div>

        <a
            href="{{ route('demo.products') }}"
            wire:navigate
            class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-white hover:bg-white/10 transition"
        >
            <i class="ri-arrow-left-line"></i>
            Continue Shopping
        </a>
    </div>

    @if($items->isEmpty())
        <div class="rounded-2xl border border-dashed border-white/15 bg-white/3 p-10 text-center">
            <p class="text-slate-300">Your cart is empty.</p>
            <a href="{{ route('demo.products') }}" wire:navigate class="mt-4 inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-500 transition">
                <i class="ri-shopping-bag-3-line"></i>
                Browse Products
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <section class="lg:col-span-2 rounded-2xl border border-white/10 bg-[#0b0d0f] overflow-hidden">
                <div class="divide-y divide-white/10">
                    @foreach($items as $item)
                        @php
                            $product = $item->product;
                            $image = $product?->images?->firstWhere('is_primary', true)?->image ?? $product?->images?->first()?->image;
                        @endphp

                        <div wire:key="cart-item-{{ $item->id }}" class="p-4 sm:p-5 flex gap-4">
                            <div class="h-20 w-20 rounded-xl bg-white/4 border border-white/10 flex items-center justify-center overflow-hidden shrink-0">
                                @if($image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $product?->name }}" class="h-full w-full object-cover">
                                @else
                                    <img src="{{ asset('images/hero.png') }}" alt="{{ $product?->name }}" class="h-14 object-contain opacity-80">
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm sm:text-base font-semibold text-white truncate">{{ $product?->name ?? 'Deleted product' }}</h3>
                                <p class="text-xs text-slate-400 mt-1">Rs {{ number_format((float) $item->price, 2) }} each</p>

                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    <div class="inline-flex items-center border border-white/15 rounded-md overflow-hidden">
                                        <button type="button" wire:click="decrement({{ $item->id }})" class="px-2.5 py-1.5 text-slate-200 hover:bg-white/5">-</button>
                                        <span class="px-3 text-sm text-white">{{ $item->quantity }}</span>
                                        <button type="button" wire:click="increment({{ $item->id }})" class="px-2.5 py-1.5 text-slate-200 hover:bg-white/5">+</button>
                                    </div>

                                    <button type="button" wire:click="removeItem({{ $item->id }})" class="text-xs text-rose-300 hover:text-rose-200 inline-flex items-center gap-1">
                                        <i class="ri-delete-bin-line"></i>
                                        Remove
                                    </button>
                                </div>
                            </div>

                            <div class="text-sm font-semibold text-white shrink-0">
                                Rs {{ number_format((float) $item->total, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <aside class="rounded-2xl border border-white/10 bg-[#0b0d0f] p-5 space-y-5 h-fit">
                <h2 class="text-base font-semibold text-white">Order Summary</h2>

                <form wire:submit="applyCoupon" class="space-y-2">
                    <label for="couponCode" class="text-xs text-slate-400">Coupon Code</label>
                    <div class="flex gap-2">
                        <input
                            id="couponCode"
                            type="text"
                            wire:model.defer="couponCode"
                            placeholder="e.g. SAVE10"
                            class="w-full rounded-md border border-white/15 bg-white/3 px-3 py-2 text-sm text-white placeholder:text-slate-500 outline-none focus:border-blue-500"
                        >
                        <button type="submit" class="rounded-md bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-500 transition">Apply</button>
                    </div>
                    @error('couponCode') <p class="text-xs text-rose-300">{{ $message }}</p> @enderror
                </form>

                @if($cart?->coupon)
                    <div class="rounded-md border border-emerald-400/30 bg-emerald-500/10 px-3 py-2 flex items-center justify-between gap-2">
                        <p class="text-xs text-emerald-200">
                            Coupon: <span class="font-semibold">{{ $cart->coupon->code }}</span>
                            ({{ ucfirst($cart->coupon->type) }})
                        </p>
                        <button type="button" wire:click="removeCoupon" class="text-xs text-emerald-100 hover:text-white">Remove</button>
                    </div>
                @endif

                <div class="space-y-2 text-sm">
                    <div class="flex items-center justify-between text-slate-300">
                        <span>Subtotal</span>
                        <span>Rs {{ number_format((float) ($cart?->subtotal ?? 0), 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-300">
                        <span>Discount</span>
                        <span>- Rs {{ number_format((float) ($cart?->discount ?? 0), 2) }}</span>
                    </div>
                    <div class="border-t border-white/10 pt-2 flex items-center justify-between text-white font-semibold">
                        <span>Total</span>
                        <span>Rs {{ number_format((float) ($cart?->total ?? 0), 2) }}</span>
                    </div>
                </div>

                <button type="button" class="w-full rounded-md bg-white text-black py-2.5 text-sm font-semibold hover:opacity-90 transition">
                    Proceed to Checkout
                </button>
            </aside>
        </div>
    @endif
</div>