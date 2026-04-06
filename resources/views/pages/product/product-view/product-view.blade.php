<div x-data="productGallery()" class="max-w-7xl mx-auto px-4 sm:px-6 py-12">

    <!-- GRID -->
    <div class="grid lg:grid-cols-2 gap-10 items-start">

        <!-- LEFT: IMAGE GALLERY -->
        <div>

            <!-- Main Image -->
            <div class="relative rounded-2xl border border-subtle bg-[#0b0d0f] p-6 flex items-center justify-center h-[350px] sm:h-[450px]">

                <!-- subtle glow -->
                <div class="absolute inset-0 opacity-10 blur-3xl"
                     style="background: radial-gradient(circle, #ffffff, transparent 70%);"></div>

                <img :src="activeImage"
                     class="max-h-full object-contain relative z-10 transition duration-300">
            </div>

            <!-- Thumbnails -->
            <div class="flex gap-3 mt-4 overflow-x-auto">

                <template x-for="(img, index) in images" :key="index">
                    <button @click="activeImage = img"
                        class="w-16 h-16 rounded-lg border border-subtle flex items-center justify-center p-2 transition"
                        :class="activeImage === img ? 'border-white' : 'hover:border-white/20'">

                        <img :src="img" class="max-h-full object-contain">
                    </button>
                </template>

            </div>

        </div>

        <!-- RIGHT: PRODUCT INFO -->
        <div class="space-y-6">

            <!-- Title -->
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-white mb-2">
                    Premium Glass Hookah
                </h1>

                <p class="text-muted text-sm">
                    Smooth airflow, modern design & premium build quality.
                </p>
            </div>

            <!-- Price -->
            <div class="flex items-center gap-4">
                <p class="text-2xl font-semibold text-white">₹2999</p>
                <p class="text-sm text-muted line-through">₹3999</p>

                <span class="text-xs px-2 py-1 rounded-full bg-white/10 text-white">
                    25% OFF
                </span>
            </div>

            <!-- Quantity -->
            <div class="flex items-center gap-4">

                <div class="flex items-center border border-subtle rounded-full overflow-hidden">

                    <button @click="qty = Math.max(1, qty - 1)" class="px-4 py-2">-</button>
                    <span class="px-4 text-sm" x-text="qty"></span>
                    <button @click="qty++" class="px-4 py-2">+</button>

                </div>

                <span class="text-sm text-muted">In stock</span>

            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">

                <button class="flex-1 py-3 rounded-full bg-white text-black text-sm font-semibold hover:opacity-90 transition">
                    Add to Cart
                </button>

                <button class="flex-1 py-3 rounded-full border border-subtle text-sm hover:border-white transition">
                    Buy Now
                </button>

            </div>

            <!-- FEATURES (IMPROVED LOOK) -->
            <div class="grid grid-cols-2 gap-3 mt-6">

                <div class="rounded-xl border border-subtle bg-white/5 p-4 text-center text-xs text-muted">
                    🚚 Fast Delivery
                </div>

                <div class="rounded-xl border border-subtle bg-white/5 p-4 text-center text-xs text-muted">
                    🔒 Secure Payment
                </div>

                <div class="rounded-xl border border-subtle bg-white/5 p-4 text-center text-xs text-muted">
                    💎 Premium Quality
                </div>

                <div class="rounded-xl border border-subtle bg-white/5 p-4 text-center text-xs text-muted">
                    🔁 Easy Returns
                </div>

            </div>

        </div>

    </div>

    <!-- DESCRIPTION (UPGRADED) -->
    <div class="mt-16 max-w-4xl">

        <h2 class="text-xl font-semibold text-white mb-4">
            Product Details
        </h2>

        <div class="space-y-3 text-sm text-muted leading-relaxed">

            <p>
                This premium hookah is crafted for smooth airflow and long-lasting performance.
                Designed with high-quality materials, it delivers a superior smoking experience.
            </p>

            <ul class="list-disc pl-5 space-y-1">
                <li>High-quality glass build</li>
                <li>Modern premium design</li>
                <li>Easy cleaning system</li>
                <li>Perfect for personal & group use</li>
            </ul>

        </div>

    </div>

    <!-- RELATED PRODUCTS (NEW - MAKES PAGE ATTRACTIVE) -->
    <div class="mt-20">

        <h2 class="text-xl font-semibold text-white mb-6">
            Related Products
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">

            <!-- CARD -->
            <div class="rounded-xl border border-subtle bg-[#0b0d0f] p-4 text-center">
                <img src="{{ asset('hookah.png') }}" class="h-24 mx-auto mb-3">
                <p class="text-white text-sm">Mini Hookah</p>
                <p class="text-muted text-xs">₹1999</p>
            </div>

            <div class="rounded-xl border border-subtle bg-[#0b0d0f] p-4 text-center">
                <img src="{{ asset('hookah.png') }}" class="h-24 mx-auto mb-3">
                <p class="text-white text-sm">Luxury Hookah</p>
                <p class="text-muted text-xs">₹4999</p>
            </div>

            <div class="rounded-xl border border-subtle bg-[#0b0d0f] p-4 text-center">
                <img src="{{ asset('hookah.png') }}" class="h-24 mx-auto mb-3">
                <p class="text-white text-sm">Classic Hookah</p>
                <p class="text-muted text-xs">₹2499</p>
            </div>

            <div class="rounded-xl border border-subtle bg-[#0b0d0f] p-4 text-center">
                <img src="{{ asset('hookah.png') }}" class="h-24 mx-auto mb-3">
                <p class="text-white text-sm">Glass Hookah</p>
                <p class="text-muted text-xs">₹3499</p>
            </div>

        </div>

    </div>
    <script>
function productGallery() {
    return {
        qty: 1,
        images: [
            "{{ asset('hookah.png') }}",
            "{{ asset('hookah.png') }}",
            "{{ asset('hookah.png') }}",
            "{{ asset('hookah.png') }}"
        ],
        activeImage: "{{ asset('hookah.png') }}"
    }
}
</script>

</div>