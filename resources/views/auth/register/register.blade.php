<div>
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-white/70 hover:text-white transition">
            <i class="ri-arrow-left-line"></i> Back to store
        </a>

        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <img src="{{ asset('logo.webp') }}" class="h-9" alt="Tobac-Go">
        </a>
    </div>

    <div class="text-center mb-8">
        <div class="mx-auto h-14 w-14 rounded-2xl border border-white/10 bg-white/5 backdrop-blur flex items-center justify-center shadow-[0_0_0_1px_rgba(255,255,255,0.04)]">
            <i class="ri-user-add-line text-2xl text-white/80"></i>
        </div>

        <h2 class="mt-4 text-2xl font-semibold text-white">
            Create your account
        </h2>

        <p class="text-sm text-white/50 mt-1">
            Sign up to start shopping
        </p>
    </div>

    <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-[0_30px_80px_rgba(0,0,0,0.55)] p-6">
        <form wire:submit="register" class="space-y-5">

            <div>
                <label class="text-sm font-medium text-white/80">Name</label>
                <div class="relative mt-1">
                    <i class="ri-user-3-line absolute left-3 top-3 text-white/40"></i>
                    <input
                        type="text"
                        wire:model.live="name"
                        placeholder="Enter your name"
                        class="w-full pl-10 pr-3 py-2.5 rounded-xl bg-black/30 border border-white/10 text-white placeholder-white/30 focus:ring-2 focus:ring-cyan-400/20 focus:border-white/20 outline-none transition"
                    >
                </div>
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-white/80">Email</label>
                <div class="relative mt-1">
                    <i class="ri-mail-line absolute left-3 top-3 text-white/40"></i>
                    <input
                        type="email"
                        wire:model.live="email"
                        placeholder="Enter your email"
                        class="w-full pl-10 pr-3 py-2.5 rounded-xl bg-black/30 border border-white/10 text-white placeholder-white/30 focus:ring-2 focus:ring-cyan-400/20 focus:border-white/20 outline-none transition"
                    >
                </div>
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div x-data="{ show: false }">
                <label class="text-sm font-medium text-white/80">Password</label>

                <div class="relative mt-1">
                    <i class="ri-lock-line absolute left-3 top-3 text-white/40"></i>

                    <input
                        :type="show ? 'text' : 'password'"
                        wire:model.live="password"
                        placeholder="Create a password"
                        class="w-full pl-10 pr-10 py-2.5 rounded-xl bg-black/30 border border-white/10 text-white placeholder-white/30 focus:ring-2 focus:ring-cyan-400/20 focus:border-white/20 outline-none transition"
                    >

                    <button type="button"
                        @click="show = !show"
                        class="absolute right-3 top-2.5 text-white/40 hover:text-white/70 transition">
                        <i :class="show ? 'ri-eye-off-line' : 'ri-eye-line'"></i>
                    </button>
                </div>

                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-white/80">Confirm Password</label>
                <div class="relative mt-1">
                    <i class="ri-lock-2-line absolute left-3 top-3 text-white/40"></i>
                    <input
                        type="password"
                        wire:model.live="password_confirmation"
                        placeholder="Re-enter password"
                        class="w-full pl-10 pr-3 py-2.5 rounded-xl bg-black/30 border border-white/10 text-white placeholder-white/30 focus:ring-2 focus:ring-cyan-400/20 focus:border-white/20 outline-none transition"
                    >
                </div>
            </div>

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl font-semibold bg-white text-black transition hover:bg-white/90"
            >
                <svg wire:loading wire:target="register"
                    class="animate-spin h-4 w-4"
                    viewBox="0 0 24 24"></svg>

                <span wire:loading.remove wire:target="register">Create account</span>
                <span wire:loading wire:target="register">Creating...</span>
            </button>

            <p class="text-center text-sm text-white/60">
                Already have an account?
                <a href="{{ route('login') }}" class="text-white hover:underline">
                    Login
                </a>
            </p>
        </form>
    </div>

    <p class="text-center text-xs text-white/40 mt-6">
        © {{ date('Y') }} Tobac-Go
    </p>
</div>
