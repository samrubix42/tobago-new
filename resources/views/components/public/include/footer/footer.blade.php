@php
    $mobileCartCount = current_cart_items_count();
@endphp

<div
    class="pb-24 lg:pb-0"
    x-data="{ cartCount: {{ $mobileCartCount }} }"
    x-on:cart-updated.window="cartCount = Number($event.detail?.count ?? 0)"
>
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

        <!-- LINKS -->
        <div>
            <h3 class="text-white text-sm font-semibold mb-4 uppercase tracking-wide">
                Links
            </h3>

            <ul class="space-y-2 text-sm text-muted">
                <li><a href="{{ route('products') }}" class="hover:text-white transition">Shop</a></li>
                <li><a href="{{ route('category') }}" class="hover:text-white transition">Collection</a></li>
                <li><a href="{{ route('about') }}" class="hover:text-white transition">About Us</a></li>
                <li><a href="{{ route('blogs') }}" class="hover:text-white transition">Blogs</a></li>
                <li><a href="{{ route('location.noida') }}" class="hover:text-white transition">Location</a></li>
            </ul>
        </div>

        <!-- CATEGORIES -->
        <div>
            <h3 class="text-white text-sm font-semibold mb-4 uppercase tracking-wide">
                Categories
            </h3>

            <ul class="space-y-2 text-sm text-muted">
                <li><a href="{{ route('products.category', ['category' => 'tobac-go-hookah']) }}" class="hover:text-white transition">Tobac-Go Hookah</a></li>
                <li><a href="{{ route('products.category', ['category' => 'premium-hookah']) }}" class="hover:text-white transition">Premium Hookah</a></li>
                <li><a href="{{ route('products.category', ['category' => 'pipe-and-handle']) }}" class="hover:text-white transition">Pipe and Handle</a></li>
                <li><a href="{{ route('products.category', ['category' => 'smoking-accessories']) }}" class="hover:text-white transition">Smoking Accessories</a></li>
                <li><a href="{{ route('products.category', ['category' => 'lighters']) }}" class="hover:text-white transition">Lighters</a></li>
                <li><a href="{{ route('products.category', ['category' => 'hookah-chillum']) }}" class="hover:text-white transition">Hookah Chillum</a></li>
                <li><a href="{{ route('products.category', ['category' => 'hookah-accessories']) }}" class="hover:text-white transition">Hookah Accessories</a></li>
                <li><a href="{{ route('products.category', ['category' => 'glass-percolator-bongs']) }}" class="hover:text-white transition">Glass Percolator Bongs</a></li>
                <li><a href="{{ route('products.category', ['category' => 'combos']) }}" class="hover:text-white transition">Combos</a></li>
                <li><a href="{{ route('products.category', ['category' => 'acrylic-bongs']) }}" class="hover:text-white transition">Acrylic Bongs</a></li>
                <li><a href="{{ route('products.category', ['category' => 'ashtray']) }}" class="hover:text-white transition">Ashtray</a></li>
            </ul>
        </div>

        <!-- POLICY -->
        <div>
            <h3 class="text-white text-sm font-semibold mb-4 uppercase tracking-wide">
                Policy
            </h3>

            <ul class="space-y-2 text-sm text-muted">
                <li><a href="{{ route('privacy-policy') }}" class="hover:text-white transition">Privacy Policy</a></li>
                <li><a href="{{ route('shipping-policy') }}" class="hover:text-white transition">Shipping Policy</a></li>
                <li><a href="{{ route('terms-conditions') }}" class="hover:text-white transition">Terms & Conditions</a></li>
                <li><a href="{{ route('return-refund') }}" class="hover:text-white transition">Returns & Refunds</a></li>
            </ul>
        </div>

    </div>

    <!-- BOTTOM -->
    <div class="border-t border-subtle">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-muted">

            <p>© {{ date('Y') }} Tobac-Go</p>

            <div class="flex gap-5">
                <a href="https://techonika.com" target="_blank" class="hover:text-white transition">Powered by Techonika</a>
            </div>

        </div>
    </div>

</footer>

<nav class="lg:hidden fixed -bottom-px inset-x-0 z-50 bg-[#050607] border-t border-white/5 pb-[calc(env(safe-area-inset-bottom,0px)+2px)]">
    <div class="grid grid-cols-5 h-16">
        @php
            $links = [
                ['name' => 'Home', 'route' => 'home', 'icon' => 'ri-home-5', 'active' => request()->routeIs('home')],
                ['name' => 'Category', 'route' => 'category', 'icon' => 'ri-function', 'active' => request()->routeIs('category')],
                ['name' => 'Shop', 'route' => 'products', 'icon' => 'ri-shopping-bag-3', 'active' => request()->routeIs('products')],
                ['name' => 'Cart', 'route' => 'cart', 'icon' => 'ri-shopping-cart-2', 'active' => request()->routeIs('cart')],
                ['name' => auth()->check() ? 'Account' : 'Sign In', 'route' => 'user.profile', 'icon' => 'ri-user-3', 'active' => request()->routeIs('user.*') || request()->routeIs('login')],
            ];
        @endphp

        @foreach($links as $link)
            <a href="{{ route($link['route']) }}" wire:navigate
                class="relative flex flex-col items-center justify-center gap-1 transition-all duration-300 {{ $link['active'] ? 'text-white' : 'text-white/40' }}">

                <div class="relative">
                    <i class="{{ $link['active'] ? $link['icon'].'-fill' : $link['icon'].'-line' }} text-[21px]"></i>

                    @if($link['name'] === 'Cart')
                        <span
                            x-cloak
                            x-show="cartCount > 0"
                            x-text="cartCount > 99 ? '99+' : cartCount"
                            class="absolute -top-1.5 -right-2 min-w-[15px] h-[15px] px-1 rounded-full bg-red-500 text-white text-[9px] font-bold flex items-center justify-center border border-[#050607]"
                        ></span>
                    @endif
                </div>

                <span class="text-[9px] font-medium tracking-wide">{{ $link['name'] }}</span>
            </a>
        @endforeach
    </div>
</nav>
</div>
