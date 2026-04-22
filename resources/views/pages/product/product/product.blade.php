@php
    $priceMinBound = (int) floor((float) ($priceLimits->min_price ?? 0));
    $priceMaxBound = (int) ceil((float) ($priceLimits->max_price ?? 10000));
    $activeMin = (int) ($minPrice ?? $priceMinBound);
    $activeMax = (int) ($maxPrice ?? $priceMaxBound);
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-10 space-y-6 overflow-x-hidden"
    x-data="{
        mobileFilters: false,
        observer: null,
        lockScroll() {
            document.body.style.overflow = this.mobileFilters ? 'hidden' : '';
        },
        init() {
            this.$watch('mobileFilters', () => this.lockScroll());
            if (!this.$refs.sentinel) return;
            this.observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        $wire.loadMore();
                    }
                });
            }, { rootMargin: '300px 0px' });
            this.observer.observe(this.$refs.sentinel);
        },
        destroy() {
            document.body.style.overflow = '';
        }
    }"
>
    <section class="rounded-2xl border border-white/10 bg-[radial-gradient(circle_at_18%_22%,rgba(20,184,166,0.22),transparent_48%),radial-gradient(circle_at_82%_70%,rgba(37,99,235,0.2),transparent_50%),#0b0d0f] p-5 sm:p-8">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-white/60">Collection</p>
                <h1 class="text-2xl sm:text-4xl font-semibold text-white mt-1 leading-tight">Find Your Perfect Hookah Setup</h1>
                <p class="text-sm sm:text-base text-white/70 mt-3 max-w-2xl">Clean filtering, fast browsing, and category-first navigation for a premium storefront experience.</p>
            </div>
            <a href="{{ route('cart') }}" wire:navigate class="inline-flex items-center justify-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-sm text-white hover:bg-white/15 transition">
                <i class="ri-shopping-cart-2-line"></i>
                Go to Cart
            </a>
        </div>
    </section>

    <div class="space-y-4">
        <section class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h2 class="text-lg font-semibold text-white">
                        {{ $activeSubcategory?->title ?? $activeCategory?->title ?? 'All Products' }}
                    </h2>
                    <p class="text-xs text-white/60 mt-0.5">Showing {{ $products->count() }} products</p>
                </div>
                <div class="flex items-center gap-2 self-start sm:self-auto">
                    <button type="button" x-on:click="mobileFilters = true" class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3.5 py-1.5 text-xs font-medium text-white hover:bg-white/10 transition">
                        <i class="ri-equalizer-line"></i>
                        Filters
                    </button>
                    <button type="button" wire:click="clearFilters" class="inline-flex items-center gap-2 rounded-full border border-white/15 px-3.5 py-1.5 text-xs font-medium text-white/80 hover:text-white hover:border-white/25 transition">
                        Reset
                    </button>
                </div>
            </div>

            @if($products->isEmpty())
                <div class="rounded-2xl border border-dashed border-white/15 bg-white/3 p-10 text-center text-white/70">
                    No products match your current filters.
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($products as $product)
                        @php
                            $isOut = $product->is_out_of_stock || (int) $product->stock <= 0;
                        @endphp
                        <article wire:key="product-card-{{ $product->id }}" class="group rounded-2xl border border-white/10 bg-[#0b0d0f] p-3.5 transition hover:-translate-y-1 hover:border-white/20">
                            <a href="{{ route('product', $product->slug) }}" wire:navigate class="block">
                                <div class="relative flex h-32 items-center justify-center overflow-hidden rounded-xl bg-white/4">
                                    <img src="{{ $this->productImage($product) }}" alt="{{ $product->name }}" class="h-24 object-contain transition duration-300 group-hover:scale-105">
                                </div>

                                <h3 class="mt-3 text-sm font-semibold text-white line-clamp-2 min-h-10">{{ $product->name }}</h3>
                                <p class="text-[11px] text-white/50 mt-1 truncate">{{ $product->sku ?: 'SKU N/A' }}</p>
                                <p class="text-[11px] text-cyan-200/90 mt-1 truncate">{{ $product->category?->title ?: 'General Category' }}</p>
                                <div class="mt-2 flex items-center justify-between gap-2">
                                    <p class="text-sm font-semibold text-white">Rs {{ number_format((float) $product->selling_price, 2) }}</p>
                                    @if($product->compare_price && $product->compare_price > $product->selling_price)
                                        <p class="text-[11px] text-white/40 line-through">Rs {{ number_format((float) $product->compare_price, 2) }}</p>
                                    @endif
                                </div>
                            </a>

                            <button
                                type="button"
                                wire:click="addToCart({{ $product->id }})"
                                wire:loading.attr="disabled"
                                wire:target="addToCart({{ $product->id }})"
                                @disabled($isOut)
                                class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-md px-3 py-2 text-xs font-semibold transition {{ $isOut ? 'bg-slate-700 text-slate-300 cursor-not-allowed' : 'bg-blue-600 text-white hover:bg-blue-500' }}"
                            >
                                <i class="ri-shopping-cart-line"></i>
                                <span wire:loading.remove wire:target="addToCart({{ $product->id }})">{{ $isOut ? 'Out of Stock' : 'Add to Cart' }}</span>
                                <span wire:loading wire:target="addToCart({{ $product->id }})">Adding...</span>
                            </button>
                        </article>
                    @endforeach
                </div>

                <div x-ref="sentinel" class="h-2"></div>

                @if($hasMore)
                    <div class="flex justify-center pt-1">
                        <button type="button" wire:click="loadMore" wire:loading.attr="disabled" wire:target="loadMore" class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-4 py-2 text-sm text-white hover:bg-white/10 disabled:opacity-60">
                            <i class="ri-loader-4-line animate-spin" wire:loading wire:target="loadMore"></i>
                            <span wire:loading.remove wire:target="loadMore">Load More</span>
                            <span wire:loading wire:target="loadMore">Loading...</span>
                        </button>
                    </div>
                @endif
            @endif
        </section>
    </div>

    <div x-show="mobileFilters" x-cloak class="fixed inset-0 z-[70]"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <button type="button" x-on:click="mobileFilters = false" class="absolute inset-0 bg-black/60"></button>
        <div class="absolute right-0 top-0 h-full w-full sm:w-[88%] lg:w-[430px] xl:w-[460px] bg-[#0b0d0f] border-l border-white/10 overflow-hidden"
            x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full">
            <div class="flex h-full flex-col">
                <div class="shrink-0 px-4 py-4 border-b border-white/10 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-white">Filters</h3>
                    <button type="button" x-on:click="mobileFilters = false" class="h-8 w-8 rounded-full border border-white/20 text-white inline-flex items-center justify-center">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto overflow-x-hidden overscroll-contain px-4 py-4 filter-scroll">
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs text-white/60 uppercase tracking-wider">Search</label>
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Name, sku, keyword" class="mt-1 w-full rounded-md border border-white/15 bg-white/5 px-3 py-2 text-sm text-white placeholder:text-white/40 outline-none focus:border-cyan-300/50">
                        </div>

                        <div>
                            <label class="text-xs text-white/60 uppercase tracking-wider">Sort</label>
                            <select wire:model.live="sort" class="product-filter-select mt-1 w-full rounded-md border border-white/15 bg-white/5 px-3 py-2 text-sm text-white outline-none focus:border-cyan-300/50">
                                <option value="latest">Latest</option>
                                <option value="price_asc">Price: Low to High</option>
                                <option value="price_desc">Price: High to Low</option>
                                <option value="name_asc">Name: A-Z</option>
                            </select>
                        </div>

                        <div x-data="{ min: {{ $activeMin }}, max: {{ $activeMax }}, floor: {{ $priceMinBound }}, ceil: {{ max($priceMaxBound, $priceMinBound + 1) }} }" class="space-y-2">
                            <label class="text-xs text-white/60 uppercase tracking-wider">Price Range</label>
                            <div class="rounded-xl border border-white/10 bg-white/5 p-3">
                                <div class="relative h-7">
                                    <input type="range" :min="floor" :max="ceil" x-model.number="min" x-on:input="if (min > max) max = min; $wire.set('minPrice', Number(min));" class="absolute inset-0 w-full bg-transparent accent-cyan-400">
                                    <input type="range" :min="floor" :max="ceil" x-model.number="max" x-on:input="if (max < min) min = max; $wire.set('maxPrice', Number(max));" class="absolute inset-0 w-full bg-transparent accent-blue-400">
                                </div>
                                <div class="mt-2 flex items-center justify-between text-xs">
                                    <span class="text-cyan-200 font-semibold">Rs <span x-text="min"></span></span>
                                    <span class="text-white/40">to</span>
                                    <span class="text-blue-200 font-semibold">Rs <span x-text="max"></span></span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-xs text-white/60 uppercase tracking-wider">Categories</h4>
                            <div class="mt-2 space-y-1.5 pr-1">
                                <a href="{{ route('products') }}" wire:navigate class="block rounded-lg border px-3 py-2 text-sm transition {{ !$activeCategory ? 'border-cyan-300/40 bg-cyan-400/10 text-cyan-200' : 'border-white/10 text-white/70 hover:border-white/20 hover:text-white' }}">All Products</a>
                                @foreach($categories as $category)
                                    <a href="{{ route('products.category', ['category' => $category->slug]) }}" wire:navigate class="block rounded-lg border px-3 py-2 text-sm transition {{ $activeCategory?->id === $category->id && !$activeSubcategory ? 'border-cyan-300/40 bg-cyan-400/10 text-cyan-200' : 'border-white/10 text-white/70 hover:border-white/20 hover:text-white' }}">{{ $category->title }}</a>
                                    @if($activeCategory?->id === $category->id && $category->children->isNotEmpty())
                                        <div class="ml-3 mt-1 space-y-1">
                                            @foreach($category->children as $child)
                                                <a href="{{ route('products.category.subcategory', ['category' => $category->slug, 'subcategory' => $child->slug]) }}" wire:navigate class="block rounded-md border px-2.5 py-1.5 text-xs transition {{ $activeSubcategory?->id === $child->id ? 'border-blue-300/50 bg-blue-400/10 text-blue-200' : 'border-white/10 text-white/60 hover:border-white/20 hover:text-white' }}">{{ $child->title }}</a>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="shrink-0 border-t border-white/10 bg-[#0b0d0f] p-4">
                    <button wire:click="clearFilters" class="w-full rounded-md border border-white/20 bg-white/5 px-3 py-2 text-sm text-white hover:bg-white/10 transition">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .product-filter-select {
            color-scheme: dark;
        }

        .product-filter-select option {
            background-color: #0b0d0f;
            color: #e2e8f0;
        }

        .filter-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgba(148, 163, 184, 0.4) transparent;
        }

        .filter-scroll::-webkit-scrollbar {
            width: 7px;
        }

        .filter-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .filter-scroll::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.35);
            border-radius: 9999px;
        }

        .filter-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.55);
        }
    </style>
</div>
