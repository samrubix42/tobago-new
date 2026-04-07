<header class="sticky top-0 z-40 w-full bg-white/80 backdrop-blur-md border-b border-gray-100 h-16 flex items-center justify-between px-4 lg:px-8">
    {{-- Left Side: Mobile Toggle & Title --}}
    <div class="flex items-center gap-4">
        <button @click="mobileMenu = true" 
                class="lg:hidden p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
            <i class="ri-menu-2-line text-2xl"></i>
        </button>
        
        <div class="flex items-center gap-2">
            <h1 class="text-lg font-bold text-gray-900 tracking-tight">
                {{ $title ?? 'Dashboard' }}
            </h1>
        </div>
    </div>

    {{-- Right Side: Actions & User --}}
    <div class="flex items-center gap-3">
        {{-- Notifications --}}
        <button class="relative p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition">
            <i class="ri-notification-3-line text-xl"></i>
            <span class="absolute top-2 right-2.5 w-2 h-2 bg-blue-500 border-2 border-white rounded-full"></span>
        </button>

        <div class="h-6 w-px bg-gray-200 mx-2 hidden sm:block"></div>

        {{-- User Dropdown --}}
        <div class="relative" x-data="{ userOpen: false }" @click.away="userOpen = false">
            <button @click="userOpen = !userOpen" 
                    class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-gray-50 transition border border-transparent hover:border-gray-100">
                <div class="flex flex-col items-end text-right hidden sm:flex">
                    <span class="text-sm font-bold text-gray-900 leading-none">{{ auth()->user()->name }}</span>
                    <span class="text-[11px] font-medium text-gray-400 mt-1 uppercase tracking-wider">Administrator</span>
                </div>
                <div class="h-10 w-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-lg shadow-blue-500/20">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <i class="ri-arrow-down-s-line text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': userOpen }"></i>
            </button>

            {{-- Dropdown Menu --}}
            <div x-show="userOpen" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50"
                 x-cloak>
                
                <div class="px-4 py-3 border-b border-gray-50 mb-1 sm:hidden">
                    <p class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400">Administrator</p>
                </div>

                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition">
                    <i class="ri-user-line text-lg"></i>
                    Profile
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition">
                    <i class="ri-settings-3-line text-lg"></i>
                    Account Settings
                </a>
                
                <div class="h-px bg-gray-50 my-1"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 w-full text-left transition">
                        <i class="ri-logout-box-r-line text-lg"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

