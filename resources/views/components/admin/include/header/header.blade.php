<div>
    @php
        $user = auth()->user();
    @endphp

    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="flex items-center justify-between px-4 lg:px-6 h-14 lg:h-16">

            <!-- 🔷 Left Section -->
            <div class="flex items-center gap-3">
                <!-- Mobile Toggle -->
                <button @click="mobileMenu = true" 
                        class="lg:hidden flex items-center justify-center h-9 w-9 rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition">
                    <i class="ri-menu-2-line text-xl"></i>
                </button>

                <div class="hidden sm:block">
                    <h1 class="text-base lg:text-lg font-semibold tracking-tight text-slate-900">
                        {{ $title ?? 'Dashboard' }}
                    </h1>
                </div>
            </div>

            <!-- 🔷 Right Section -->
            <div class="flex items-center gap-3">

                <!-- 🔔 Notification -->
                <button class="relative flex items-center justify-center h-9 w-9 lg:h-10 lg:w-10 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition">
                    <i class="ri-notification-3-line text-lg"></i>
                    <span class="absolute top-2 right-2 h-2 w-2 rounded-full bg-blue-600 ring-2 ring-white"></span>
                </button>

                <!-- 👤 User Info -->
                <div class="flex items-center gap-2.5 pl-2 lg:pl-3 pr-1 py-1 rounded-xl bg-white border border-slate-200">
                    <div class="hidden md:block text-right pr-1">
                        <p class="text-xs font-semibold text-slate-900 leading-none">
                            {{ $user?->name }}
                        </p>
                        <p class="text-[10px] uppercase font-medium text-slate-500 mt-1">
                            Administrator
                        </p>
                    </div>

                    <!-- Avatar -->
                    <div class="flex items-center justify-center h-8 w-8 lg:h-9 lg:w-9 rounded-lg bg-blue-600 text-white font-bold text-xs">
                        {{ strtoupper(substr($user?->name ?? 'A', 0, 1)) }}
                    </div>
                </div>

                <div class="h-6 w-px bg-slate-200 mx-1 hidden lg:block"></div>

                <!-- 🔓 Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="flex items-center justify-center h-9 w-9 lg:h-10 lg:w-10 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-rose-600 hover:text-white hover:border-rose-600 transition"
                        title="Logout"
                    >
                        <i class="ri-logout-box-r-line text-lg"></i>
                    </button>
                </form>

            </div>
        </div>
    </header>
</div>
