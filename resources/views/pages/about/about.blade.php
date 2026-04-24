
<div class="relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-20 h-72 w-72 rounded-full blur-3xl opacity-20" style="background: radial-gradient(circle, #00c6ff, transparent 70%);"></div>
        <div class="absolute top-1/3 -right-20 h-80 w-80 rounded-full blur-3xl opacity-20" style="background: radial-gradient(circle, #6a5cff, transparent 70%);"></div>
    </div>

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-14">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            <article class="lg:col-span-7 rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-7">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-subtle bg-white/5 text-[11px] tracking-[0.14em] uppercase text-white/70">
                    <span class="h-2 w-2 rounded-full bg-cyan-400"></span>
                    About Tobac-Go
                </div>

                <h1 class="mt-5 text-3xl sm:text-5xl font-semibold leading-[1.1] text-white">
                    Founder-Led. <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 via-blue-300 to-indigo-300">Session-Focused.</span> Built For Real Smokers.
                </h1>

                <p class="mt-4 text-sm sm:text-base text-slate-300 leading-relaxed">
                    Tobac-Go is built from personal experience, not trend-chasing. We focus on quality products, honest information, and a smoother buying journey.
                </p>

                <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    <div class="rounded-xl border border-white/10 bg-white/[0.03] px-3 py-2.5">
                        <p class="text-[11px] uppercase tracking-[0.14em] text-slate-400">Authenticity</p>
                        <p class="mt-1 text-sm text-white font-medium">100% Genuine</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-white/[0.03] px-3 py-2.5">
                        <p class="text-[11px] uppercase tracking-[0.14em] text-slate-400">Selection</p>
                        <p class="mt-1 text-sm text-white font-medium">Curated Range</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-white/[0.03] px-3 py-2.5">
                        <p class="text-[11px] uppercase tracking-[0.14em] text-slate-400">Support</p>
                        <p class="mt-1 text-sm text-white font-medium">Real People</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-white/[0.03] px-3 py-2.5">
                        <p class="text-[11px] uppercase tracking-[0.14em] text-slate-400">Experience</p>
                        <p class="mt-1 text-sm text-white font-medium">Smooth Sessions</p>
                    </div>
                </div>
            </article>

            <aside class="lg:col-span-5 rounded-2xl border border-subtle bg-[#0b0d0f] p-3 sm:p-4">
                <div class="grid grid-cols-2 gap-3 h-full">
                    <div class="rounded-xl overflow-hidden border border-white/10 min-h-[140px] sm:min-h-[170px]">
                        <img src="{{ asset('images/1 6.webp') }}" alt="Tobac-Go store showcase" class="h-full w-full object-cover">
                    </div>
                    <div class="rounded-xl overflow-hidden border border-white/10 min-h-[140px] sm:min-h-[170px]">
                        <img src="{{ asset('images/1 7.webp') }}" alt="Tobac-Go product display" class="h-full w-full object-cover">
                    </div>
                    <div class="col-span-2 rounded-xl overflow-hidden border border-white/10 min-h-[190px] sm:min-h-[230px]">
                        <img src="{{ asset('images/tobac-go-interior.webp') }}" alt="Tobac-Go interior" class="h-full w-full object-cover">
                    </div>
                </div>
            </aside>
        </div>
    </section>

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pb-4">
        <div class="rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-7">
            <div class="flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-cyan-500/10 text-cyan-300 border border-cyan-400/20">
                    <i class="ri-user-star-line text-base"></i>
                </span>
                <p class="text-xs uppercase tracking-[0.16em] text-slate-400">Founder’s Journey</p>
            </div>

            <div class="mt-5 grid grid-cols-1 lg:grid-cols-12 gap-5">
                <div class="lg:col-span-8 space-y-4 text-sm sm:text-[15px] leading-relaxed text-slate-300">
                    @foreach($founderJourney as $line)
                        <p>{{ $line }}</p>
                    @endforeach
                </div>

                <div class="lg:col-span-4 rounded-xl border border-white/10 bg-white/[0.02] p-4 h-fit">
                    <p class="text-[11px] uppercase tracking-[0.14em] text-slate-400">Store Front</p>
                    <div class="mt-3 rounded-lg overflow-hidden border border-white/10">
                        <img src="{{ asset('images/TOBAC-GO - Front.webp') }}" alt="Tobac-Go front store" class="w-full h-44 object-cover">
                    </div>
                    <p class="mt-3 text-xs leading-relaxed text-slate-300">
                        Designed for people who care about quality sessions, clear product choices, and dependable buying experience.
                    </p>
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

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pb-16 sm:pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <article class="lg:col-span-5 rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-6">
                <h2 class="text-xl font-semibold text-white">Who Tobac-Go Is For</h2>
                <ul class="mt-4 space-y-3">
                    @foreach($forWho as $line)
                        <li class="flex items-start gap-2 text-sm text-slate-200">
                            <i class="ri-checkbox-circle-fill mt-0.5 text-cyan-300"></i>
                            <span>{{ $line }}</span>
                        </li>
                    @endforeach
                </ul>
                <p class="mt-5 text-sm text-slate-300 leading-relaxed">Whether you are buying your first hookah or adding to your collection, we want the process to feel simple from start to finish.</p>
            </article>

            <article class="lg:col-span-7 rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-6">
                <h2 class="text-xl font-semibold text-white">Looking Ahead</h2>
                <div class="mt-4 space-y-3 text-sm text-slate-300 leading-relaxed">
                    @foreach($lookingAhead as $line)
                        <p>{{ $line }}</p>
                    @endforeach
                </div>

                <div class="mt-6 rounded-xl border border-white/10 bg-white/[0.02] p-4">
                    <p class="text-[11px] uppercase tracking-[0.14em] text-slate-400 mb-2">Why Customers Stay With Us</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 text-xs sm:text-sm">
                        <div class="rounded-lg border border-white/10 bg-black/20 px-3 py-2.5 text-slate-200 flex items-center gap-2">
                            <i class="ri-price-tag-3-line text-cyan-300"></i>
                            <span>Fair Pricing</span>
                        </div>
                        <div class="rounded-lg border border-white/10 bg-black/20 px-3 py-2.5 text-slate-200 flex items-center gap-2">
                            <i class="ri-file-text-line text-cyan-300"></i>
                            <span>Honest Details</span>
                        </div>
                        <div class="rounded-lg border border-white/10 bg-black/20 px-3 py-2.5 text-slate-200 flex items-center gap-2">
                            <i class="ri-customer-service-2-line text-cyan-300"></i>
                            <span>Real Support</span>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <div class="mt-6 rounded-2xl overflow-hidden border border-subtle bg-[#0b0d0f]">
            <div class="grid grid-cols-1 md:grid-cols-3">
                <div class="md:col-span-2 p-6 sm:p-7">
                    <p class="text-xs uppercase tracking-[0.16em] text-slate-400">Our Space</p>
                    <h3 class="mt-2 text-2xl sm:text-3xl font-semibold text-white">Built Around Real Sessions and Real Products</h3>
                    <p class="mt-3 text-sm text-slate-300 leading-relaxed">
                        From store floor to final checkout, every detail at Tobac-Go is designed for clarity, consistency, and confidence.
                    </p>
                </div>
                <div class="border-t md:border-t-0 md:border-l border-white/10">
                    <img src="{{ asset('images/hookah-shop-in-noida.webp') }}" alt="Hookah store in Noida" class="h-full w-full object-cover min-h-[210px]">
                </div>
            </div>
        </div>
    </section>
</div>
