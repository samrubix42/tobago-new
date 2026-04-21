<x-errors.minimal title="503 | Under Maintenance">
    <section class="grid w-full items-center gap-10 lg:grid-cols-[1.05fr_0.95fr]">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 rounded-full border border-amber-300/20 bg-amber-300/10 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.28em] text-amber-100">
                <i class="ri-tools-line text-sm"></i>
                Maintenance Mode
            </div>

            <h1 class="mt-6 text-5xl font-black tracking-[-0.04em] text-white sm:text-6xl lg:text-7xl">
                We’re tuning the lounge.
            </h1>

            <p class="mt-5 max-w-xl text-base leading-7 text-white/65 sm:text-lg">
                Tobac-Go is temporarily offline while we improve the experience. We’ll be back shortly with a smoother, faster storefront.
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-6 py-3.5 text-sm font-semibold text-black transition hover:bg-white/90">
                    <i class="ri-refresh-line text-base"></i>
                    Try Again
                </a>
                <a href="mailto:support@tobac-go.com" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/12 bg-white/5 px-6 py-3.5 text-sm font-semibold text-white transition hover:border-white/20 hover:bg-white/8">
                    <i class="ri-mail-send-line text-base"></i>
                    Contact Support
                </a>
            </div>

            <p class="mt-6 text-sm text-white/45">
                Thanks for your patience while we work behind the scenes.
            </p>
        </div>

        <div class="relative">
            <div class="absolute inset-0 rounded-[2rem] bg-amber-200/10 blur-3xl"></div>
            <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/5 p-6 shadow-[0_30px_90px_rgba(0,0,0,0.45)] backdrop-blur-xl sm:p-8">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-white/40">Status</p>
                        <p class="mt-3 text-6xl font-black leading-none text-white sm:text-7xl">503</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/10 bg-white/8 text-amber-100">
                        <i class="ri-hammer-line text-2xl"></i>
                    </div>
                </div>

                <div class="mt-8 space-y-3">
                    <div class="rounded-2xl border border-white/10 bg-[#0b0d0f]/80 p-4">
                        <p class="text-sm font-semibold text-white">What’s happening</p>
                        <p class="mt-1 text-sm leading-6 text-white/55">Routine updates, polish, and performance work are in progress right now.</p>
                    </div>
                    <div class="rounded-2xl border border-dashed border-white/10 bg-transparent p-4">
                        <p class="text-xs uppercase tracking-[0.22em] text-white/35">Recommended</p>
                        <p class="mt-2 text-sm font-semibold text-white">Please check back in a few minutes.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-errors.minimal>
