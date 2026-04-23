<div x-data="productPage(@js($galleryImages), {{ max(1, (int) $product->stock) }}, @js($this->isOutOfStock()))" class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12 overflow-x-hidden">

    <!-- Breadcrumb -->
    <nav class="text-xs text-muted mb-6 flex flex-wrap items-center gap-2">
        <a href="{{ route('home') }}" wire:navigate class="hover:text-white transition">Home</a>
        <i class="ri-arrow-right-s-line text-base"></i>
        <span class="text-white/70 break-words">{{ $product->name }}</span>
    </nav>

    @php
        $whatsappNumber = preg_replace('/[^0-9]/', '', (string) app_setting('whatsapp_number', ''));
        $whatsappMessage = rawurlencode("Hi, I want to buy this product: {$product->name}. Please help me with the order.");
        $whatsappUrl = $whatsappNumber ? "https://wa.me/{$whatsappNumber}?text={$whatsappMessage}" : null;
    @endphp

    <!-- GRID -->
    <div class="grid lg:grid-cols-[minmax(0,1.35fr)_minmax(0,0.9fr)] gap-6 sm:gap-10 items-start">

        <div class="space-y-4 min-w-0">

            <!-- Main Image -->
            <div class="relative rounded-3xl border border-subtle bg-[#0b0d0f] overflow-hidden lg:sticky lg:top-24" x-on:mouseenter="stopAuto()" x-on:mouseleave="startAuto()">
                <div class="absolute -top-24 -left-24 h-56 w-56 rounded-full bg-blue-500/10 blur-[90px]"></div>
                <div class="absolute -bottom-24 -right-24 h-56 w-56 rounded-full bg-purple-500/10 blur-[90px]"></div>
                <div class="absolute inset-0 opacity-20" style="background: radial-gradient(circle at top, rgba(0,198,255,0.18), transparent 55%);"></div>

                <div class="relative h-[280px] sm:h-[460px] flex items-center justify-center p-4 sm:p-6">
                    <template x-for="(img, index) in images" :key="img.src">
                        <div
                            x-cloak
                            x-show="activeIndex === index"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute inset-0 flex items-center justify-center p-6">
                            <img
                                :src="img.src"
                                :alt="img.alt"
                                class="max-h-full object-contain drop-shadow-[0_18px_45px_rgba(0,0,0,0.55)]">
                        </div>
                    </template>
                </div>
            </div>

            <!-- Thumbnails -->
            <div class="flex gap-3 overflow-x-auto pb-1">
                <template x-for="(img, index) in images" :key="img.src + index">
                    <button
                        type="button"
                        class="shrink-0 w-16 h-16 rounded-xl border bg-[#0b0d0f] border-subtle flex items-center justify-center p-2 transition"
                        :class="activeIndex === index ? 'border-white/25 ring-2 ring-indigo-400/20' : 'hover:border-white/15'"
                        x-on:click="setActive(index); restartAuto()"
                        :aria-label="`View image ${index + 1}`">
                        <img :src="img.src" :alt="img.alt" class="max-h-full object-contain opacity-90">
                    </button>
                </template>
            </div>

            <!-- Pricing (Mobile) -->
            <div class="lg:hidden rounded-3xl border border-subtle bg-[#0b0d0f] p-4 sm:p-6">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.22em] text-muted">Price</p>
                        <p class="text-3xl font-semibold text-white mt-2">&#8377;{{ $this->price($product->selling_price) }}</p>
                        @if($product->compare_price && $product->compare_price > $product->selling_price)
                        <p class="text-sm text-muted line-through mt-1">&#8377;{{ $this->price($product->compare_price) }}</p>
                        @endif
                    </div>
                    @if($this->discountPercent())
                    <span class="text-xs px-3 py-1.5 rounded-full border border-white/10 bg-white/5 text-indigo-300">
                        Save {{ $this->discountPercent() }}%
                    </span>
                    @endif
                </div>

                <div class="mt-6 flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="inline-flex items-center border border-subtle rounded-full overflow-hidden bg-white/2">
                        <button type="button" class="px-4 py-2 text-white/80 hover:bg-white/5 transition" x-on:click="qty = Math.max(1, qty - 1)">-</button>
                        <span class="px-4 text-sm text-white" x-text="qty"></span>
                        <button type="button" class="px-4 py-2 text-white/80 hover:bg-white/5 transition" x-on:click="qty = Math.min(maxQty, qty + 1)">+</button>
                    </div>

                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <button type="button" class="flex-1 py-3 rounded-full border border-subtle text-sm text-white hover:border-white/20 hover:bg-white/5 transition disabled:opacity-50 disabled:cursor-not-allowed" x-bind:disabled="isOutOfStock" x-on:click="$wire.addToCart(qty)" wire:loading.attr="disabled" wire:target="addToCart,buyNow">
                            Add to Cart
                        </button>
                        <button type="button" class="flex-1 py-3 rounded-full bg-white text-sm font-bold text-black transition hover:opacity-90 hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed" x-bind:disabled="isOutOfStock" x-on:click="$wire.buyNow(qty)" wire:loading.attr="disabled" wire:target="addToCart,buyNow">
                            Buy Now
                        </button>
                    </div>
                </div>

                <p class="mt-4 text-xs text-amber-300 inline-flex items-center gap-2">
                    <i class="ri-fire-line"></i> {{ $this->soldText() }}
                </p>

                @if($this->fomoText())
                <p class="mt-2 text-xs text-rose-300 inline-flex items-center gap-2">
                    <i class="ri-timer-flash-line"></i> {{ $this->fomoText() }}
                </p>
                @endif

                <div class="mt-6 grid sm:grid-cols-2 gap-3">
                    <div class="rounded-2xl border border-white/10 bg-white/3 px-4 py-3 text-xs text-muted flex items-center gap-2">
                        <i class="ri-map-pin-2-line text-indigo-300 text-base"></i>
                        Ships to India
                    </div>
                    @if ($whatsappUrl)
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="rounded-2xl border border-white/10 bg-white/3 px-4 py-3 text-xs text-muted flex items-center gap-2 transition hover:border-white/20 hover:bg-white/5">
                            <i class="ri-whatsapp-line text-indigo-300 text-base"></i>
                            WhatsApp support
                        </a>
                    @else
                        <div class="rounded-2xl border border-white/10 bg-white/3 px-4 py-3 text-xs text-muted flex items-center gap-2 opacity-70">
                            <i class="ri-whatsapp-line text-indigo-300 text-base"></i>
                            WhatsApp support unavailable
                        </div>
                    @endif
                </div>
            </div>

            <!-- Trust chips -->
            <div class="hidden lg:grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="rounded-2xl border border-subtle bg-white/3 px-4 py-3 text-xs text-muted flex items-center gap-2">
                    <i class="ri-truck-line text-indigo-300 text-base"></i> Fast delivery
                </div>
                <div class="rounded-2xl border border-subtle bg-white/3 px-4 py-3 text-xs text-muted flex items-center gap-2">
                    <i class="ri-secure-payment-line text-indigo-300 text-base"></i> Secure payment
                </div>
                <div class="rounded-2xl border border-subtle bg-white/3 px-4 py-3 text-xs text-muted flex items-center gap-2">
                    <i class="ri-shield-check-line text-indigo-300 text-base"></i> 100% original
                </div>
                <div class="rounded-2xl border border-subtle bg-white/3 px-4 py-3 text-xs text-muted flex items-center gap-2">
                    <i class="ri-refresh-line text-indigo-300 text-base"></i> Easy returns
                </div>
            </div>

            <!-- Details -->
            <div class="hidden lg:block rounded-3xl border border-subtle bg-[#0b0d0f] p-5 sm:p-7 lg:p-8">
                <div class="space-y-6 text-sm text-muted leading-relaxed">
                    <section>
                        <h3 class="text-xs uppercase tracking-[0.22em] text-white/80 mb-3">Features</h3>
                        <div class="rounded-2xl border border-white/10 bg-white/3 p-4 overflow-x-auto overflow-y-hidden no-scrollbar [&_p]:mb-3 [&_p:last-child]:mb-0 [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:space-y-1 [&_ol]:list-decimal [&_ol]:pl-5 [&_ol]:space-y-1 [&_li]:mb-1 [&_a]:text-cyan-300 [&_a]:underline [&_strong]:text-white [&_em]:text-white/90 [&_h1]:text-white [&_h1]:text-xl [&_h1]:font-semibold [&_h1]:mb-3 [&_h2]:text-white [&_h2]:text-lg [&_h2]:font-semibold [&_h2]:mb-2 [&_h3]:text-white [&_h3]:font-semibold [&_h3]:mb-2 [&_blockquote]:border-l-2 [&_blockquote]:border-white/20 [&_blockquote]:pl-4 [&_blockquote]:italic [&_table]:w-full [&_table]:min-w-180 [&_table]:border-collapse [&_table]:text-sm [&_thead]:bg-white/5 [&_th]:text-left [&_th]:text-white [&_th]:font-semibold [&_th]:px-3 [&_th]:py-2 [&_th]:border [&_th]:border-white/10 [&_td]:px-3 [&_td]:py-2 [&_td]:border [&_td]:border-white/10 [&_img]:max-w-full [&_img]:h-auto [&_hr]:border-white/10 [&_code]:bg-white/10 [&_code]:px-1.5 [&_code]:py-0.5 [&_code]:rounded">
                            @if($product->short_description)
                            {!! nl2br(e($product->short_description)) !!}
                            @else
                            <p>Product highlights will be updated soon.</p>
                            @endif
                        </div>
                    </section>

                    <section>
                        <h3 class="text-xs uppercase tracking-[0.22em] text-white/80 mb-3">Specification</h3>
                        <div class="rounded-2xl border border-white/10 bg-white/3 p-4 overflow-x-auto overflow-y-hidden no-scrollbar [&_p]:mb-3 [&_p:last-child]:mb-0 [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:space-y-1 [&_ol]:list-decimal [&_ol]:pl-5 [&_ol]:space-y-1 [&_li]:mb-1 [&_a]:text-cyan-300 [&_a]:underline [&_strong]:text-white [&_em]:text-white/90 [&_h1]:text-white [&_h1]:text-xl [&_h1]:font-semibold [&_h1]:mb-3 [&_h2]:text-white [&_h2]:text-lg [&_h2]:font-semibold [&_h2]:mb-2 [&_h3]:text-white [&_h3]:font-semibold [&_h3]:mb-2 [&_blockquote]:border-l-2 [&_blockquote]:border-white/20 [&_blockquote]:pl-4 [&_blockquote]:italic [&_table]:w-full [&_table]:min-w-180 [&_table]:border-collapse [&_table]:text-sm [&_thead]:bg-white/5 [&_th]:text-left [&_th]:text-white [&_th]:font-semibold [&_th]:px-3 [&_th]:py-2 [&_th]:border [&_th]:border-white/10 [&_td]:px-3 [&_td]:py-2 [&_td]:border [&_td]:border-white/10 [&_img]:max-w-full [&_img]:h-auto [&_hr]:border-white/10 [&_code]:bg-white/10 [&_code]:px-1.5 [&_code]:py-0.5 [&_code]:rounded">
                            {!! $product->feature_and_specifications ?: '<p>Detailed specifications are not available yet.</p>' !!}
                        </div>
                    </section>
                </div>
            </div>

        </div>

        <!-- RIGHT: INFO -->
        <div class="space-y-8 min-w-0">

            <!-- Title -->
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-subtle bg-white/5 text-xs text-muted mb-5 backdrop-blur">
                    <span class="w-2 h-2 rounded-full bg-linear-to-r from-cyan-400 to-pink-500 animate-pulse"></span>
                    {{ $product->category?->title ?? 'Premium Product' }}
                </div>

                <h1 class="text-2xl sm:text-4xl font-semibold text-white leading-tight break-words">
                    {{ $product->name }}
                </h1>

                <p class="text-muted text-sm mt-3 leading-6 max-w-xl">
                    {{ $this->shortText($product->short_description, 180) }}
                </p>

                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <span class="sm:ml-auto text-xs text-amber-300 inline-flex items-center gap-2">
                        <i class="ri-fire-line text-base"></i> {{ $this->soldText() }}
                    </span>

                    @if($this->showHurryFomo())
                    <span class="text-xs text-rose-300 inline-flex items-center gap-2">
                        <i class="ri-alarm-warning-line text-base"></i> Hurry, only {{ $product->stock }} left
                    </span>
                    @elseif(! $this->isOutOfStock())
                    <span class="text-xs text-emerald-300 inline-flex items-center gap-2">
                        <i class="ri-checkbox-circle-line text-base"></i> {{ $product->stock }} available
                    </span>
                    @endif
                </div>

                <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <div class="rounded-xl border border-white/10 bg-white/3 px-3 py-2">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-muted">SKU</p>
                        <p class="text-xs text-white mt-1 truncate">{{ $product->sku ?: 'N/A' }}</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-white/3 px-3 py-2">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-muted">Category</p>
                        <p class="text-xs text-white mt-1 truncate">{{ $product->category?->title ?: 'General' }}</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-white/3 px-3 py-2">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-muted">In Stock</p>
                        <p class="text-xs text-white mt-1">{{ max(0, (int) $product->stock) }}</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-white/3 px-3 py-2">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-muted">Sold</p>
                        <p class="text-xs text-white mt-1">{{ (int) ($product->hurry_stock ?? 0) > 0 ? (int) $product->hurry_stock . '+' : 'Some' }}</p>
                    </div>
                </div>
            </div>

            <!-- Details (Mobile) -->
            <div class="lg:hidden rounded-3xl border border-subtle bg-[#0b0d0f] p-5">
                <div class="space-y-6 text-sm text-muted leading-relaxed">
                    <section>
                        <h3 class="text-xs uppercase tracking-[0.22em] text-white/80 mb-3">Features</h3>
                        <div class="rounded-2xl border border-white/10 bg-white/3 p-4 overflow-x-auto overflow-y-hidden no-scrollbar [&_p]:mb-3 [&_p:last-child]:mb-0 [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:space-y-1 [&_ol]:list-decimal [&_ol]:pl-5 [&_ol]:space-y-1 [&_li]:mb-1 [&_a]:text-cyan-300 [&_a]:underline [&_strong]:text-white [&_em]:text-white/90 [&_h1]:text-white [&_h1]:text-xl [&_h1]:font-semibold [&_h1]:mb-3 [&_h2]:text-white [&_h2]:text-lg [&_h2]:font-semibold [&_h2]:mb-2 [&_h3]:text-white [&_h3]:font-semibold [&_h3]:mb-2 [&_blockquote]:border-l-2 [&_blockquote]:border-white/20 [&_blockquote]:pl-4 [&_blockquote]:italic [&_table]:w-full [&_table]:min-w-180 [&_table]:border-collapse [&_table]:text-sm [&_thead]:bg-white/5 [&_th]:text-left [&_th]:text-white [&_th]:font-semibold [&_th]:px-3 [&_th]:py-2 [&_th]:border [&_th]:border-white/10 [&_td]:px-3 [&_td]:py-2 [&_td]:border [&_td]:border-white/10 [&_img]:max-w-full [&_img]:h-auto [&_hr]:border-white/10 [&_code]:bg-white/10 [&_code]:px-1.5 [&_code]:py-0.5 [&_code]:rounded">
                            @if($product->short_description)
                            {!! nl2br(e($product->short_description)) !!}
                            @else
                            <p>Product highlights will be updated soon.</p>
                            @endif
                        </div>
                    </section>

                    <section>
                        <h3 class="text-xs uppercase tracking-[0.22em] text-white/80 mb-3">Specification</h3>
                        <div class="rounded-2xl border border-white/10 bg-white/3 p-4 overflow-x-auto overflow-y-hidden no-scrollbar [&_p]:mb-3 [&_p:last-child]:mb-0 [&_ul]:list-disc [&_ul]:pl-5 [&_ul]:space-y-1 [&_ol]:list-decimal [&_ol]:pl-5 [&_ol]:space-y-1 [&_li]:mb-1 [&_a]:text-cyan-300 [&_a]:underline [&_strong]:text-white [&_em]:text-white/90 [&_h1]:text-white [&_h1]:text-xl [&_h1]:font-semibold [&_h1]:mb-3 [&_h2]:text-white [&_h2]:text-lg [&_h2]:font-semibold [&_h2]:mb-2 [&_h3]:text-white [&_h3]:font-semibold [&_h3]:mb-2 [&_blockquote]:border-l-2 [&_blockquote]:border-white/20 [&_blockquote]:pl-4 [&_blockquote]:italic [&_table]:w-full [&_table]:min-w-180 [&_table]:border-collapse [&_table]:text-sm [&_thead]:bg-white/5 [&_th]:text-left [&_th]:text-white [&_th]:font-semibold [&_th]:px-3 [&_th]:py-2 [&_th]:border [&_th]:border-white/10 [&_td]:px-3 [&_td]:py-2 [&_td]:border [&_td]:border-white/10 [&_img]:max-w-full [&_img]:h-auto [&_hr]:border-white/10 [&_code]:bg-white/10 [&_code]:px-1.5 [&_code]:py-0.5 [&_code]:rounded">
                            {!! $product->feature_and_specifications ?: '<p>Detailed specifications are not available yet.</p>' !!}
                        </div>
                    </section>
                </div>
            </div>

            <!-- Pricing -->
            <div class="hidden lg:block rounded-3xl border border-subtle bg-[#0b0d0f] p-4 sm:p-6">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.22em] text-muted">Price</p>
                        <p class="text-3xl font-semibold text-white mt-2">&#8377;{{ $this->price($product->selling_price) }}</p>
                        @if($product->compare_price && $product->compare_price > $product->selling_price)
                        <p class="text-sm text-muted line-through mt-1">&#8377;{{ $this->price($product->compare_price) }}</p>
                        @endif
                    </div>
                    @if($this->discountPercent())
                    <span class="text-xs px-3 py-1.5 rounded-full border border-white/10 bg-white/5 text-indigo-300">
                        Save {{ $this->discountPercent() }}%
                    </span>
                    @endif
                </div>

                <div class="mt-6 flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="inline-flex items-center border border-subtle rounded-full overflow-hidden bg-white/2">
                        <button type="button" class="px-4 py-2 text-white/80 hover:bg-white/5 transition" x-on:click="qty = Math.max(1, qty - 1)">-</button>
                        <span class="px-4 text-sm text-white" x-text="qty"></span>
                        <button type="button" class="px-4 py-2 text-white/80 hover:bg-white/5 transition" x-on:click="qty = Math.min(maxQty, qty + 1)">+</button>
                    </div>

                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <button type="button" class="flex-1 py-3 rounded-full border border-subtle text-sm text-white hover:border-white/20 hover:bg-white/5 transition disabled:opacity-50 disabled:cursor-not-allowed" x-bind:disabled="isOutOfStock" x-on:click="$wire.addToCart(qty)" wire:loading.attr="disabled" wire:target="addToCart,buyNow">
                            Add to Cart
                        </button>
                        <button type="button" class="flex-1 py-3 rounded-full bg-white text-sm font-bold text-black transition hover:opacity-90 hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed" x-bind:disabled="isOutOfStock" x-on:click="$wire.buyNow(qty)" wire:loading.attr="disabled" wire:target="addToCart,buyNow">
                            Buy Now
                        </button>
                    </div>
                </div>

                <p class="mt-4 text-xs text-amber-300 inline-flex items-center gap-2">
                    <i class="ri-fire-line"></i> {{ $this->soldText() }}
                </p>

                @if($this->fomoText())
                <p class="mt-2 text-xs text-rose-300 inline-flex items-center gap-2">
                    <i class="ri-timer-flash-line"></i> {{ $this->fomoText() }}
                </p>
                @endif

                <div class="mt-6 grid sm:grid-cols-2 gap-3">
                    <div class="rounded-2xl border border-white/10 bg-white/3 px-4 py-3 text-xs text-muted flex items-center gap-2">
                        <i class="ri-map-pin-2-line text-indigo-300 text-base"></i>
                        Ships to India
                    </div>
                    @if ($whatsappUrl)
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="rounded-2xl border border-white/10 bg-white/3 px-4 py-3 text-xs text-muted flex items-center gap-2 transition hover:border-white/20 hover:bg-white/5">
                            <i class="ri-whatsapp-line text-indigo-300 text-base"></i>
                            WhatsApp support
                        </a>
                    @else
                        <div class="rounded-2xl border border-white/10 bg-white/3 px-4 py-3 text-xs text-muted flex items-center gap-2 opacity-70">
                            <i class="ri-whatsapp-line text-indigo-300 text-base"></i>
                            WhatsApp support unavailable
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

    <!-- RELATED PRODUCTS -->
    <section class="mt-14 sm:mt-20">
        <div class="flex items-end justify-between gap-6 mb-8">
            <div>
                <h2 class="text-2xl font-semibold text-white">Related Products</h2>
                <p class="text-muted text-sm mt-2">More picks you might like</p>
            </div>
            <a href="{{ route('home') }}#collection" wire:navigate class="text-sm text-muted hover:text-white transition hidden sm:inline-flex items-center gap-2">
                View all <i class="ri-arrow-right-line"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            @forelse($relatedProducts as $relatedProduct)
            <a href="{{ route('product', $relatedProduct->slug) }}" wire:navigate class="group rounded-2xl border border-subtle bg-[#0b0d0f] p-4 transition hover:-translate-y-1 hover:border-white/20 hover:shadow-2xl hover:shadow-black/30 block">
                <div class="relative flex h-32 items-center justify-center overflow-hidden rounded-xl bg-white/3">
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition" style="background: radial-gradient(circle at center, rgba(0,198,255,0.12), transparent 60%);"></div>
                    <img src="{{ $this->productImageUrl($relatedProduct) }}" class="relative h-24 object-contain transition duration-300 group-hover:scale-105" alt="{{ $relatedProduct->name }}">
                </div>

                <div class="mt-4 flex items-center justify-between gap-2">
                    <span class="rounded-full border border-white/10 bg-white/5 px-2 py-1 text-[10px] uppercase tracking-wider text-muted">
                        {{ $relatedProduct->category?->title ?? 'Tobac-Go' }}
                    </span>
                    <i class="ri-heart-line text-white/35 group-hover:text-indigo-300 transition"></i>
                </div>

                <h3 class="text-white text-sm font-semibold leading-snug mt-2 min-h-9.5 line-clamp-2">{{ $relatedProduct->name }}</h3>
                <p class="text-muted text-xs mt-1 line-clamp-1">{{ $this->shortText($relatedProduct->short_description, 38) }}</p>

                <div class="mt-4 flex items-center justify-between gap-3">
                    <div>
                        <p class="text-white font-semibold text-sm">&#8377;{{ $this->price($relatedProduct->selling_price) }}</p>
                        @if($relatedProduct->compare_price && $relatedProduct->compare_price > $relatedProduct->selling_price)
                        <p class="text-white/45 text-xs line-through">&#8377;{{ $this->price($relatedProduct->compare_price) }}</p>
                        @endif
                    </div>
                    <span class="h-9 w-9 rounded-full border border-subtle text-white/70 transition group-hover:bg-white/5 group-hover:border-white/20 inline-flex items-center justify-center" aria-label="View {{ $relatedProduct->name }}">
                        <i class="ri-add-line"></i>
                    </span>
                </div>
            </a>
            @empty
            <div class="col-span-full rounded-2xl border border-dashed border-white/15 bg-white/2 px-6 py-10 text-center text-white/70">
                More products will appear here soon.
            </div>
            @endforelse
        </div>
    </section>

    <style>
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>

    <script>
        function productPage(images, maxQty, isOutOfStock) {
            return {
                qty: 1,
                activeIndex: 0,
                autoTimer: null,
                images: images,
                maxQty: maxQty,
                isOutOfStock: isOutOfStock,
                init() {
                    this.startAuto();
                },
                setActive(index) {
                    this.activeIndex = index;
                },
                prev() {
                    this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
                },
                next() {
                    this.activeIndex = (this.activeIndex + 1) % this.images.length;
                },
                startAuto() {
                    if (this.images.length <= 1) return;
                    if (this.autoTimer) return;
                    this.autoTimer = setInterval(() => this.next(), 3500);
                },
                stopAuto() {
                    if (!this.autoTimer) return;
                    clearInterval(this.autoTimer);
                    this.autoTimer = null;
                },
                restartAuto() {
                    this.stopAuto();
                    this.startAuto();
                },
            }
        }
    </script>

</div>
