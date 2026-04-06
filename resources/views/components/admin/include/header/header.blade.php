<div>
    @php
        $user = auth()->user();
    @endphp

    <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-gray-200">
        <div class="flex items-center justify-between px-6 py-4 lg:px-8">

            <!-- 🔷 Left Section -->
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-blue-600">
                    Admin Panel
                </p>
                <h1 class="mt-1 text-xl font-semibold text-gray-900">
                    {{ $title ?? 'Dashboard' }}
                </h1>
            </div>

            <!-- 🔷 Right Section -->
            <div class="flex items-center gap-4">

                <!-- 🔔 Notification (optional) -->
                <button class="relative flex items-center justify-center h-10 w-10 rounded-lg bg-gray-100 text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                    <i class="ri-notification-3-line text-lg"></i>

                    <!-- Dot -->
                    <span class="absolute top-2 right-2 h-2 w-2 rounded-full bg-blue-600"></span>
                </button>

                <!-- 👤 User Info -->
                <div class="hidden sm:flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-50 transition">

                    <!-- Avatar -->
                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-600 text-white font-semibold shadow-sm">
                        {{ strtoupper(substr($user?->name ?? 'A', 0, 1)) }}
                    </div>

                    <!-- Name -->
                    <div class="text-left">
                        <p class="text-sm font-medium text-gray-900 leading-none">
                            {{ $user?->name }}
                        </p>
                        <p class="text-xs text-gray-400">
                            Administrator
                        </p>
                    </div>
                </div>

                <!-- 🔓 Logout -->
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="flex items-center justify-center h-10 w-10 rounded-lg bg-gray-100 text-gray-600 hover:bg-red-50 hover:text-red-500 transition"
                        title="Logout"
                    >
                        <i class="ri-logout-box-r-line text-lg"></i>
                    </button>
                </form>

            </div>
        </div>
    </header>
</div>
