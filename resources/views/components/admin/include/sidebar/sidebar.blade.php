<div>
    <!-- 📱 Mobile Sidebar Backdrop -->
    <div x-show="mobileMenu" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm lg:hidden"
         @click="mobileMenu = false"></div>

    <!-- 🔷 Sidebar Panel -->
    <aside class="fixed inset-y-0 left-0 z-50 flex flex-col w-72 lg:w-64 bg-white border-r border-slate-200 transition-transform duration-300 transform lg:translate-x-0"
           :class="mobileMenu ? 'translate-x-0' : '-translate-x-full'">
        
        <div class="flex flex-col h-full">

            <!-- 🔷 Logo Section -->
            <div class="flex items-center justify-between px-5 h-14 lg:h-16 border-b border-slate-200">
                <a href="{{ route('home') }}" wire:navigate class="flex-shrink-0">
                    <img src="{{ asset('logo.webp') }}" class="h-7 w-auto" alt="Logo">
                </a>

                <!-- Close Mobile Menu -->
                <button @click="mobileMenu = false" 
                        class="lg:hidden h-9 w-9 flex items-center justify-center rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <!-- 🔷 Menu -->
            <div class="flex-1 px-3 py-4 overflow-y-auto custom-scrollbar">

                <nav class="space-y-1">

                        @foreach (\App\Views\Builders\AdminSidebar::menu(user: auth()->user())->get() as $menu)

                            @php
                                // determine if current URL matches this menu or any of its submenu URLs
                                $isActive = url()->current() === $menu->url;
                                $submenuUrls = collect($menu->submenu ?? [])->pluck('url')->toArray();
                                $isActiveParent = $isActive || in_array(url()->current(), $submenuUrls);
                            @endphp

                            <div x-data="{ open: {{ $isActiveParent ? 'true' : 'false' }} }" class="">

                                @if($menu->hasSubmenu)
                                    <button @click="open = !open"
                                            :aria-expanded="open"
                                            class="w-full flex items-center justify-between gap-3 px-3 py-2 rounded-lg text-sm transition
                                                {{ $isActiveParent ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">

                                        <div class="flex items-center gap-3">
                                            <i class="{{ $menu->icon ?? 'ri-layout-grid-line' }} text-lg {{ $isActiveParent ? 'text-blue-700' : 'text-slate-400' }}"></i>
                                            <span class="font-medium">{{ $menu->title }}</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <i :class="open ? 'ri-arrow-down-s-line rotate-180' : 'ri-arrow-down-s-line'" class="text-lg text-slate-400 transition-transform"></i>
                                        </div>
                                    </button>

                                    <div x-show="open" x-collapse class="mt-1 ml-4 pl-4 border-l border-slate-200 space-y-1">
                                        @foreach($menu->submenu as $sub)
                                            @php $isSubActive = url()->current() === $sub->url; @endphp
                                            <a href="{{ $sub->url }}" wire:navigate class="flex items-center gap-3 px-3 py-1.5 rounded-md text-[13px] transition
                                                {{ $isSubActive ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                                <span class="h-1.5 w-1.5 rounded-full {{ $isSubActive ? 'bg-blue-600' : 'bg-slate-300' }}"></span>
                                                <span class="truncate">{{ $sub->title }}</span>
                                            </a>
                                        @endforeach
                                    </div>

                                @else
                                    <a href="{{ $menu->url }}" wire:navigate
                                       class="group flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition
                                       {{ $isActive ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">

                                        <i class="{{ $menu->icon ?? 'ri-layout-grid-line' }} text-lg {{ $isActive ? 'text-blue-700' : 'text-slate-400 group-hover:text-slate-500' }}"></i>

                                        <span class="font-medium">{{ $menu->title }}</span>
                                    </a>
                                @endif

                            </div>

                        @endforeach

                </nav>
            </div>

            <!-- 🔷 Bottom Section / User Profile -->
            <div class="p-3 border-t border-slate-200 bg-slate-50/40">
                <div class="flex items-center gap-3 px-3 py-2 rounded-xl bg-white border border-slate-200">

                    <!-- User Avatar -->
                    <div class="h-9 w-9 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <!-- User Info -->
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-900 truncate">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-[10px] font-medium text-slate-500 uppercase">
                            Admin
                        </p>
                    </div>

                    <!-- Logout icon specifically for sidebar -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="flex items-center justify-center h-9 w-9 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition" title="Logout">
                            <i class="ri-logout-box-r-line text-lg"></i>
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </aside>
</div>
