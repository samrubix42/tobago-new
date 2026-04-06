<div>
    <aside class="hidden lg:flex fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200">
        <div class="flex flex-col w-full h-full">

            <!-- 🔷 Logo Section -->
            <div class="flex items-center gap-3 px-5 py-5 border-b border-gray-100">
                
                <!-- Logo Icon -->
                <div class="flex items-center justify-center h-10 w-auto px-6 py-6 text-white">
                    <img src="{{asset('logo.webp')}}" alt="">
                </div>

             
            </div>

            <!-- 🔷 Menu -->
            <div class="flex-1 px-3 py-6 overflow-y-auto">

                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Menu
                </p>

                <nav class="mt-4 space-y-1">

                    @foreach (\App\Views\Builders\AdminSidebar::menu(user: auth()->user())->get() as $menu)

                        @php
                            $isActive = url()->current() === $menu->url;
                        @endphp

                        <a href="{{ $menu->url }}"
                           class="group flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm transition-all duration-200
                           {{ $isActive 
                                ? 'bg-blue-600 text-white shadow-sm' 
                                : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">

                            <!-- Icon -->
                            <div class="flex items-center justify-center h-9 w-9 rounded-lg transition
                                {{ $isActive 
                                    ? 'bg-white/20 text-white' 
                                    : 'bg-gray-100 text-gray-400 group-hover:bg-blue-100 group-hover:text-blue-600' }}">
                                
                                <i class="{{ $menu->icon ?? 'ri-layout-grid-line' }} text-lg"></i>
                            </div>

                            <!-- Title -->
                            <span class="font-medium">
                                {{ $menu->title }}
                            </span>
                        </a>

                    @endforeach

                </nav>
            </div>

            <!-- 🔷 Bottom Section -->
            <div class="p-4 border-t border-gray-100">
                <div class="flex items-center gap-3">

                    <!-- User Avatar -->
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <!-- User Info -->
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-xs text-gray-400">
                            Admin
                        </p>
                    </div>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-gray-400 hover:text-red-500 transition">
                            <i class="ri-logout-box-r-line text-lg"></i>
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </aside>
</div>