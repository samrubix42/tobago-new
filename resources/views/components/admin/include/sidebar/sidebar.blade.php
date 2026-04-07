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
    <aside class="fixed inset-y-0 left-0 z-50 flex flex-col w-72 lg:w-64 bg-white border-r border-gray-200 transition-transform duration-300 transform lg:translate-x-0"
           :class="mobileMenu ? 'translate-x-0' : '-translate-x-full'">
        
        <div class="flex flex-col h-full">

            <!-- 🔷 Logo Section -->
            <div class="flex items-center justify-between px-6 h-16 lg:h-20 border-b border-gray-100">
                <a href="{{ route('home') }}" class="flex-shrink-0">
                    <img src="{{ asset('logo.webp') }}" class="h-8 w-auto" alt="Logo">
                </a>

                <!-- Close Mobile Menu -->
                <button @click="mobileMenu = false" 
                        class="lg:hidden p-2 rounded-xl text-gray-400 hover:text-black hover:bg-gray-100 transition">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <!-- 🔷 Menu -->
            <div class="flex-1 px-4 py-6 overflow-y-auto custom-scrollbar">

                <nav class="space-y-1.5">

                    @foreach (\App\Views\Builders\AdminSidebar::menu(user: auth()->user())->get() as $menu)

                        @php
                            $isActive = url()->current() === $menu->url;
                        @endphp

                        <a href="{{ $menu->url }}"
                           class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition-all duration-200
                           {{ $isActive 
                                ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' 
                                : 'text-gray-500 hover:bg-blue-50 hover:text-blue-600' }}">

                            <!-- Icon -->
                            <div class="flex items-center justify-center h-8 w-8 rounded-lg transition
                                {{ $isActive 
                                    ? 'bg-white/20 text-white' 
                                    : 'bg-blue-50 text-blue-400 group-hover:bg-white group-hover:text-blue-600' }}">
                                
                                <i class="{{ $menu->icon ?? 'ri-layout-grid-line' }} text-lg"></i>
                            </div>

                            <!-- Title -->
                            <span class="font-semibold">
                                {{ $menu->title }}
                            </span>
                        </a>

                    @endforeach

                </nav>
            </div>

            <!-- 🔷 Bottom Section / User Profile -->
            <div class="p-4 border-t border-blue-50 bg-blue-50/20">
                <div class="flex items-center gap-3 p-2 rounded-2xl bg-white border border-blue-100 shadow-sm">

                    <!-- User Avatar -->
                    <div class="h-9 w-9 rounded-xl bg-blue-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <!-- User Info -->
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-gray-900 truncate">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-[10px] font-semibold text-blue-600 uppercase">
                            Admin
                        </p>
                    </div>

                    <!-- Logout icon specifically for sidebar -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="flex items-center justify-center h-8 w-8 rounded-lg text-blue-400 hover:text-red-500 hover:bg-red-50 transition">
                            <i class="ri-logout-box-r-line text-lg"></i>
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </aside>
</div>