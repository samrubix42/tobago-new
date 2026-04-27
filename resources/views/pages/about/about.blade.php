<div class="relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-20 h-72 w-72 rounded-full blur-3xl opacity-20" style="background: radial-gradient(circle, #00c6ff, transparent 70%);"></div>
        <div class="absolute top-1/3 -right-20 h-80 w-80 rounded-full blur-3xl opacity-20" style="background: radial-gradient(circle, #6a5cff, transparent 70%);"></div>
    </div>



    <section class="relative max-w-7xl mx-auto pt-20  px-4 sm:px-6 pb-4">
        <div class="rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-7">
            <div class="flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-cyan-500/10 text-cyan-300 border border-cyan-400/20">
                    <i class="ri-user-star-line text-base"></i>
                </span>
                <p class="text-xs uppercase tracking-[0.16em] text-slate-400">Founder's Journey</p>
            </div>

            <div class="mt-5 grid grid-cols-1 lg:grid-cols-12 gap-5">
                <div class="lg:col-span-8 space-y-4 text-sm sm:text-[15px] leading-relaxed text-slate-300">
                    @foreach($founderJourney as $line)
                        <p>{{ $line }}</p>
                    @endforeach
                </div>

                <div class="lg:col-span-4 rounded-xl border border-white/10 bg-white/[0.02] p-4 h-fit">
                    <p class="text-[11px] uppercase tracking-[0.14em] text-slate-400">Our Promise</p>
                    <p class="mt-3 text-sm leading-relaxed text-slate-300">
                        Every product listed on Tobac-Go is selected to deliver reliable performance, real value, and a better session experience.
                    </p>
                    <div class="mt-4 space-y-2 text-sm">
                        <p class="rounded-lg border border-white/10 bg-white/[0.03] px-3 py-2 text-slate-200">Quality-first curation</p>
                        <p class="rounded-lg border border-white/10 bg-white/[0.03] px-3 py-2 text-slate-200">No inflated claims</p>
                        <p class="rounded-lg border border-white/10 bg-white/[0.03] px-3 py-2 text-slate-200">Support that actually helps</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <article class="rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-6">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500/10 text-blue-300 border border-blue-400/20">
                        <i class="ri-building-4-line"></i>
                    </span>
                    <h2 class="text-xl font-semibold text-white">About Tobac-Go</h2>
                </div>
                <div class="mt-4 space-y-3 text-sm text-slate-300 leading-relaxed">
                    @foreach($aboutIntro as $line)
                        <p>{{ $line }}</p>
                    @endforeach
                </div>
            </article>

            <article class="rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-6">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-500/10 text-indigo-300 border border-indigo-400/20">
                        <i class="ri-store-3-line"></i>
                    </span>
                    <h2 class="text-xl font-semibold text-white">What We Do</h2>
                </div>
                <div class="mt-4 space-y-3 text-sm text-slate-300 leading-relaxed">
                    @foreach($whatWeDo as $line)
                        <p>{{ $line }}</p>
                    @endforeach
                </div>
            </article>

            <article class="rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-6">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500/10 text-emerald-300 border border-emerald-400/20">
                        <i class="ri-shield-check-line"></i>
                    </span>
                    <h2 class="text-xl font-semibold text-white">How We Think About Quality</h2>
                </div>
                <div class="mt-4 space-y-3 text-sm text-slate-300 leading-relaxed">
                    @foreach($quality as $line)
                        <p>{{ $line }}</p>
                    @endforeach
                </div>
            </article>

            <article class="rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-6">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-fuchsia-500/10 text-fuchsia-300 border border-fuchsia-400/20">
                        <i class="ri-heart-3-line"></i>
                    </span>
                    <h2 class="text-xl font-semibold text-white">Our Approach To Customers</h2>
                </div>
                <div class="mt-4 space-y-3 text-sm text-slate-300 leading-relaxed">
                    @foreach($customerApproach as $line)
                        <p>{{ $line }}</p>
                    @endforeach
                </div>
            </article>
        </div>
    </section>

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pb-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <article class="lg:col-span-5 rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-6">
                <h2 class="text-xl font-semibold text-white">Who This Is For</h2>
                <p class="mt-3 text-sm text-slate-300 leading-relaxed">Whether you are just getting started or already experienced, Tobac-Go is designed to keep your buying experience simple and clear.</p>
                <div class="mt-4 flex flex-wrap gap-2.5">
                    @foreach($forWho as $item)
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.03] px-3 py-2 text-xs text-slate-200">
                            <i class="ri-sparkling-2-line text-cyan-300"></i>
                            {{ $item }}
                        </span>
                    @endforeach
                </div>
            </article>

            <article class="lg:col-span-7 rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-6">
                <h2 class="text-xl font-semibold text-white">Looking Ahead</h2>
                <div class="mt-4 space-y-3 text-sm text-slate-300 leading-relaxed">
                    @foreach($lookingAhead as $line)
                        <p>{{ $line }}</p>
                    @endforeach
                </div>
                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('products') }}" wire:navigate class="inline-flex items-center justify-center gap-2 rounded-full border border-white/20 bg-white/[0.05] px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.14em] text-white/90 transition hover:border-cyan-300/40 hover:bg-white/[0.09]">
                        Explore Products
                        <i class="ri-arrow-right-line"></i>
                    </a>
                    <a href="{{ route('location.noida') }}" wire:navigate class="inline-flex items-center justify-center gap-2 rounded-full border border-white/20 px-5 py-3 text-[11px] font-semibold uppercase tracking-[0.14em] text-white/75 transition hover:text-white hover:border-white/40">
                        Visit Our Store
                        <i class="ri-map-pin-line"></i>
                    </a>
                </div>
            </article>
        </div>
    </section>
</div>
