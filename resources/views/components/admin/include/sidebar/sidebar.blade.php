<div>
    {{-- Mobile Sidebar Backdrop --}}
    <div x-show="mobileMenu" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-slate-900/35 lg:hidden"
         @click="mobileMenu = false"
         x-cloak></div>

        <aside class="fixed inset-y-0 left-0 z-50 w-72 lg:w-64 bg-white text-slate-700 transform transition-transform duration-300 lg:translate-x-0 overflow-y-auto custom-scrollbar border-r border-slate-200"
    :class="mobileMenu ? 'translate-x-0' : '-translate-x-full'"
    x-data="{}"
    @click.away="if(window.innerWidth < 1024) mobileMenu = false">
    
    <div class="flex flex-col h-full">
        {{-- Logo Section --}}
        <div class="px-6 h-20 border-b border-slate-200 flex items-center justify-between bg-slate-50/70">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-white text-xs font-bold">TG</span>
                <div class="leading-tight">
                    <p class="text-sm font-semibold text-slate-900">Tobac-Go</p>
                    <p class="text-[11px] text-slate-500 uppercase tracking-wider">Admin Panel</p>
                </div>
            </a>

            <span class="hidden lg:inline-flex text-[10px] px-2 py-1 rounded-full border border-slate-300 text-slate-500 uppercase tracking-wider">v1</span>
        </div>

        {{-- Mobile Close Button --}}
        <button @click="mobileMenu = false" 
                class="lg:hidden absolute top-4 right-4 p-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition">
            <i class="ri-close-line text-2xl"></i>
        </button>

        {{-- Navigation Menu --}}
        <div class="flex-1 px-4 pb-6 mt-4">
            <div class="px-3 pb-2 mb-2 text-[11px] font-semibold text-slate-400 uppercase tracking-[0.18em]">Main Navigation</div>
            <ul class="space-y-1">
                @foreach (\App\Views\Builders\AdminSidebar::menu(user: auth()->user())->get() as $menu)
                    @php
                        $currentPath = trim(request()->path(), '/');
                        $menuPath = ltrim((string) (parse_url($menu->url, PHP_URL_PATH) ?? ''), '/');
                        $activeSubPath = null;

                        if($menu->hasSubmenu) {
                            foreach($menu->submenu as $sub) {
                                $subPath = ltrim((string) (parse_url($sub->url, PHP_URL_PATH) ?? ''), '/');
                                if($subPath !== '' && ($currentPath === $subPath || str_starts_with($currentPath, $subPath . '/'))) {
                                    if($activeSubPath === null || strlen($subPath) > strlen($activeSubPath)) {
                                        $activeSubPath = $subPath;
                                    }
                                }
                            }
                        }

                        $anySubActive = $activeSubPath !== null;
                        $isActive = $menu->hasSubmenu
                            ? $anySubActive
                            : (($menuPath !== '' && ($currentPath === $menuPath || str_starts_with($currentPath, $menuPath . '/'))) || ($menu->url === route('admin.dashboard') && request()->routeIs('admin.dashboard')));
                    @endphp

                    <li class="nav-item" x-data="{ open: {{ ($isActive || $anySubActive) ? 'true' : 'false' }} }">
                        @if($menu->hasSubmenu)
                            {{-- Parent Menu --}}
                            <a href="javascript:void(0)"
                                class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all group border
                                    {{ ($isActive || $anySubActive) ? 'bg-blue-50 text-blue-700 border-blue-100' : 'text-slate-600 border-transparent hover:text-slate-900 hover:bg-slate-100 hover:border-slate-200' }}"
                                @click="open = !open">
                                <div class="flex items-center gap-3">
                                    <i class="{{ $menu->icon ?? 'ri-circle-line' }} text-lg {{ ($isActive || $anySubActive) ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                                    <span>{{ $menu->title }}</span>
                                </div>
                                <i class="ri-arrow-down-s-line transition-transform duration-200 text-base" :class="{ 'rotate-180': open }"></i>
                            </a>

                            {{-- Submenu --}}
                            <ul class="mt-1.5 space-y-1 ml-3 border-l border-slate-200 pl-2" 
                                x-show="open" 
                                x-collapse
                                x-cloak>
                                @foreach($menu->submenu as $submenu)
                                    @php
                                        $submenuPath = ltrim((string) (parse_url($submenu->url, PHP_URL_PATH) ?? ''), '/');
                                        $isSubActive = $submenuPath !== '' && $submenuPath === $activeSubPath;
                                    @endphp
                                    <li>
                                        <a href="{{ $submenu->url }}"
                                            class="flex items-center px-3 py-2 rounded-lg text-[13px] transition
                                            {{ $isSubActive ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100' }}">
                                            <span class="mr-2 opacity-40">•</span>
                                            {{ $submenu->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            {{-- Single Menu Item --}}
                            <a href="{{ $menu->url }}" 
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all group border
                                {{ $isActive ? 'bg-blue-50 text-blue-700 border-blue-100' : 'text-slate-600 border-transparent hover:text-slate-900 hover:bg-slate-100 hover:border-slate-200' }}">
                                <i class="{{ $menu->icon ?? 'ri-circle-line' }} text-lg {{ $isActive ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
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


