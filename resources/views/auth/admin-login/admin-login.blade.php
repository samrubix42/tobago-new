<div>
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-xl bg-blue-600 text-white text-xl font-bold shadow-md">
                A
            </div>

            <h2 class="mt-4 text-2xl font-semibold text-gray-900">
                Admin Login
            </h2>

            <p class="text-sm text-gray-400 mt-1">
                Sign in to manage Tobac-Go
            </p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">

            <form wire:submit="login" class="space-y-5">

                <div>
                    <label class="text-sm font-medium text-gray-700">Email</label>
                    <div class="relative mt-1">
                        <i class="ri-mail-line absolute left-3 top-3 text-gray-400"></i>
                        <input
                            type="email"
                            wire:model.live="email"
                            placeholder="Enter admin email"
                            class="w-full pl-10 pr-3 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none"
                        >
                    </div>
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ show: false }">
                    <label class="text-sm font-medium text-gray-700">Password</label>

                    <div class="relative mt-1">
                        <i class="ri-lock-line absolute left-3 top-3 text-gray-400"></i>

                        <input
                            :type="show ? 'text' : 'password'"
                            wire:model.live="password"
                            placeholder="Enter your password"
                            class="w-full pl-10 pr-10 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none"
                        >

                        <button type="button"
                            @click="show = !show"
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <i :class="show ? 'ri-eye-off-line' : 'ri-eye-line'"></i>
                        </button>
                    </div>

                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-600">
                        <input type="checkbox" wire:model="remember"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        Remember me
                    </label>

                    <a href="{{ route('home') }}" class="text-blue-600 hover:underline">
                        Back to store
                    </a>
                </div>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full flex items-center justify-center gap-2 bg-blue-600 text-white py-2.5 rounded-lg font-medium hover:bg-blue-700 transition"
                >
                    <svg wire:loading wire:target="login"
                        class="animate-spin h-4 w-4"
                        viewBox="0 0 24 24"></svg>

                    <span wire:loading.remove wire:target="login">Login</span>
                    <span wire:loading wire:target="login">Logging in...</span>
                </button>

            </form>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            © {{ date('Y') }} Tobac-Go
        </p>
    </div>
</div>

