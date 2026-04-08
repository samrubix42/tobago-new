
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Demo</p>
            <h1 class="text-2xl sm:text-3xl font-semibold text-white mt-1">Product Demo Listing</h1>
            <p class="text-sm text-slate-400 mt-2">Use this page to test Add to Cart flow for guest and logged-in users.</p>
        </div>

        <a
            href="{{ route('cart') }}"
            wire:navigate
            class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-white hover:bg-white/10 transition"
        >
            <i class="ri-shopping-cart-2-line"></i>
            Go to Cart
        </a>
    </div>

    @if($products->isEmpty())
        <div class="rounded-2xl border border-dashed border-white/15 bg-white/3 p-10 text-center text-slate-400">
            No active products available for demo.
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5">
            @foreach($products as $product)
                @php
                    $image = $product->images->firstWhere('is_primary', true)?->image ?? $product->images->first()?->image;
                    $isOutOfStock = $product->is_out_of_stock || $product->stock <= 0;
                @endphp

                <article class="group rounded-2xl border border-subtle bg-[#0b0d0f] p-4 transition hover:-translate-y-1 hover:border-white/20">
                    <div class="relative flex h-36 items-center justify-center overflow-hidden rounded-xl bg-white/3">
                        @if($image)
                            <img
                                src="{{ asset('storage/' . $image) }}"
                                alt="{{ $product->name }}"
                                class="h-28 object-contain transition duration-300 group-hover:scale-105"
                            >
                        @else
                            <img src="{{ asset('images/hero.png') }}" alt="{{ $product->name }}" class="h-28 object-contain opacity-80">
                        @endif
                    </div>

                    <h3 class="text-white text-sm font-semibold leading-snug mt-4 min-h-9.5">
                        {{ $product->name }}
                    </h3>

                    <p class="text-xs text-slate-400 mt-1">SKU: {{ $product->sku ?: 'N/A' }}</p>

                    <div class="mt-3 flex items-center gap-2">
                        <p class="text-white font-semibold text-sm">Rs {{ number_format((float) $product->selling_price, 2) }}</p>
                        @if(!empty($product->compare_price) && (float) $product->compare_price > (float) $product->selling_price)
                            <p class="text-xs text-slate-500 line-through">Rs {{ number_format((float) $product->compare_price, 2) }}</p>
                        @endif
                    </div>

                    <button
                        type="button"
                        wire:click="addToCart({{ $product->id }})"
                        wire:loading.attr="disabled"
                        wire:target="addToCart({{ $product->id }})"
                        @disabled($isOutOfStock)
                        class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-md px-3 py-2 text-xs font-semibold transition {{ $isOutOfStock ? 'bg-slate-700 text-slate-300 cursor-not-allowed' : 'bg-blue-600 text-white hover:bg-blue-500' }}"
                    >
                        <i class="ri-shopping-cart-2-line"></i>
                        <span wire:loading.remove wire:target="addToCart({{ $product->id }})">
                            {{ $isOutOfStock ? 'Out of Stock' : 'Add to Cart' }}
                        </span>
                        <span wire:loading wire:target="addToCart({{ $product->id }})">Adding...</span>
                    </button>
                </article>
            @endforeach
        </div>
    @endif
</div>