@php
$headerCartCount = current_cart_items_count();
@endphp

<div x-cloak x-data="{
        mobileOpen: false,
        dropdown: null,
        userMenu: false,
        cartCount: {{ $headerCartCount }},
        searchOpen: false,
        closeSearch() {
            this.searchOpen = false;
        },
    }"
    x-on:cart-updated.window="cartCount = Number($event.detail?.count ?? 0)">

    {{-- ── TOP TICKER ── --}}
    <div class="bg-[#0b0d0f] border-b border-white/5 overflow-hidden"
        x-data="{ 
            messages: [
                { text: 'Free shipping on orders Rs. 3000+', icon: 'ri-truck-line' },
                { text: 'Premium Range Available', icon: 'ri-fire-line' },
                { text: 'Need help finding something? Chat with us', icon: 'ri-whatsapp-line' }
            ],
            current: 0,
            init() {
                setInterval(() => {
                    this.current = (this.current + 1) % this.messages.length;
                }, 5000);
            }
         }">
        <div class="max-w-7xl mx-auto px-4 h-10 flex items-center justify-center relative">
            <template x-for="(msg, i) in messages" :key="i">
                <div x-show="current === i"
                    x-transition:enter="transition ease-out duration-700"
                    x-transition:enter-start="opacity-0 translate-y-3"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-500"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-3"
                    class="absolute flex items-center gap-2.5 text-[10px] font-bold tracking-widest text-white/90 uppercase">
                    <i :class="msg.icon" class="text-blue-500 text-sm"></i>
                    <span x-text="msg.text"></span>
                </div>
            </template>
        </div>
    </div>

    {{-- ── HEADER BAR ── --}}
    <header class="sticky top-0 z-50 bg-[#07080a]/90 backdrop-blur-xl border-b border-white/10">

        {{-- Main row --}}
        <div class="max-w-7xl mx-auto px-4 lg:px-6 h-16 flex items-center gap-4 lg:gap-6">

            <a href="{{ route('home') }}" class="relative inline-block">
                <!-- Glow Effect -->
                <span
                    class="pointer-events-none absolute -inset-x-3 -inset-y-3 rounded-full opacity-90 blur-2xl"
                    style="background: radial-gradient(circle, rgba(236,72,153,0.7) 0%, rgba(168,85,247,0.55) 42%, rgba(168,85,247,0) 76%);">
                </span>

                <!-- Logo -->
                <img src="{{ asset('logo.webp') }}" class="relative w-auto h-12 pb-1  lg:h-12" alt="Tobac-Go">
            </a>
            <div class="flex-1"></div>

            {{-- DESKTOP SEARCH --}}
            <div class="hidden lg:flex lg:w-80 xl:w-96 lg:shrink-0">
                <form action="{{ route('search') }}" method="GET" class="relative w-full" @click.outside="closeSearch()">
                    <i class="ri-search-line absolute left-3.5 top-1/2 -translate-y-1/2 text-white/30 text-sm"></i>
                    <input type="text"
                        name="q"
                        wire:model.live.debounce.300ms="search"
                        wire:key="desktop-search-input"
                        @focus="searchOpen = true"
                        @keydown.escape="closeSearch()"
                        placeholder="Search by product or SKU..."
                        class="w-full pl-9 pr-24 py-2 rounded-xl bg-white/5 border border-white/8 text-sm text-white placeholder-white/30 focus:outline-none focus:border-white/20 focus:bg-white/7 transition-all">
                    <button type="submit" class="absolute right-1 top-1 bottom-1 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-white/70 hover:text-white text-xs font-semibold uppercase transition-all flex items-center justify-center gap-1.5">
                        <i class="ri-search-line text-sm text-white/40"></i>
                        <span>Search</span>
                    </button>

                    <div x-cloak x-show="searchOpen"
                        x-transition
                        class="absolute top-[calc(100%+8px)] left-0 right-0 rounded-2xl border border-white/10 bg-[#0d0f11] shadow-2xl shadow-black/40 overflow-hidden">
                        <div class="max-h-90 overflow-y-auto">
                            <div wire:loading wire:target="search" class="px-4 py-6 text-xs text-white/60 flex items-center gap-2">
                                <i class="ri-loader-4-line animate-spin"></i>
                                Searching products...
                            </div>

                            @php $desktopResults = $this->searchResults(); @endphp

                            @if(mb_strlen($search) >= 2)
                            @if($desktopResults->isEmpty())
                            <div wire:loading.remove wire:target="search" class="px-4 py-6 text-xs text-white/50">No matching products found.</div>
                            @else
                            <div wire:loading.remove wire:target="search">
                                @foreach($desktopResults as $item)
                                <a href="{{ route('product', $item->slug) }}"
                                    class="flex items-center gap-3 px-3 py-2.5 hover:bg-white/5 transition-all border-b border-white/5 last:border-b-0">
                                    <img src="{{ $this->searchImage($item) }}" alt="{{ $item->name }}" class="w-10 h-10 rounded-lg object-cover border border-white/10 bg-white/5">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm text-white truncate">{{ $item->name }}</p>
                                        <div class="text-[11px] text-white/45 flex items-center gap-2">
                                            <span>{{ $item->category?->title ?: 'General' }}</span>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                            @endif
                            @else
                            <div class="px-4 py-6 text-xs text-white/50">Type at least 2 characters to search.</div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            {{-- DESKTOP ACTIONS --}}
            <div class="hidden lg:flex items-center gap-2">

                @php $user = auth()->user(); @endphp

                @guest
                <a href="{{ route('login') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm text-white/70 hover:text-white hover:bg-white/6 border border-white/8 hover:border-white/15 transition-all">
                    <i class="ri-user-line text-base"></i> Sign In
                </a>
               
                @else
                @if ($user?->is_admin)
                <a href="{{ route('admin.dashboard') }}" aria-label="Admin"
                    class="w-9 h-9 rounded-xl bg-white/5 border border-white/8 flex items-center justify-center text-white/60 hover:text-white hover:border-white/20 transition-all">
                    <i class="ri-settings-3-line text-base"></i>
                </a>
                @endif

                <div class="relative" @click.outside="userMenu=false">
                    <button @click="userMenu=!userMenu" aria-label="Account"
                        class="w-9 h-9 rounded-xl bg-white/5 border border-white/8 flex items-center justify-center text-white hover:border-white/20 transition-all overflow-hidden">
                        @if($user?->avatar)
                        <img src="{{ $user->avatar }}" class="w-full h-full object-cover">
                        @else
                        <span class="text-xs font-bold">{{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}</span>
                        @endif
                    </button>
                    <div x-show="userMenu" x-transition.origin.top.right
                        class="absolute right-0 mt-2 w-56 rounded-xl border border-white/8 bg-[#0d0f11] shadow-2xl p-1.5">
                        <div class="px-3 py-2.5 mb-1">
                            <p class="text-xs text-white/40 mb-0.5">Signed in as</p>
                            <p class="text-sm text-white font-medium truncate">{{ $user?->name }}</p>
                            <p class="text-xs text-white/40 truncate">{{ $user?->email }}</p>
                        </div>
                        <div class="h-px bg-white/6 mx-1 mb-1"></div>
                        <a href="{{ route('user.profile') }}"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all">
                            <i class="ri-user-3-line text-white/40"></i> My Profile
                        </a>
                        <a href="{{ route('user.address') }}"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all">
                            <i class="ri-map-pin-line text-white/40"></i> My Addresses
                        </a>
                        <a href="{{ route('user.orders') }}"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all">
                            <i class="ri-shopping-bag-3-line text-white/40"></i> My Orders
                        </a>
                        <div class="h-px bg-white/6 mx-1 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all">
                                <i class="ri-logout-box-r-line text-white/40"></i> Sign out
                            </button>
                        </form>
                    </div>
                </div>
                @endguest

                {{-- Cart --}}
                <a href="/cart" class="relative" aria-label="Cart">
                    <div class="w-9 h-9 rounded-xl bg-white/5 border border-white/8 flex items-center justify-center text-white/70 hover:text-white hover:border-white/20 transition-all">
                        <i class="ri-shopping-cart-line text-base"></i>
                    </div>
                    <span class="absolute -top-1.5 -right-1.5 bg-white text-black text-[9px] font-bold min-w-4 h-4 px-1 rounded-full flex items-center justify-center" x-text="cartCount"></span>
                </a>

            </div>

            {{-- MOBILE RIGHT ICONS --}}
            <div class="flex lg:hidden items-center gap-2">
                <a href="/cart" class="relative" aria-label="Cart">
                    <div class="w-9 h-9 rounded-xl bg-white/5 border border-white/8 flex items-center justify-center text-white/70">
                        <i class="ri-shopping-cart-line text-base"></i>
                    </div>
                    <span class="absolute -top-1.5 -right-1.5 bg-white text-black text-[9px] font-bold min-w-4 h-4 px-1 rounded-full flex items-center justify-center" x-text="cartCount"></span>
                </a>

                <button @click="mobileOpen = true" aria-label="Open menu"
                    class="w-9 h-9 rounded-xl bg-white/5 border border-white/8 flex items-center justify-center text-white/70 hover:text-white transition-all">
                    <i class="ri-menu-3-line text-base"></i>
                </button>
            </div>

        </div>
        {{-- DESKTOP MENU ROW --}}
        <div class="hidden lg:block border-t border-white/5">
            <div class="max-w-7xl mx-auto px-4 lg:px-6 h-12 flex items-center">
                <nav class="flex items-center gap-1 text-sm font-medium text-white/60">

                    <a href="{{ route('home') }}" class="px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/5 transition-all">
                        Home
                    </a>

                    {{-- Hookah --}}
                    <div class="relative" @mouseenter="dropdown='hookah'" @mouseleave="dropdown=null">
                        <button class="flex items-center gap-1 px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/5 transition-all">
                            Hookah
                            <i class="ri-arrow-down-s-line text-xs transition-transform duration-200" :class="dropdown==='hookah' ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="dropdown==='hookah'" x-transition.origin.top.left
                            class="absolute top-full left-0 mt-2 w-56 rounded-xl border border-white/8 bg-[#0d0f11] shadow-2xl py-1.5 z-50">
                            <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'tobac-go-hookah']) }}" class="block px-3.5 py-2 text-sm font-bold text-orange-400 hover:text-orange-300 hover:bg-white/5 transition-all rounded-lg mx-1">Tobac-Go Exclusive Hookah</a>
                            <div class="h-px bg-white/5 mx-2 my-1"></div>
                            <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'vg-france-foggit-hookah']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">VG France Foggit Hookah</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'thugs-hookah']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Thugs Hookah</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'mya-hookah']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Mya Hookah</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'whiskey-bottle-hookah']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Whiskey Bottle Hookah</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'enrolando-hookah']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Enrolando Hookah</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'dum-hookahs']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">DUM Hookahs</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'digital-smoke-hookah']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Digital Smoke Hookah</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'deja-vu-hookah']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Deja Vu Hookah</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'cocoyaya-hookah']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Cocoyaya Hookah</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'tobac-go-car-hookah']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Tobac-Go Car Hookah</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'alshan-hookah']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Alshan Hookah</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'al-akbar-hookah']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Al Akbar Hookah</a>
                        </div>
                    </div>

                    {{-- Shop By Budget --}}
                    <div class="relative" @mouseenter="dropdown='budget'" @mouseleave="dropdown=null">
                        <button class="flex items-center gap-1 px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/5 transition-all">
                            Shop By Budget
                            <i class="ri-arrow-down-s-line text-xs transition-transform duration-200" :class="dropdown==='budget' ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="dropdown==='budget'" x-transition.origin.top.left
                            class="absolute top-full left-0 mt-2 w-52 rounded-xl border border-white/8 bg-[#0d0f11] shadow-2xl py-1.5">
                            <a href="{{ route('seo.hookah-under-3000') }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Hookah under 3000</a>
                            <a href="{{ route('seo.hookah-under-5000') }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Hookah under 5000</a>
                            <a href="{{ route('seo.hookah-above-7000') }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Hookah Above ₹7000</a>
                        </div>
                    </div>

                    {{-- Bongs --}}
                    <div class="relative" @mouseenter="dropdown='bongs'" @mouseleave="dropdown=null">
                        <button class="flex items-center gap-1 px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/5 transition-all">
                            Bongs
                            <i class="ri-arrow-down-s-line text-xs transition-transform duration-200" :class="dropdown==='bongs' ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="dropdown==='bongs'" x-transition.origin.top.left
                            class="absolute top-full left-0 mt-2 w-52 rounded-xl border border-white/8 bg-[#0d0f11] shadow-2xl py-1.5 z-50">
                            <a href="{{ route('products.category.subcategory', ['category' => 'bongs', 'subcategory' => 'acrylic-bongs']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Acrylic Bongs</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'bongs', 'subcategory' => 'glass-percolator-bongs']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Glass Percolator Bongs</a>
                        </div>
                    </div>

                    {{-- Accessories --}}
                    <div class="relative" @mouseenter="dropdown='acc'" @mouseleave="dropdown=null">
                        <button class="flex items-center gap-1 px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/5 transition-all">
                            Accessories
                            <i class="ri-arrow-down-s-line text-xs transition-transform duration-200" :class="dropdown==='acc' ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="dropdown==='acc'" x-transition.origin.top.left
                            class="absolute top-full left-0 mt-2 w-56 rounded-xl border border-white/8 bg-[#0d0f11] shadow-2xl py-1.5 z-50">
                            <a href="{{ route('products.category', ['category' => 'smoking-accessories']) }}" class="block px-3.5 py-2 text-sm font-bold text-orange-400 hover:text-orange-300 hover:bg-white/5 transition-all rounded-lg mx-1">Smoking Accessories</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'smoking-accessories', 'subcategory' => 'wooden-smoking-pipe']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Wooden Smoking Pipe</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'smoking-accessories', 'subcategory' => 'smoking-glass-pipe']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Smoking Glass Pipe</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'smoking-accessories', 'subcategory' => 'metal-shooter']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Metal Shooter</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'smoking-accessories', 'subcategory' => 'glass-bong-shooter']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Glass Bong Shooter</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'smoking-accessories', 'subcategory' => 'filter-screens']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Filter Screens</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'smoking-accessories', 'subcategory' => 'crusher']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Crusher</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'smoking-accessories', 'subcategory' => 'cleaning-brush']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Cleaning Brush</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'smoking-accessories', 'subcategory' => 'baba-chillum']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Baba Chillum</a>
                            <a href="{{ route('products.category', ['category' => 'hookah-accessories']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Hookah Accessories</a>
                            <a href="{{ route('products.category', ['category' => 'hookah-chillum']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Hookah Chillum</a>
                            <a href="{{ route('products.category', ['category' => 'pipe-and-handle']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Pipe and Handle</a>
                        </div>
                    </div>

                    {{-- Combos --}}
                    <div class="relative" @mouseenter="dropdown='combos'" @mouseleave="dropdown=null">
                        <button class="flex items-center gap-1 px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/5 transition-all">
                            Combos
                            <i class="ri-arrow-down-s-line text-xs transition-transform duration-200" :class="dropdown==='combos' ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="dropdown==='combos'" x-transition.origin.top.left
                            class="absolute top-full left-0 mt-2 w-56 rounded-xl border border-white/8 bg-[#0d0f11] shadow-2xl py-1.5 z-50">
                            <a href="{{ route('products.category.subcategory', ['category' => 'combos', 'subcategory' => 'smoking-glass-pipe-combo']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Smoking Glass Pipe Combo</a>
                            <a href="{{ route('products.category.subcategory', ['category' => 'combos', 'subcategory' => 'wooden-smoking-pipes-combo']) }}" class="block px-3.5 py-2 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all rounded-lg mx-1">Wooden Smoking Pipes Combo</a>
                        </div>
                    </div>
                    <a href="{{ route('products.category', ['category' => 'lighters']) }}" class="px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/5 transition-all">Lighters</a>
                    <a href="{{ route('products.category', ['category' => 'ashtray']) }}" class="px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/5 transition-all">Ashtray</a>
                    <a href="{{ route('blogs') }}" class="px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/5 transition-all">Blogs</a>
                    <a href="{{ route('location.noida') }}" class="px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/5 transition-all">Noida Store</a>
                    <a href="{{ route('about') }}" class="px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/5 transition-all">About</a>
                    <a href="{{ route('contact') }}" class="px-3 py-1.5 rounded-lg hover:text-white hover:bg-white/5 transition-all">Contact</a>

                </nav>
            </div>
        </div>
        {{-- MOBILE SEARCH BAR (always visible below header on phones) --}}
        <div class="lg:hidden border-t border-white/5 px-4 py-2.5 bg-[#07080a]/90">
            <form action="{{ route('search') }}" method="GET" class="relative" @click.outside="closeSearch()">
                <i class="ri-search-line absolute left-3.5 top-1/2 -translate-y-1/2 text-white/30 text-sm"></i>
                <input type="text"
                    name="q"
                    wire:model.live.debounce.300ms="search"
                    wire:key="mobile-search-input"
                    @focus="searchOpen = true"
                    @keydown.escape="closeSearch()"
                    placeholder="Search by product or SKU..."
                    class="w-full pl-9 pr-24 py-2.5 rounded-xl bg-white/5 border border-white/8 text-sm text-white placeholder-white/30 focus:outline-none focus:border-white/20 transition-all">
                <button type="submit" class="absolute right-1 top-1 bottom-1 px-3 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-white/70 hover:text-white text-xs font-semibold uppercase transition-all flex items-center justify-center gap-1.5">
                    <i class="ri-search-line text-sm text-white/40"></i>
                    <span>Search</span>
                </button>

                <div x-cloak x-show="searchOpen"
                    x-transition
                    class="absolute top-[calc(100%+8px)] left-0 right-0 rounded-2xl border border-white/10 bg-[#0d0f11] shadow-2xl shadow-black/40 overflow-hidden z-20">
                    <div class="max-h-[60vh] overflow-y-auto">
                        <div wire:loading wire:target="search" class="px-4 py-6 text-xs text-white/60 flex items-center gap-2">
                            <i class="ri-loader-4-line animate-spin"></i>
                            Searching products...
                        </div>

                        @php $mobileResults = $this->searchResults(); @endphp

                        @if(mb_strlen($search) >= 2)
                        @if($mobileResults->isEmpty())
                        <div wire:loading.remove wire:target="search" class="px-4 py-6 text-xs text-white/50">No matching products found.</div>
                        @else
                        <div wire:loading.remove wire:target="search">
                            @foreach($mobileResults as $item)
                            <a href="{{ route('product', $item->slug) }}"
                                class="flex items-center gap-3 px-3 py-2.5 hover:bg-white/5 transition-all border-b border-white/5 last:border-b-0">
                                <img src="{{ $this->searchImage($item) }}" alt="{{ $item->name }}" class="w-10 h-10 rounded-lg object-cover border border-white/10 bg-white/5">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm text-white truncate">{{ $item->name }}</p>
                                    <div class="text-[11px] text-white/45 flex items-center gap-2">
                                        <span>{{ $item->category?->title ?: 'General' }}</span>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        @endif
                        @else
                        <div class="px-4 py-6 text-xs text-white/50">Type at least 2 characters to search.</div>
                        @endif
                    </div>
                </div>
            </form>
        </div>

    </header>

    {{-- ── MOBILE FULL-SCREEN SLIDE FROM RIGHT ── --}}

    {{-- Backdrop --}}
    <div x-show="mobileOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileOpen=false"
        class="lg:hidden fixed inset-0 z-40 bg-black/60 backdrop-blur-sm">
    </div>

    {{-- Drawer panel --}}
    <div x-show="mobileOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="lg:hidden fixed inset-0 z-50 bg-[#07080a] flex flex-col overflow-y-auto">

        {{-- Drawer header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-white/10 shrink-0">
            <a href="{{ route('home') }}">
                <img src="{{ asset('logo.webp') }}" class="h-9" alt="Tobac-Go">
            </a>
            <button @click="mobileOpen=false" aria-label="Close menu"
                class="w-9 h-9 rounded-xl bg-white/5 border border-white/8 flex items-center justify-center text-white/70 hover:text-white transition-all">
                <i class="ri-close-line text-lg"></i>
            </button>
        </div>

        {{-- Drawer body --}}
        <div class="flex-1 px-4 py-5 space-y-1 overflow-y-auto">

            <a href="{{ route('home') }}"
                class="flex items-center justify-between px-4 py-3 rounded-xl text-white/70 hover:text-white hover:bg-white/5 transition-all text-[15px]">
                Home
            </a>

            {{-- Hookah accordion --}}
            <div x-data="{ sub: false }">
                <button @click="sub=!sub"
                    class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-white/70 hover:text-white hover:bg-white/5 transition-all text-[15px]">
                    Hookah
                    <i class="ri-arrow-down-s-line text-white/40 transition-transform duration-200" :class="sub ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="sub" x-transition class="ml-4 mt-0.5 space-y-0.5 border-l border-white/8 pl-3">
                    <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'tobac-go-hookah']) }}" class="block px-3 py-2.5 rounded-lg text-sm font-bold text-orange-400 hover:text-orange-300 hover:bg-white/5 transition-all">Tobac-Go Exclusive</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'vg-france-foggit-hookah']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">VG France Foggit</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'thugs-hookah']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Thugs Hookah</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'mya-hookah']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Mya Hookah</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'whiskey-bottle-hookah']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Whiskey Bottle</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'enrolando-hookah']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Enrolando Hookah</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'dum-hookahs']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">DUM Hookahs</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'digital-smoke-hookah']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Digital Smoke</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'deja-vu-hookah']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Deja Vu Hookah</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'cocoyaya-hookah']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Cocoyaya Hookah</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'tobac-go-car-hookah']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Tobac-Go Car</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'alshan-hookah']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Alshan Hookah</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'premium-hookah', 'subcategory' => 'al-akbar-hookah']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Al Akbar Hookah</a>
                </div>
            </div>

            {{-- Budget accordion --}}
            <div x-data="{ sub: false }">
                <button @click="sub=!sub"
                    class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-white/70 hover:text-white hover:bg-white/5 transition-all text-[15px]">
                    Shop By Budget
                    <i class="ri-arrow-down-s-line text-white/40 transition-transform duration-200" :class="sub ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="sub" x-transition class="ml-4 mt-0.5 space-y-0.5 border-l border-white/8 pl-3">
                    <a href="{{ route('seo.hookah-under-3000') }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Hookah under 3000</a>
                    <a href="{{ route('seo.hookah-under-5000') }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Hookah under 5000</a>
                    <a href="{{ route('seo.hookah-above-7000') }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Hookah Above ₹7000</a>
                </div>
            </div>

            {{-- Bongs accordion --}}
            <div x-data="{ sub: false }">
                <button @click="sub=!sub"
                    class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-white/70 hover:text-white hover:bg-white/5 transition-all text-[15px]">
                    Bongs
                    <i class="ri-arrow-down-s-line text-white/40 transition-transform duration-200" :class="sub ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="sub" x-transition class="ml-4 mt-0.5 space-y-0.5 border-l border-white/8 pl-3">
                    <a href="{{ route('products.category.subcategory', ['category' => 'bongs', 'subcategory' => 'acrylic-bongs']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Acrylic Bongs</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'bongs', 'subcategory' => 'glass-percolator-bongs']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Glass Percolator Bongs</a>
                </div>
            </div>

            {{-- Accessories accordion --}}
            <div x-data="{ sub: false }">
                <button @click="sub=!sub"
                    class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-white/70 hover:text-white hover:bg-white/5 transition-all text-[15px]">
                    Accessories
                    <i class="ri-arrow-down-s-line text-white/40 transition-transform duration-200" :class="sub ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="sub" x-transition class="ml-4 mt-0.5 space-y-0.5 border-l border-white/8 pl-3">
                    <a href="{{ route('products.category', ['category' => 'smoking-accessories']) }}" class="block px-3 py-2.5 rounded-lg text-sm font-bold text-orange-400 hover:text-orange-300 hover:bg-white/5 transition-all">Smoking Accessories</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'smoking-accessories', 'subcategory' => 'wooden-smoking-pipe']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Wooden Smoking Pipe</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'smoking-accessories', 'subcategory' => 'smoking-glass-pipe']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Smoking Glass Pipe</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'smoking-accessories', 'subcategory' => 'metal-shooter']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Metal Shooter</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'smoking-accessories', 'subcategory' => 'glass-bong-shooter']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Glass Bong Shooter</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'smoking-accessories', 'subcategory' => 'filter-screens']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Filter Screens</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'smoking-accessories', 'subcategory' => 'crusher']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Crusher</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'smoking-accessories', 'subcategory' => 'cleaning-brush']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Cleaning Brush</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'smoking-accessories', 'subcategory' => 'baba-chillum']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Baba Chillum</a>
                    <a href="{{ route('products.category', ['category' => 'hookah-accessories']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Hookah Accessories</a>
                    <a href="{{ route('products.category', ['category' => 'hookah-chillum']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Hookah Chillum</a>
                    <a href="{{ route('products.category', ['category' => 'pipe-and-handle']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Pipe and Handle</a>
                </div>
            </div>

            {{-- Combos accordion --}}
            <div x-data="{ sub: false }">
                <button @click="sub=!sub"
                    class="flex items-center justify-between w-full px-4 py-3 rounded-xl text-white/70 hover:text-white hover:bg-white/5 transition-all text-[15px]">
                    Combos
                    <i class="ri-arrow-down-s-line text-white/40 transition-transform duration-200" :class="sub ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="sub" x-transition class="ml-4 mt-0.5 space-y-0.5 border-l border-white/8 pl-3">
                    <a href="{{ route('products.category.subcategory', ['category' => 'combos', 'subcategory' => 'smoking-glass-pipe-combo']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Smoking Glass Pipe Combo</a>
                    <a href="{{ route('products.category.subcategory', ['category' => 'combos', 'subcategory' => 'wooden-smoking-pipes-combo']) }}" class="block px-3 py-2.5 rounded-lg text-sm text-white/50 hover:text-white hover:bg-white/5 transition-all">Wooden Smoking Pipes Combo</a>
                </div>
            </div>
            <a href="{{ route('products.category', ['category' => 'lighters']) }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-white/70 hover:text-white hover:bg-white/5 transition-all text-[15px]">
                Lighters
            </a>
            <a href="{{ route('products.category', ['category' => 'ashtray']) }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-white/70 hover:text-white hover:bg-white/5 transition-all text-[15px]">
                Ashtrays
            </a>
            <a href="{{ route('blogs') }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-white/70 hover:text-white hover:bg-white/5 transition-all text-[15px]">
                Blogs
            </a>
            <a href="{{ route('location.noida') }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-white/70 hover:text-white hover:bg-white/5 transition-all text-[15px]">
                Noida Store
            </a>
            <a href="{{ route('about') }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-white/70 hover:text-white hover:bg-white/5 transition-all text-[15px]">
                About
            </a>
            <a href="{{ route('contact') }}" class="flex items-center justify-between px-4 py-3 rounded-xl text-white/70 hover:text-white hover:bg-white/5 transition-all text-[15px]">
                Contact
            </a>

        </div>

        {{-- Drawer footer / auth --}}
        <div class="shrink-0 px-4 py-5 border-t border-white/10 space-y-2.5">

            @guest
            <a href="{{ route('login') }}"
                class="flex items-center justify-center gap-2 w-full py-3 rounded-xl border border-white/10 bg-white/5 text-sm text-white/80 hover:text-white hover:bg-white/10 transition-all">
                <i class="ri-user-line"></i> Sign In
            </a>
            <a href="{{ route('register') }}"
                class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-white text-black text-sm font-semibold hover:bg-white/90 transition-all">
                Create Account
            </a>
            @else
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-white/5 border border-white/8">
                <div class="w-9 h-9 rounded-xl bg-white/10 border border-white/8 flex items-center justify-center shrink-0 overflow-hidden">
                    @if(auth()->user()?->avatar)
                    <img src="{{ auth()->user()->avatar }}" class="w-full h-full object-cover">
                    @else
                    <span class="text-xs font-bold text-white">{{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}</span>
                    @endif
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm text-white font-medium truncate">{{ auth()->user()?->name }}</p>
                    <p class="text-xs text-white/40 truncate">{{ auth()->user()?->email }}</p>
                </div>
            </div>

            @if(auth()->user()?->is_admin)
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center justify-center gap-2 w-full py-3 rounded-xl border border-white/10 bg-white/5 text-sm text-white/80 hover:text-white transition-all">
                <i class="ri-settings-3-line"></i> Admin Dashboard
            </a>
            @endif

            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('user.profile') }}"
                    class="flex items-center justify-center gap-2 py-3 rounded-xl border border-white/10 bg-white/5 text-sm text-white/70 hover:text-white transition-all">
                    <i class="ri-user-3-line text-sm"></i> My Profile
                </a>
                <a href="{{ route('user.address') }}"
                    class="flex items-center justify-center gap-2 py-3 rounded-xl border border-white/10 bg-white/5 text-sm text-white/70 hover:text-white transition-all">
                    <i class="ri-map-pin-line text-sm"></i> Addresses
                </a>
                <a href="{{ route('user.orders') }}"
                    class="col-span-2 flex items-center justify-center gap-2 py-3 rounded-xl border border-white/10 bg-white/5 text-sm text-white/70 hover:text-white transition-all">
                    <i class="ri-shopping-bag-3-line text-sm"></i> My Orders
                </a>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center justify-center gap-2 w-full py-3 rounded-xl border border-white/10 bg-white/5 text-sm text-white/70 hover:text-white transition-all">
                    <i class="ri-logout-box-r-line"></i> Sign out
                </button>
            </form>
            @endguest

        </div>

    </div>

</div>

