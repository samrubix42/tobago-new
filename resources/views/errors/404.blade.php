<x-errors.minimal title="404 | Page Not Found">
    <section class="grid w-full items-center gap-10 lg:grid-cols-[1.1fr_0.9fr]">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 rounded-full border border-cyan-400/20 bg-cyan-400/10 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.28em] text-cyan-200">
                <i class="ri-error-warning-line text-sm"></i>
                Page Not Found
            </div>

            <h1 class="mt-6 text-5xl font-black tracking-[-0.04em] text-white sm:text-6xl lg:text-7xl">
                Lost in the smoke?
            </h1>

            <p class="mt-5 max-w-xl text-base leading-7 text-white/65 sm:text-lg">
                The page you tried to open is missing, moved, or never existed. Let’s get you back to the hookahs, accessories, and latest arrivals.
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-6 py-3.5 text-sm font-semibold text-black transition hover:bg-white/90">
                    <i class="ri-home-5-line text-base"></i>
                    Back to Home
                </a>
                <a href="{{ route('products') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/12 bg-white/5 px-6 py-3.5 text-sm font-semibold text-white transition hover:border-white/20 hover:bg-white/8">
                    <i class="ri-store-2-line text-base"></i>
                    Browse Products
                </a>
            </div>

            <div class="mt-8 flex flex-wrap gap-3 text-xs text-white/45">
                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">Premium Hookahs</span>
                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">Smoking Accessories</span>
                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5">Fast Delivery</span>
            </div>
        </div>

        <div class="relative">
            <div class="absolute inset-0 rounded-[2rem] bg-cyan-400/10 blur-3xl"></div>
            <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/5 p-6 shadow-[0_30px_90px_rgba(0,0,0,0.45)] backdrop-blur-xl sm:p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/40">Error Code</p>
                        <p class="mt-3 text-7xl font-black leading-none text-white sm:text-8xl">404</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/10 bg-white/8 text-cyan-200">
                        <i class="ri-compass-3-line text-2xl"></i>
                    </div>
                </div>

                <div class="mt-8 space-y-3">
                    <div class="rounded-2xl border border-white/10 bg-[#0b0d0f]/80 p-4">
                        <p class="text-sm font-semibold text-white">Quick recovery</p>
                        <p class="mt-1 text-sm leading-6 text-white/55">Check the URL, return to the homepage, or jump straight into the store collection.</p>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <a href="{{ route('home') }}" class="rounded-2xl border border-white/10 bg-white/5 p-4 transition hover:border-white/20 hover:bg-white/8">
                            <p class="text-xs uppercase tracking-[0.22em] text-white/35">Home</p>
                            <p class="mt-2 text-sm font-semibold text-white">Return to storefront</p>
                        </a>
                        <a href="{{ route('products') }}" class="rounded-2xl border border-white/10 bg-white/5 p-4 transition hover:border-white/20 hover:bg-white/8">
                            <p class="text-xs uppercase tracking-[0.22em] text-white/35">Shop</p>
                            <p class="mt-2 text-sm font-semibold text-white">Explore the catalog</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-errors.minimal>
