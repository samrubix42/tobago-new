<div class="w-full max-w-sm mx-auto">

    {{-- ── Top bar ── --}}
    <div class="flex items-center justify-between mb-10">
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-1.5 text-xs text-white/40 hover:text-white/80 transition-colors">
            <i class="ri-arrow-left-line"></i> Back to store
        </a>
        <a href="{{ route('home') }}">
            <img src="{{ asset('logo.webp') }}" class="h-8" alt="Tobac-Go">
        </a>
    </div>

    {{-- ── Heading ── --}}
    <div class="mb-8">
        <h1 class="text-2xl font-semibold tracking-tight text-white mb-1">Welcome back</h1>
        <p class="text-sm text-white/40">Sign in to continue shopping at Tobac-Go</p>
    </div>

    {{-- ── Google OAuth ── --}}
    <a href="{{ route('google.redirect') }}"
       class="flex items-center justify-center gap-3 w-full py-2.5 px-4 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 text-white/80 hover:text-white text-sm font-medium transition-all">
        <svg width="17" height="17" viewBox="0 0 48 48" fill="none">
            <path d="M47.532 24.552c0-1.636-.138-3.2-.395-4.697H24v9.009h13.194c-.572 3.023-2.286 5.588-4.876 7.29v6.054h7.894c4.618-4.254 7.32-10.52 7.32-17.656z" fill="#4285F4"/>
            <path d="M24 48c6.48 0 11.916-2.148 15.888-5.838l-7.894-6.054c-2.148 1.44-4.896 2.292-7.994 2.292-6.15 0-11.358-4.152-13.218-9.732H2.616v6.252C6.576 42.696 14.736 48 24 48z" fill="#34A853"/>
            <path d="M10.782 28.668A14.4 14.4 0 0 1 9.9 24c0-1.62.282-3.192.882-4.668v-6.252H2.616A23.94 23.94 0 0 0 0 24c0 3.864.924 7.524 2.616 10.92l8.166-6.252z" fill="#FBBC05"/>
            <path d="M24 9.54c3.462 0 6.564 1.188 9.006 3.528l6.744-6.744C35.916 2.394 30.48 0 24 0 14.736 0 6.576 5.304 2.616 13.08l8.166 6.252C12.642 13.692 17.85 9.54 24 9.54z" fill="#EA4335"/>
        </svg>
        Continue with Google
    </a>

    {{-- ── Divider ── --}}
    <div class="flex items-center gap-3 my-6">
        <div class="flex-1 h-px bg-white/8"></div>
        <span class="text-xs text-white/30">or with email</span>
        <div class="flex-1 h-px bg-white/8"></div>
    </div>

    {{-- ── Form ── --}}
    <form wire:submit="login" class="space-y-4">

        {{-- Email --}}
        <div>
            <label class="block text-xs font-medium text-white/60 mb-1.5">Email address</label>
            <div class="relative">
                <i class="ri-mail-line absolute left-3 top-1/2 -translate-y-1/2 text-white/30 text-sm"></i>
                <input
                    type="email"
                    wire:model.live="email"
                    id="login-email"
                    placeholder="you@example.com"
                    autocomplete="email"
                    class="w-full pl-9 pr-3 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white text-sm placeholder-white/25 focus:outline-none focus:border-white/30 focus:bg-white/8 transition-all"
                >
            </div>
            @error('email')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div x-data="{ show: false }">
            <div class="flex items-center justify-between mb-1.5">
                <label class="text-xs font-medium text-white/60">Password</label>
                <a href="#" class="text-xs text-white/35 hover:text-white/70 transition-colors">Forgot password?</a>
            </div>
            <div class="relative">
                <i class="ri-lock-line absolute left-3 top-1/2 -translate-y-1/2 text-white/30 text-sm"></i>
                <input
                    :type="show ? 'text' : 'password'"
                    wire:model.live="password"
                    id="login-password"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    class="w-full pl-9 pr-10 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white text-sm placeholder-white/25 focus:outline-none focus:border-white/30 focus:bg-white/8 transition-all"
                >
                <button type="button" @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-white/30 hover:text-white/70 transition-colors">
                    <i :class="show ? 'ri-eye-off-line' : 'ri-eye-line'" class="text-sm"></i>
                </button>
            </div>
            @error('password')
                <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember me --}}
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" wire:model="remember" id="login-remember"
                   class="w-3.5 h-3.5 rounded border-white/20 bg-white/5 accent-white cursor-pointer">
            <span class="text-xs text-white/40">Keep me signed in</span>
        </label>

        {{-- Submit --}}
        <button
            type="submit"
            wire:loading.attr="disabled"
            id="login-submit"
            class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-white text-black text-sm font-semibold hover:bg-white/90 active:scale-[0.98] transition-all disabled:opacity-60"
        >
            <svg wire:loading wire:target="login"
                 class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="10" stroke="rgba(0,0,0,0.25)" stroke-width="3"/>
                <path d="M12 2a10 10 0 0 1 10 10" stroke="#000" stroke-width="3" stroke-linecap="round"/>
            </svg>
            <span wire:loading.remove wire:target="login">Sign In</span>
            <span wire:loading wire:target="login">Signing in…</span>
        </button>

    </form>

    {{-- ── Footer ── --}}
    <p class="text-center text-sm text-white/35 mt-8">
        New here?
        <a href="{{ route('register') }}" class="text-white/70 hover:text-white font-medium transition-colors">
            Create an account
        </a>
    </p>

    <p class="text-center text-xs text-white/20 mt-6">
        © {{ date('Y') }} Tobac-Go
    </p>

</div>
