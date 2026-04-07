<div>
    @php
        $user = auth()->user();
    @endphp

    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-blue-100/50">
        <div class="flex items-center justify-between px-4 lg:px-8 h-16 lg:h-20">

            <!-- 🔷 Left Section -->
            <div class="flex items-center gap-3">
                <!-- Mobile Toggle -->
                <button @click="mobileMenu = true" 
                        class="lg:hidden flex items-center justify-center p-2 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 hover:bg-blue-600 hover:text-white transition shadow-sm">
                    <i class="ri-menu-2-line text-xl"></i>
                </button>

                <div class="hidden sm:block">
                    <h1 class="text-lg lg:text-xl font-bold tracking-tight text-gray-900">
                        {{ $title ?? 'Dashboard' }}
                    </h1>
                </div>
            </div>

            <!-- 🔷 Right Section -->
            <div class="flex items-center gap-3">

                <!-- 🔔 Notification -->
                <button class="relative flex items-center justify-center h-10 w-10 lg:h-11 lg:w-11 rounded-xl bg-white border border-blue-100 text-blue-600 hover:bg-blue-50 transition shadow-sm">
                    <i class="ri-notification-3-line text-lg"></i>
                    <span class="absolute top-2.5 right-2.5 h-2 w-2 rounded-full bg-blue-600 border-2 border-white"></span>
                </button>

                <!-- 👤 User Info -->
                <div class="flex items-center gap-2.5 pl-2 lg:pl-3 pr-1 py-1 rounded-2xl bg-white border border-blue-100 shadow-sm">
                    <div class="hidden md:block text-right pr-1">
                        <p class="text-xs font-bold text-gray-900 leading-none">
                            {{ $user?->name }}
                        </p>
                        <p class="text-[10px] uppercase font-semibold text-blue-600 mt-1">
                            Administrator
                        </p>
                    </div>

                    <!-- Avatar -->
                    <div class="flex items-center justify-center h-8 w-8 lg:h-9 lg:w-9 rounded-xl bg-blue-600 text-white font-bold text-xs shadow-md">
                        {{ strtoupper(substr($user?->name ?? 'A', 0, 1)) }}
                    </div>
                </div>

                <div class="h-6 w-px bg-blue-100 mx-1 hidden lg:block"></div>

                <!-- 🔓 Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="flex items-center justify-center h-10 w-10 lg:h-11 lg:w-11 rounded-xl bg-blue-50 border border-blue-100 text-blue-600 hover:bg-red-600 hover:text-white hover:border-red-600 transition shadow-sm"
                        title="Logout"
                    >
                        <i class="ri-logout-box-r-line text-lg"></i>
                    </button>
                </form>

            </div>
        </div>
    </header>
</div>
