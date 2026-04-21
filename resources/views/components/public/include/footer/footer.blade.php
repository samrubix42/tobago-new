@php
    $mobileCartCount = current_cart_items_count();
@endphp

<div class="pb-24 lg:pb-0">
    <footer class="hidden lg:block mt-20 border-t border-subtle bg-[#050607] pb-24 lg:pb-0">

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

<nav class="lg:hidden fixed bottom-0 inset-x-0 z-40 border-t border-white/10 bg-[#050607] shadow-[0_-8px_24px_rgba(0,0,0,0.45)]">
    <div class="absolute inset-x-0 top-0 h-px bg-linear-to-r from-transparent via-white/25 to-transparent"></div>
    <div class="grid grid-cols-5 h-17 px-2 py-1 pb-[calc(0.25rem+env(safe-area-inset-bottom))]">
            <a href="{{ route('home') }}" wire:navigate
                class="relative rounded-xl flex flex-col items-center justify-center gap-0.5 text-[10px] font-semibold transition {{ request()->routeIs('home') ? 'bg-white/12 text-white shadow-[0_8px_16px_rgba(0,0,0,0.24)]' : 'text-white/60 hover:text-white hover:bg-white/6' }}">
                <i class="{{ request()->routeIs('home') ? 'ri-home-5-fill' : 'ri-home-5-line' }} text-[18px]"></i>
                <span>Home</span>
            </a>

            <a href="{{ route('cart') }}" wire:navigate
                class="relative rounded-xl flex flex-col items-center justify-center gap-0.5 text-[10px] font-semibold transition {{ request()->routeIs('cart') ? 'bg-white/12 text-white shadow-[0_8px_16px_rgba(0,0,0,0.24)]' : 'text-white/60 hover:text-white hover:bg-white/6' }}">
                <i class="{{ request()->routeIs('cart') ? 'ri-shopping-cart-2-fill' : 'ri-shopping-cart-2-line' }} text-[18px]"></i>
                <span>Cart</span>
                @if($mobileCartCount > 0)
                    <span class="absolute top-0.5 right-2 min-w-4 h-4 px-1 rounded-full bg-[#ef4444] text-white text-[9px] font-bold leading-none flex items-center justify-center border border-[#111315]">
                        {{ $mobileCartCount > 99 ? '99+' : $mobileCartCount }}
                    </span>
                @endif
            </a>

            <a href="{{ auth()->check() ? route('user.profile') : route('login') }}" wire:navigate
                class="relative rounded-xl flex flex-col items-center justify-center gap-0.5 text-[10px] font-semibold transition {{ request()->routeIs('user.profile') || request()->routeIs('user.address') ? 'bg-white/12 text-white shadow-[0_8px_16px_rgba(0,0,0,0.24)]' : 'text-white/60 hover:text-white hover:bg-white/6' }}">
                <i class="{{ request()->routeIs('user.profile') || request()->routeIs('user.address') ? 'ri-user-3-fill' : 'ri-user-3-line' }} text-[18px]"></i>
                <span>Profile</span>
            </a>

            <a href="{{ auth()->check() ? route('user.orders') : route('login') }}" wire:navigate
                class="relative rounded-xl flex flex-col items-center justify-center gap-0.5 text-[10px] font-semibold transition {{ request()->routeIs('user.orders') || request()->routeIs('user.orders.*') ? 'bg-white/12 text-white shadow-[0_8px_16px_rgba(0,0,0,0.24)]' : 'text-white/60 hover:text-white hover:bg-white/6' }}">
                <i class="{{ request()->routeIs('user.orders') || request()->routeIs('user.orders.*') ? 'ri-file-list-3-fill' : 'ri-file-list-3-line' }} text-[18px]"></i>
                <span>My Order</span>
            </a>

            <a href="mailto:support@tobac-go.com"
                class="relative rounded-xl flex flex-col items-center justify-center gap-0.5 text-[10px] font-semibold text-white/60 transition hover:text-white hover:bg-white/6">
                <i class="ri-customer-service-2-line text-[18px]"></i>
                <span>Support</span>
            </a>
    </div>
</nav>
</div>