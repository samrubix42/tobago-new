<div>
    {{-- Mobile Sidebar Backdrop --}}
    <div x-show="mobileMenu" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-gray-900/50 backdrop-blur-sm lg:hidden"
         @click="mobileMenu = false"
         x-cloak></div>

    <aside class="fixed inset-y-0 left-0 z-50 w-72 lg:w-64 bg-[#1b2434] text-white transform transition-transform duration-300 lg:translate-x-0 overflow-y-auto custom-scrollbar border-r border-white/5"
    :class="mobileMenu ? 'translate-x-0' : '-translate-x-full'"
    x-data="{ openMenu: null }"
    @click.away="if(window.innerWidth < 1024) mobileMenu = false">
    
    <div class="flex flex-col h-full">
        {{-- Logo Section --}}
        <div class="flex items-center gap-3 px-8 h-20 border-b border-white/5">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('logo.webp') }}" class="h-8 w-auto filter brightness-0 invert" alt="Logo">
               
            </a>
        </div>

        {{-- Mobile Close Button --}}
        <button @click="mobileMenu = false" 
                class="lg:hidden absolute top-4 right-4 p-2 text-white/50 hover:text-white hover:bg-white/10 rounded-lg transition">
            <i class="ri-close-line text-2xl"></i>
        </button>

        {{-- Navigation Menu --}}
        <div class="flex-1 px-4 pb-6 mt-4">
            <ul class="space-y-1.5">
                @foreach (\App\Views\Builders\AdminSidebar::menu(user: auth()->user())->get() as $menu)
                    @php
                        $isActive = request()->is(trim($menu->url, '/') . '*') || ($menu->url === route('admin.dashboard') && request()->routeIs('admin.dashboard'));
                        // Check if any sub-item is active
                        $anySubActive = false;
                        if($menu->hasSubmenu) {
                            foreach($menu->submenu as $sub) {
                                if(request()->is(trim($sub->url, '/') . '*')) {
                                    $anySubActive = true;
                                    break;
                                }
                            }
                        }
                    @endphp

                    <li class="nav-item" x-data="{ open: {{ ($isActive || $anySubActive) ? 'true' : 'false' }} }">
                        @if($menu->hasSubmenu)
                            {{-- Parent Menu --}}
                            <a href="javascript:void(0)"
                                class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium transition-all group
                                    {{ ($isActive || $anySubActive) ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-white/60 hover:text-white hover:bg-white/5' }}"
                                @click="open = !open">
                                <div class="flex items-center gap-3">
                                    <i class="{{ $menu->icon ?? 'ri-circle-line' }} text-xl {{ ($isActive || $anySubActive) ? 'text-white' : 'text-white/40 group-hover:text-white' }}"></i>
                                    <span>{{ $menu->title }}</span>
                                </div>
                                <i class="ri-arrow-down-s-line transition-transform duration-200 text-lg" :class="{ 'rotate-180': open }"></i>
                            </a>

                            {{-- Submenu --}}
                            <ul class="mt-1 space-y-1 ml-4 border-l border-white/10 pl-2" 
                                x-show="open" 
                                x-collapse
                                x-cloak>
                                @foreach($menu->submenu as $submenu)
                                    @php
                                        $isSubActive = request()->is(trim($submenu->url, '/') . '*');
                                    @endphp
                                    <li>
                                        <a href="{{ $submenu->url }}"
                                            class="flex items-center px-4 py-2 rounded-lg text-[13px] transition
                                            {{ $isSubActive ? 'text-white font-semibold' : 'text-white/50 hover:text-white hover:bg-white/5' }}">
                                            <span class="mr-2 opacity-30">•</span>
                                            {{ $submenu->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            {{-- Single Menu Item --}}
                            <a href="{{ $menu->url }}" 
                                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all group
                                {{ $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-white/60 hover:text-white hover:bg-white/5' }}">
                                <i class="{{ $menu->icon ?? 'ri-circle-line' }} text-xl {{ $isActive ? 'text-white' : 'text-white/40 group-hover:text-white' }}"></i>
                                <span>{{ $menu->title }}</span>
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

    
    </div>
</aside>
</div>


