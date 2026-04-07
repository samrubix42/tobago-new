<div x-data="productPage()" class="max-w-7xl mx-auto px-4 sm:px-6 py-12">

    <!-- Breadcrumb -->
    <nav class="text-xs text-muted mb-6 flex items-center gap-2">
        <a href="{{ route('home') }}" wire:navigate class="hover:text-white transition">Home</a>
        <i class="ri-arrow-right-s-line text-base"></i>
        <span class="text-white/70">Product</span>
    </nav>

    <!-- GRID -->
    <div class="grid lg:grid-cols-2 gap-10 items-start">

        <!-- LEFT: GALLERY -->
        <div class="space-y-4 lg:sticky lg:top-24">

            <!-- Main Image -->
            <div class="relative rounded-3xl border border-subtle bg-[#0b0d0f] overflow-hidden" x-on:mouseenter="stopAuto()" x-on:mouseleave="startAuto()">
                <div class="absolute -top-24 -left-24 h-56 w-56 rounded-full bg-blue-500/10 blur-[90px]"></div>
                <div class="absolute -bottom-24 -right-24 h-56 w-56 rounded-full bg-purple-500/10 blur-[90px]"></div>
                <div class="absolute inset-0 opacity-20" style="background: radial-gradient(circle at top, rgba(0,198,255,0.18), transparent 55%);"></div>

                <div class="relative h-[330px] sm:h-[460px] flex items-center justify-center p-6">
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
                            class="absolute inset-0 flex items-center justify-center p-6"
                        >
                            <img
                                :src="img.src"
                                :alt="img.alt"
                                class="max-h-full object-contain drop-shadow-[0_18px_45px_rgba(0,0,0,0.55)]"
                            >
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
                        :aria-label="`View image ${index + 1}`"
                    >
                        <img :src="img.src" :alt="img.alt" class="max-h-full object-contain opacity-90">
                    </button>
                </template>
            </div>

            <!-- Trust chips -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="rounded-2xl border border-subtle bg-white/[0.03] px-4 py-3 text-xs text-muted flex items-center gap-2">
                    <i class="ri-truck-line text-indigo-300 text-base"></i> Fast delivery
                </div>
                <div class="rounded-2xl border border-subtle bg-white/[0.03] px-4 py-3 text-xs text-muted flex items-center gap-2">
                    <i class="ri-secure-payment-line text-indigo-300 text-base"></i> Secure payment
                </div>
                <div class="rounded-2xl border border-subtle bg-white/[0.03] px-4 py-3 text-xs text-muted flex items-center gap-2">
                    <i class="ri-shield-check-line text-indigo-300 text-base"></i> 100% original
                </div>
                <div class="rounded-2xl border border-subtle bg-white/[0.03] px-4 py-3 text-xs text-muted flex items-center gap-2">
                    <i class="ri-refresh-line text-indigo-300 text-base"></i> Easy returns
                </div>
            </div>

        </div>

        <!-- RIGHT: INFO -->
        <div class="space-y-8">

            <!-- Title -->
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-subtle bg-white/5 text-xs text-muted mb-5 backdrop-blur">
                    <span class="w-2 h-2 rounded-full bg-gradient-to-r from-cyan-400 to-pink-500 animate-pulse"></span>
                    Premium Hookah
                </div>

                <h1 class="text-2xl sm:text-4xl font-semibold text-white leading-tight">
                    Premium Glass Hookah
                </h1>

                <p class="text-muted text-sm mt-3 leading-6 max-w-xl">
                    Smooth airflow, modern design & premium build quality. Perfect for daily home use and gifting.
                </p>

                <div class="mt-4 flex items-center gap-3">
                    <div class="flex gap-1 text-amber-300 text-sm">
                        <i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-half-fill"></i>
                    </div>
                    <span class="text-sm text-white/80 font-semibold">4.3</span>
                    <span class="text-xs text-muted">(120 reviews)</span>
                    <span class="ml-auto text-xs text-emerald-300 inline-flex items-center gap-2">
                        <i class="ri-checkbox-circle-line text-base"></i> In stock
                    </span>
                </div>
            </div>

            <!-- Pricing -->
            <div class="rounded-3xl border border-subtle bg-[#0b0d0f] p-6">
                <div class="flex items-end justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.22em] text-muted">Price</p>
                        <p class="text-3xl font-semibold text-white mt-2">&#8377;2,999</p>
                        <p class="text-sm text-muted line-through mt-1">&#8377;3,999</p>
                    </div>
                    <span class="text-xs px-3 py-1.5 rounded-full border border-white/10 bg-white/5 text-indigo-300">
                        Save 25%
                    </span>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="inline-flex items-center border border-subtle rounded-full overflow-hidden bg-white/[0.02]">
                        <button type="button" class="px-4 py-2 text-white/80 hover:bg-white/5 transition" x-on:click="qty = Math.max(1, qty - 1)">-</button>
                        <span class="px-4 text-sm text-white" x-text="qty"></span>
                        <button type="button" class="px-4 py-2 text-white/80 hover:bg-white/5 transition" x-on:click="qty++">+</button>
                    </div>

                    <div class="flex-1 flex gap-3">
                        <button type="button" class="flex-1 py-3 rounded-full border border-subtle text-sm text-white hover:border-white/20 hover:bg-white/5 transition">
                            Add to Cart
                        </button>
                        <button type="button" class="flex-1 py-3 rounded-full bg-white text-sm font-bold text-black transition hover:opacity-90 hover:scale-[1.02]">
                            Buy Now
                        </button>
                    </div>
                </div>

                <div class="mt-6 grid sm:grid-cols-2 gap-3">
                    <div class="rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3 text-xs text-muted flex items-center gap-2">
                        <i class="ri-map-pin-2-line text-indigo-300 text-base"></i>
                        Ships to India
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/[0.03] px-4 py-3 text-xs text-muted flex items-center gap-2">
                        <i class="ri-whatsapp-line text-indigo-300 text-base"></i>
                        WhatsApp support
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="rounded-3xl border border-subtle bg-[#0b0d0f] p-6">
                <div class="flex flex-wrap gap-2">
                    <button type="button" class="px-4 py-2 rounded-full text-xs border border-subtle transition" :class="tab === 'details' ? 'bg-white/5 border-white/15 text-white' : 'text-muted hover:bg-white/5 hover:border-white/15'" x-on:click="tab = 'details'">
                        Details
                    </button>
                    <button type="button" class="px-4 py-2 rounded-full text-xs border border-subtle transition" :class="tab === 'specs' ? 'bg-white/5 border-white/15 text-white' : 'text-muted hover:bg-white/5 hover:border-white/15'" x-on:click="tab = 'specs'">
                        Specs
                    </button>
                    <button type="button" class="px-4 py-2 rounded-full text-xs border border-subtle transition" :class="tab === 'shipping' ? 'bg-white/5 border-white/15 text-white' : 'text-muted hover:bg-white/5 hover:border-white/15'" x-on:click="tab = 'shipping'">
                        Shipping & Returns
                    </button>
                </div>

                <div class="mt-6 space-y-3 text-sm text-muted leading-relaxed">
                    <div x-show="tab === 'details'" x-transition.opacity>
                        <p>
                            Crafted for smooth airflow and long-lasting performance. Built with premium materials to deliver a superior session every time.
                        </p>
                        <ul class="list-disc pl-5 space-y-1 mt-3">
                            <li>High-quality glass build</li>
                            <li>Modern premium design</li>
                            <li>Easy cleaning system</li>
                            <li>Perfect for personal & group use</li>
                        </ul>
                    </div>

                    <div x-show="tab === 'specs'" x-transition.opacity x-cloak>
                        <div class="grid sm:grid-cols-2 gap-3">
                            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                                <p class="text-xs uppercase tracking-[0.22em] text-muted">Material</p>
                                <p class="text-white mt-2">Glass + Steel</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                                <p class="text-xs uppercase tracking-[0.22em] text-muted">Height</p>
                                <p class="text-white mt-2">22 inch</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                                <p class="text-xs uppercase tracking-[0.22em] text-muted">Hose</p>
                                <p class="text-white mt-2">Single (included)</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                                <p class="text-xs uppercase tracking-[0.22em] text-muted">Use</p>
                                <p class="text-white mt-2">Home / Lounge</p>
                            </div>
                        </div>
                    </div>

                    <div x-show="tab === 'shipping'" x-transition.opacity x-cloak>
                        <p>
                            Orders are packed in strong outer boxes with protective wrap. Returns accepted within 10 days if unused and in original packaging.
                        </p>
                        <div class="mt-4 grid sm:grid-cols-2 gap-3">
                            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4 flex items-center gap-3">
                                <i class="ri-archive-2-line text-indigo-300 text-xl"></i>
                                <div>
                                    <p class="text-white text-sm font-semibold">Safe packaging</p>
                                    <p class="text-muted text-xs mt-1">Bubble wrap + foam</p>
                                </div>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4 flex items-center gap-3">
                                <i class="ri-refresh-line text-indigo-300 text-xl"></i>
                                <div>
                                    <p class="text-white text-sm font-semibold">Easy returns</p>
                                    <p class="text-muted text-xs mt-1">10-day window</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- RELATED PRODUCTS -->
    <section class="mt-20">
        <div class="flex items-end justify-between gap-6 mb-8">
            <div>
                <h2 class="text-2xl font-semibold text-white">Related Products</h2>
                <p class="text-muted text-sm mt-2">More picks you might like</p>
            </div>
            <a href="#" class="text-sm text-muted hover:text-white transition hidden sm:inline-flex items-center gap-2">
                View all <i class="ri-arrow-right-line"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5">
            @foreach([
                ['Mini Hookah', '1,999'],
                ['Luxury Hookah', '4,999'],
                ['Classic Hookah', '2,499'],
                ['Glass Hookah', '3,499'],
            ] as $p)
                <article class="group rounded-2xl border border-subtle bg-[#0b0d0f] p-4 transition hover:-translate-y-1 hover:border-white/20 hover:shadow-2xl hover:shadow-black/30">
                    <div class="relative flex h-32 items-center justify-center overflow-hidden rounded-xl bg-white/[0.03]">
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition" style="background: radial-gradient(circle at center, rgba(0,198,255,0.12), transparent 60%);"></div>
                        <img src="{{ asset('images/hero.png') }}" class="relative h-24 object-contain transition duration-300 group-hover:scale-105" alt="{{ $p[0] }}">
                    </div>

                    <div class="mt-4 flex items-center justify-between gap-2">
                        <span class="rounded-full border border-white/10 bg-white/5 px-2 py-1 text-[10px] uppercase tracking-wider text-muted">
                            Tobac-Go
                        </span>
                        <i class="ri-heart-line text-white/35 group-hover:text-indigo-300 transition"></i>
                    </div>

                    <h3 class="text-white text-sm font-semibold leading-snug mt-2 min-h-[38px]">{{ $p[0] }}</h3>
                    <p class="text-muted text-xs mt-1">Popular pick</p>

                    <div class="mt-4 flex items-center justify-between gap-3">
                        <p class="text-white font-semibold text-sm">&#8377;{{ $p[1] }}</p>
                        <button type="button" class="h-9 w-9 rounded-full border border-subtle text-white/70 transition hover:bg-white/5 hover:border-white/20" aria-label="Add {{ $p[0] }}">
                            <i class="ri-add-line"></i>
                        </button>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <script>
        function productPage() {
            return {
                qty: 1,
                tab: 'details',
                activeIndex: 0,
                autoTimer: null,
                images: [
                    { src: "{{ asset('images/hero.png') }}", alt: "Premium Hookah - image 1" },
                    { src: "{{ asset('hookah.png') }}", alt: "Premium Hookah - image 2" },
                    { src: "{{ asset('hookah.png') }}", alt: "Premium Hookah - image 3" },
                    { src: "{{ asset('hookah.png') }}", alt: "Premium Hookah - image 4" },
                ],
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
