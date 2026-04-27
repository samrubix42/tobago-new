@section('meta_title', 'Shipping Policy | Tobac-Go Hookah Store')
@section('meta_description', 'Learn about our shipping and delivery process. We provide safe and fast shipping for hookahs and accessories across India.')

<div class="relative overflow-hidden">

    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-20 h-72 w-72 rounded-full blur-3xl opacity-20" style="background: radial-gradient(circle, #00c6ff, transparent 70%);"></div>
        <div class="absolute top-1/3 -right-20 h-80 w-80 rounded-full blur-3xl opacity-20" style="background: radial-gradient(circle, #6a5cff, transparent 70%);"></div>
    </div>

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pt-10 sm:pt-14 pb-6">
        <div class="rounded-3xl border border-subtle bg-[#0b0d0f]/90 p-6 sm:p-8">
            <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-[11px] uppercase tracking-[0.16em] text-slate-300">
                <i class="ri-truck-line text-cyan-300"></i>
                Shipping Policy
            </div>

            <h1 class="mt-5 text-3xl sm:text-5xl font-semibold tracking-tight text-white">Shipping Policy</h1>
            <p class="mt-4 max-w-3xl text-sm sm:text-base text-slate-300 leading-relaxed">
                This page explains how Tobac-Go handles shipping, delivery timelines, and delay support so you know what to expect after placing an order.
            </p>

            <div class="mt-7 grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="rounded-xl border border-white/10 bg-white/[0.03] px-4 py-3">
                    <p class="text-[11px] uppercase tracking-[0.14em] text-slate-400">Free Shipping</p>
                    <p class="mt-1 text-sm font-semibold text-white">Above &#8377;3000</p>
                </div>
                <div class="rounded-xl border border-white/10 bg-white/[0.03] px-4 py-3">
                    <p class="text-[11px] uppercase tracking-[0.14em] text-slate-400">Standard Delivery</p>
                    <p class="mt-1 text-sm font-semibold text-white">3-7 Business Days</p>
                </div>
                <div class="rounded-xl border border-white/10 bg-white/[0.03] px-4 py-3">
                    <p class="text-[11px] uppercase tracking-[0.14em] text-slate-400">Last Updated</p>
                    <p class="mt-1 text-sm font-semibold text-white">April 24, 2026</p>
                </div>
            </div>
        </div>
    </section>

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pb-6">
        <div class="rounded-2xl border border-subtle bg-[#0b0d0f] p-4 sm:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="text-[11px] uppercase tracking-[0.16em] text-slate-400">Policy Center</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('privacy-policy') }}" wire:navigate class="inline-flex items-center rounded-full border px-3 py-1.5 text-xs transition {{ request()->routeIs('privacy-policy') ? 'border-cyan-300/40 bg-cyan-500/10 text-cyan-200' : 'border-white/15 bg-white/[0.03] text-slate-300 hover:border-white/30 hover:text-white' }}">Privacy</a>
                    <a href="{{ route('shipping-policy') }}" wire:navigate class="inline-flex items-center rounded-full border px-3 py-1.5 text-xs transition {{ request()->routeIs('shipping-policy') ? 'border-cyan-300/40 bg-cyan-500/10 text-cyan-200' : 'border-white/15 bg-white/[0.03] text-slate-300 hover:border-white/30 hover:text-white' }}">Shipping</a>
                    <a href="{{ route('terms-conditions') }}" wire:navigate class="inline-flex items-center rounded-full border px-3 py-1.5 text-xs transition {{ request()->routeIs('terms-conditions') ? 'border-cyan-300/40 bg-cyan-500/10 text-cyan-200' : 'border-white/15 bg-white/[0.03] text-slate-300 hover:border-white/30 hover:text-white' }}">Terms</a>
                    <a href="{{ route('return-refund') }}" wire:navigate class="inline-flex items-center rounded-full border px-3 py-1.5 text-xs transition {{ request()->routeIs('return-refund') ? 'border-cyan-300/40 bg-cyan-500/10 text-cyan-200' : 'border-white/15 bg-white/[0.03] text-slate-300 hover:border-white/30 hover:text-white' }}">Returns</a>
                </div>
            </div>
        </div>
    </section>

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pb-16 sm:pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <article class="lg:col-span-8 rounded-3xl border border-subtle bg-[#0b0d0f] p-6 sm:p-8 space-y-7">
                <div>
                    <h2 class="text-xl font-semibold text-white">Shipping and Delivery</h2>
                    <p class="mt-3 text-sm text-slate-300 leading-relaxed">We offer shipping options designed to keep deliveries reliable and predictable for customers across India.</p>
                    <ul class="mt-4 space-y-3 text-sm text-slate-200">
                        <li class="flex items-start gap-2"><i class="ri-checkbox-circle-fill text-cyan-300 mt-0.5"></i><span>Free shipping on orders above &#8377;3000.</span></li>
                        <li class="flex items-start gap-2"><i class="ri-checkbox-circle-fill text-cyan-300 mt-0.5"></i><span>Flat-rate shipping available for all other orders.</span></li>
                        <li class="flex items-start gap-2"><i class="ri-checkbox-circle-fill text-cyan-300 mt-0.5"></i><span>Orders are generally processed within 1-3 business days.</span></li>
                        <li class="flex items-start gap-2"><i class="ri-checkbox-circle-fill text-cyan-300 mt-0.5"></i><span>Standard delivery usually takes 3-7 business days.</span></li>
                    </ul>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                    <h3 class="text-lg font-semibold text-white">Delayed Orders</h3>
                    <p class="mt-3 text-sm text-slate-300 leading-relaxed">Sometimes deliveries may be delayed due to logistics issues, weather, high demand, or carrier constraints. If this happens, we share updates and revised delivery timelines as quickly as possible.</p>
                    <p class="mt-3 text-sm text-slate-300 leading-relaxed">For significant delays, you can continue, modify, or cancel your order for a full refund.</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                    <h3 class="text-lg font-semibold text-white">Returns and Exchanges</h3>
                    <p class="mt-3 text-sm text-slate-300 leading-relaxed">For return or exchange eligibility, please review our Return and Refund Policy. Shipping and refund processes are designed to work together clearly.</p>
                </div>
            </article>

            <aside class="lg:col-span-4 rounded-3xl border border-subtle bg-[#0b0d0f] p-6 sm:p-8 h-fit">
                <h3 class="text-lg font-semibold text-white">Need Shipping Help?</h3>
                <p class="mt-3 text-sm text-slate-300 leading-relaxed">For order tracking, delivery updates, or shipping issues, reach out to our support team.</p>

                <div class="mt-5 rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                    <p class="text-[11px] uppercase tracking-[0.14em] text-slate-400">Support Email</p>
                    <a href="mailto:info@tobacgo.in" class="mt-2 inline-block text-sm font-medium text-cyan-300 hover:text-cyan-200">info@tobacgo.in</a>
                </div>

                <div class="mt-4 rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                    <p class="text-[11px] uppercase tracking-[0.14em] text-slate-400">Response</p>
                    <p class="mt-2 text-sm text-slate-300">We keep communication clear and share updates promptly when shipment status changes.</p>
                </div>
            </aside>
        </div>
    </section>
</div>
