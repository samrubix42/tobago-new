<div class="w-full">
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="grid lg:grid-cols-5">
            <aside class="hidden lg:flex lg:col-span-2 flex-col justify-between bg-slate-50 p-8 border-r border-slate-200">
                <div>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-900 text-white text-xs">TG</span>
                        Tobac-Go Admin
                    </a>

                    <h2 class="mt-8 text-2xl font-semibold tracking-tight text-slate-900">Welcome back</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Use your admin account to manage products, orders, and storefront content.</p>
                </div>

                <div class="space-y-3 text-sm text-slate-600">
                    <div class="flex items-center gap-2"><i class="ri-shield-check-line text-slate-500"></i> Secure admin access</div>
                    <div class="flex items-center gap-2"><i class="ri-settings-3-line text-slate-500"></i> Product and catalog controls</div>
                    <div class="flex items-center gap-2"><i class="ri-line-chart-line text-slate-500"></i> Sales and performance dashboard</div>
                </div>
            </aside>

            <section class="lg:col-span-3 p-6 sm:p-8 lg:p-10">
                <div class="mb-8">
                    <div class="flex items-center justify-between gap-3">
                        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Admin Login</h1>
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-slate-800 transition-colors">
                            <i class="ri-arrow-left-line"></i> Back to store
                        </a>
                    </div>
                    <p class="mt-2 text-sm text-slate-500">Enter your credentials to continue.</p>
                </div>

                <form wire:submit="login" class="space-y-5">
                    <div>
                        <label for="admin-email" class="block text-sm font-medium text-slate-700">Email address</label>
                        <div class="relative mt-1.5">
                            <i class="ri-mail-line absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input
                                id="admin-email"
                                type="email"
                                wire:model.live="email"
                                placeholder="you@company.com"
                                autocomplete="email"
                                class="w-full rounded-xl border border-slate-300 bg-white pl-10 pr-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
                            >
                        </div>
                        @error('email')
                            <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{ show: false }">
                        <label for="admin-password" class="block text-sm font-medium text-slate-700">Password</label>
                        <div class="relative mt-1.5">
                            <i class="ri-lock-line absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input
                                id="admin-password"
                                :type="show ? 'text' : 'password'"
                                wire:model.live="password"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                class="w-full rounded-xl border border-slate-300 bg-white pl-10 pr-10 py-2.5 text-sm text-slate-900 outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200"
                            >
                            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 transition-colors">
                                <i :class="show ? 'ri-eye-off-line' : 'ri-eye-line'"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" wire:model="remember" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-300">
                            Remember me
                        </label>
                    </div>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:opacity-70"
                    >
                        <svg wire:loading wire:target="login" class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="rgba(255,255,255,0.4)" stroke-width="3"></circle>
                            <path d="M12 2a10 10 0 0 1 10 10" stroke="white" stroke-width="3" stroke-linecap="round"></path>
                        </svg>
                        <span wire:loading.remove wire:target="login">Sign In</span>
                        <span wire:loading wire:target="login">Signing in...</span>
                    </button>
                </form>

                <p class="mt-8 text-center text-xs text-slate-400">© {{ date('Y') }} Tobac-Go</p>
            </section>
        </div>
    </div>
</div>

