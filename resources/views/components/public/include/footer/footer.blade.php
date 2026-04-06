<footer class="mt-20 border-t border-subtle bg-[#050607]">

    <!-- TOP -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">

        <!-- BRAND -->
        <div>
            <img src="{{ asset('logo.webp') }}" class="h-11 mb-4">

            <p class="text-sm text-muted leading-relaxed max-w-xs">
                Premium hookah store delivering quality products and smooth experiences.
            </p>

            <!-- Social -->
            <div class="flex gap-3 mt-5">
                <a href="#" class="w-9 h-9 flex items-center justify-center rounded-full border border-subtle bg-white/5 hover:border-white/20 transition">
                    <i class="ri-instagram-line text-sm"></i>
                </a>
                <a href="#" class="w-9 h-9 flex items-center justify-center rounded-full border border-subtle bg-white/5 hover:border-white/20 transition">
                    <i class="ri-facebook-line text-sm"></i>
                </a>
                <a href="#" class="w-9 h-9 flex items-center justify-center rounded-full border border-subtle bg-white/5 hover:border-white/20 transition">
                    <i class="ri-youtube-line text-sm"></i>
                </a>
            </div>
        </div>

        <!-- SHOP -->
        <div>
            <h3 class="text-white text-sm font-semibold mb-4 uppercase tracking-wide">
                Shop
            </h3>

            <ul class="space-y-2 text-sm text-muted">
                <li><a href="#" class="hover:text-white transition">Hookah</a></li>
                <li><a href="#" class="hover:text-white transition">Bongs</a></li>
                <li><a href="#" class="hover:text-white transition">Accessories</a></li>
                <li><a href="#" class="hover:text-white transition">Combos</a></li>
            </ul>
        </div>

        <!-- SUPPORT -->
        <div>
            <h3 class="text-white text-sm font-semibold mb-4 uppercase tracking-wide">
                Support
            </h3>

            <ul class="space-y-2 text-sm text-muted">
                <li><a href="#" class="hover:text-white transition">Contact</a></li>
                <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                <li><a href="#" class="hover:text-white transition">Shipping</a></li>
                <li><a href="#" class="hover:text-white transition">Returns</a></li>
            </ul>
        </div>

        <!-- NEWSLETTER -->
        <div>
            <h3 class="text-white text-sm font-semibold mb-4 uppercase tracking-wide">
                Newsletter
            </h3>

            <p class="text-sm text-muted mb-4">
                Get updates on new arrivals and offers.
            </p>

            <div class="flex gap-2">

                <input
                    type="email"
                    placeholder="Your email"
                    class="flex-1 rounded-full bg-white/5 border border-subtle px-4 py-2 text-sm text-white placeholder-white/40 focus:outline-none focus:border-white/20 transition"
                >

                <button class="px-4 py-2 rounded-full border border-subtle text-sm hover:border-white transition">
                    Join
                </button>

            </div>

        </div>

    </div>

    <!-- BOTTOM -->
    <div class="border-t border-subtle">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-muted">

            <p>© {{ date('Y') }} Tobac-Go</p>

            <div class="flex gap-5">
                <a href="#" class="hover:text-white transition">Privacy</a>
                <a href="#" class="hover:text-white transition">Terms</a>
                <a href="#" class="hover:text-white transition">Refund</a>
            </div>

        </div>
    </div>

</footer>