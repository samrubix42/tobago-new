<div x-data="{ open: false, dropdown: null, userMenu: false }">

    <!-- 🔝 HEADER -->
    <header class="sticky top-0 z-50 border-b border-subtle bg-[#050607]/80 backdrop-blur-xl">

        <!-- 🧠 TOP -->
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between gap-4">

            <!-- 🔥 LOGO -->
            <a href="{{ route('home') }}">
                <img src="{{ asset('logo.webp') }}" class="h-11 sm:h-12">
            </a>

            <!-- 🔍 SEARCH (DESKTOP) -->
            <div class="hidden lg:flex flex-1 max-w-xl">
                <div class="relative w-full">
                    <input
                        type="text"
                        placeholder="Search products..."
                        class="w-full rounded-full bg-white/5 border border-subtle px-5 py-2.5 text-sm text-white placeholder-white/40 focus:outline-none focus:border-white/20 transition"
                    >
                    <i class="ri-search-line absolute right-4 top-1/2 -translate-y-1/2 text-white/40"></i>
                </div>
            </div>

            <!-- 👤 LOGIN + 🛒 CART -->
            <div class="hidden lg:flex items-center gap-4">

                @php
                    $user = auth()->user();
                @endphp

                @guest
                    <!-- LOGIN -->
                    <a href="{{ route('login') }}" class="group" aria-label="Login">
                        <div class="w-10 h-10 rounded-full bg-white/5 border border-subtle flex items-center justify-center hover:border-white/20 transition">
                            <i class="ri-user-line text-white/80"></i>
                        </div>
                    </a>

                    <!-- REGISTER -->
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 rounded-full border border-subtle bg-white/5 text-xs text-white/80 hover:border-white/20 transition">
                        Register
                    </a>
                @else
                    @if ($user?->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="group" aria-label="Admin dashboard">
                            <div class="w-10 h-10 rounded-full bg-white/5 border border-subtle flex items-center justify-center hover:border-white/20 transition">
                                <i class="ri-settings-3-line text-white/80"></i>
                            </div>
                        </a>
                    @endif

                    <div class="relative" @click.outside="userMenu=false">
                        <button type="button" @click="userMenu=!userMenu"
                            class="w-10 h-10 rounded-full bg-white/5 border border-subtle flex items-center justify-center hover:border-white/20 transition"
                            aria-label="Account">
                            <span class="text-xs font-semibold text-white">
                                {{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}
                            </span>
                        </button>

                        <div x-show="userMenu" x-transition
                             class="absolute right-0 mt-3 w-52 rounded-xl border border-subtle bg-[#0b0d0f] p-2">

                            <div class="px-3 py-2">
                                <p class="text-xs text-white/50">Signed in as</p>
                                <p class="text-sm text-white truncate">{{ $user?->email }}</p>
                            </div>

                            <div class="h-px bg-white/5 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-3 py-2 rounded hover:bg-white/5 text-sm text-white/80">
                                    <i class="ri-logout-box-r-line mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest

                <!-- CART -->
                <a href="/cart" class="relative group">

                    <div class="w-10 h-10 rounded-full bg-white/5 border border-subtle flex items-center justify-center hover:border-white/20 transition">
                        <i class="ri-shopping-bag-3-line text-white/80"></i>
                    </div>

                    <!-- Count -->
                    <span class="absolute -top-1 -right-1 bg-white text-[10px] px-1.5 rounded-full text-black">
                        1
                    </span>

                </a>

            </div>

            <!-- 📱 MOBILE BUTTON -->
            <button @click="open = !open"
                class="lg:hidden w-10 h-10 flex items-center justify-center border border-subtle rounded-full">
                <i class="text-xl" :class="open ? 'ri-close-line' : 'ri-menu-3-line'"></i>
            </button>

        </div>

        <!-- 🔥 MENU BAR -->
        <div class="hidden lg:block border-t border-subtle bg-[#0b0d0f]/80">

            <div class="max-w-7xl mx-auto px-6 py-3 flex items-center gap-8 text-sm text-white/70">

                <a href="#" class="hover:text-white transition">Home</a>

                <!-- Hookah -->
                <div class="relative"
                     @mouseenter="dropdown='hookah'" @mouseleave="dropdown=null">

                    <button class="flex items-center gap-1 hover:text-white transition">
                        Hookah <i class="ri-arrow-down-s-line"></i>
                    </button>

                    <div x-show="dropdown==='hookah'" x-transition
                         class="absolute top-full mt-3 w-48 rounded-xl border border-subtle bg-[#0b0d0f] p-2">

                        <a href="#" class="block px-3 py-2 rounded hover:bg-white/5">Premium</a>
                        <a href="#" class="block px-3 py-2 rounded hover:bg-white/5">Glass</a>
                        <a href="#" class="block px-3 py-2 rounded hover:bg-white/5">Mini</a>

                    </div>
                </div>

                <a href="#" class="hover:text-white">Bongs</a>

                <!-- Accessories -->
                <div class="relative"
                     @mouseenter="dropdown='acc'" @mouseleave="dropdown=null">

                    <button class="flex items-center gap-1 hover:text-white transition">
                        Accessories <i class="ri-arrow-down-s-line"></i>
                    </button>

                    <div x-show="dropdown==='acc'" x-transition
                         class="absolute top-full mt-3 w-48 rounded-xl border border-subtle bg-[#0b0d0f] p-2">

                        <a href="#" class="block px-3 py-2 rounded hover:bg-white/5">Hoses</a>
                        <a href="#" class="block px-3 py-2 rounded hover:bg-white/5">Bowls</a>
                        <a href="#" class="block px-3 py-2 rounded hover:bg-white/5">Charcoal</a>

                    </div>
                </div>

                <a href="#" class="hover:text-white">Combos</a>
                <a href="#" class="hover:text-white">Blogs</a>

            </div>

        </div>

        <!-- 📱 MOBILE MENU -->
        <div x-show="open" x-transition class="lg:hidden bg-[#0b0d0f] border-t border-subtle">

            <div class="px-4 py-5 space-y-4">

                <!-- Search -->
                <input type="text"
                    placeholder="Search..."
                    class="w-full rounded-full bg-white/5 border border-subtle px-4 py-2 text-sm text-white">

                <!-- Links -->
                <a href="#" class="block text-white/80">Home</a>

                <div x-data="{ sub:false }">
                    <button @click="sub=!sub" class="flex justify-between w-full text-white/80">
                        Hookah
                        <i class="ri-arrow-down-s-line"></i>
                    </button>

                    <div x-show="sub" class="pl-4 mt-2 space-y-2 text-sm text-white/60">
                        <a href="#">Premium</a>
                        <a href="#">Glass</a>
                        <a href="#">Mini</a>
                    </div>
                </div>

                <a href="#" class="block text-white/80">Accessories</a>
                <a href="#" class="block text-white/80">Combos</a>
                <a href="#" class="block text-white/80">Blogs</a>

                <!-- Actions -->
                <div class="pt-4 flex flex-col gap-3">

                    @guest
                        <a href="{{ route('login') }}"
                           class="w-full text-center py-2 rounded-full border border-subtle hover:border-white transition text-sm">
                            Login
                        </a>

                        <a href="{{ route('register') }}"
                           class="w-full text-center py-2 rounded-full border border-subtle hover:border-white transition text-sm">
                            Register
                        </a>
                    @else
                        @if (auth()->user()?->is_admin)
                            <a href="{{ route('admin.dashboard') }}"
                               class="w-full text-center py-2 rounded-full border border-subtle hover:border-white transition text-sm">
                                Admin Dashboard
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-center py-2 rounded-full border border-subtle hover:border-white transition text-sm">
                                Logout
                            </button>
                        </form>
                    @endguest

                    <a href="/cart"
                       class="w-full text-center py-2 rounded-full border border-subtle hover:border-white transition text-sm">
                        View Cart
                    </a>

                </div>

            </div>

        </div>

    </header>
</div>
