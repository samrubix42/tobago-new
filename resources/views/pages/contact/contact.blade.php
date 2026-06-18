
@php
    $phone = app_setting('phone_number', '+91 78384 49604') ?: '+91 78384 49604';
    $whatsapp = app_setting('whatsapp_number', $phone) ?: $phone;
    $email = app_setting('email', 'support@tobacgo.in') ?: 'support@tobacgo.in';
    $address = app_setting('address', 'Shop No. 38-39, Lower Ground Floor, Street 76 Market, Amarpali Silicon City, Sector 76, Noida, Uttar Pradesh 201316')
        ?: 'Shop No. 38-39, Lower Ground Floor, Street 76 Market, Amarpali Silicon City, Sector 76, Noida, Uttar Pradesh 201316';
    $phoneHref = preg_replace('/[^0-9+]/', '', $phone);
    $whatsappHref = preg_replace('/[^0-9]/', '', $whatsapp);
@endphp

<div class="relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-[14%] -left-[8%] h-[360px] w-[360px] sm:h-[520px] sm:w-[520px] opacity-20 blur-[110px]" style="background: radial-gradient(circle, #00c6ff, transparent 72%);"></div>
        <div class="absolute top-[12%] -right-[10%] h-[360px] w-[360px] sm:h-[540px] sm:w-[540px] opacity-20 blur-[120px]" style="background: radial-gradient(circle, #ff7a18, transparent 70%);"></div>
    </div>

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pt-10 pb-8 lg:pt-16 lg:pb-10">
        <div class="grid lg:grid-cols-12 gap-6 lg:gap-8 items-stretch">
            <div class="lg:col-span-7 rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-7 lg:p-8">
                <p class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.04] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-300">
                    <i class="ri-customer-service-2-line text-cyan-300"></i>
                    Contact Tobac-Go
                </p>

                <h1 class="mt-5 max-w-3xl text-3xl sm:text-5xl font-bold leading-[1.08] tracking-tight text-white">
                    Need help choosing the right hookah or accessory?
                </h1>
                <p class="mt-4 max-w-2xl text-sm sm:text-base leading-relaxed text-slate-300">
                    Message us for product guidance, order support, store visits, bulk inquiries, or anything you want clarified before you buy.
                </p>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <a href="https://wa.me/{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer" class="group rounded-xl border border-emerald-400/20 bg-emerald-400/10 p-4 transition hover:border-emerald-300/40 hover:bg-emerald-400/15">
                        <span class="flex items-center gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-400/15 text-emerald-300">
                                <i class="ri-whatsapp-line text-xl"></i>
                            </span>
                            <span>
                                <span class="block text-sm font-semibold text-white">WhatsApp Support</span>
                                <span class="mt-0.5 block text-xs text-slate-300">{{ $whatsapp }}</span>
                            </span>
                        </span>
                    </a>

                    <a href="tel:{{ $phoneHref }}" class="group rounded-xl border border-cyan-400/20 bg-cyan-400/10 p-4 transition hover:border-cyan-300/40 hover:bg-cyan-400/15">
                        <span class="flex items-center gap-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-400/15 text-cyan-300">
                                <i class="ri-phone-line text-xl"></i>
                            </span>
                            <span>
                                <span class="block text-sm font-semibold text-white">Call The Store</span>
                                <span class="mt-0.5 block text-xs text-slate-300">{{ $phone }}</span>
                            </span>
                        </span>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5 overflow-hidden rounded-2xl border border-subtle bg-[#0b0d0f]">
                <div class="relative h-full min-h-[300px]">
                    <img src="{{ asset('images/hookah-shop-in-noida.webp') }}" alt="Tobac-Go Noida store" class="absolute inset-0 h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/35 to-transparent"></div>
                    <div class="absolute inset-x-0 bottom-0 p-5 sm:p-6">
                        <p class="text-xs uppercase tracking-[0.14em] text-cyan-200">Noida Store</p>
                        <p class="mt-2 text-xl font-semibold text-white">Sector 76, Amarpali Silicon City</p>
                        <p class="mt-2 text-sm leading-relaxed text-slate-200">Visit us for in-person product advice, setup help, and premium hookah shopping.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pb-8">
        <div class="grid lg:grid-cols-12 gap-6">
            <div class="lg:col-span-7 rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-7">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-2xl sm:text-3xl font-semibold text-white">Send Us A Message</h2>
                        <p class="mt-2 text-sm text-slate-300">Share a few details and our team will respond as soon as possible.</p>
                    </div>
                    <span class="hidden sm:inline-flex h-11 w-11 items-center justify-center rounded-xl border border-white/10 bg-white/[0.04] text-cyan-300">
                        <i class="ri-mail-send-line text-xl"></i>
                    </span>
                </div>

                <form wire:submit="submit" class="mt-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="contact-name" class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">Name</label>
                            <input id="contact-name" type="text" wire:model.defer="name" autocomplete="name" placeholder="Your name" class="mt-2 w-full rounded-xl border border-white/10 bg-white/[0.04] px-4 py-3 text-sm text-white placeholder:text-slate-500 outline-none transition focus:border-cyan-300/50 focus:bg-white/[0.06]">
                            @error('name') <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="contact-phone" class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">Phone</label>
                            <input id="contact-phone" type="tel" wire:model.defer="phone" autocomplete="tel" placeholder="Mobile number" class="mt-2 w-full rounded-xl border border-white/10 bg-white/[0.04] px-4 py-3 text-sm text-white placeholder:text-slate-500 outline-none transition focus:border-cyan-300/50 focus:bg-white/[0.06]">
                            @error('phone') <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="contact-email" class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">Email</label>
                        <input id="contact-email" type="email" wire:model.defer="email" autocomplete="email" placeholder="you@example.com" class="mt-2 w-full rounded-xl border border-white/10 bg-white/[0.04] px-4 py-3 text-sm text-white placeholder:text-slate-500 outline-none transition focus:border-cyan-300/50 focus:bg-white/[0.06]">
                        @error('email') <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="contact-message" class="text-xs font-semibold uppercase tracking-[0.12em] text-slate-400">Message</label>
                        <textarea id="contact-message" wire:model.defer="message" rows="6" placeholder="Tell us what you are looking for..." class="mt-2 w-full resize-none rounded-xl border border-white/10 bg-white/[0.04] px-4 py-3 text-sm leading-relaxed text-white placeholder:text-slate-500 outline-none transition focus:border-cyan-300/50 focus:bg-white/[0.06]"></textarea>
                        @error('message') <p class="mt-1.5 text-xs text-rose-300">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-1">
                        <p class="text-xs leading-relaxed text-slate-400">We use your details only to reply to your inquiry.</p>
                        <button type="submit" wire:loading.attr="disabled" wire:target="submit" class="inline-flex items-center justify-center gap-2 rounded-full px-6 py-3 text-sm font-bold text-black transition hover:scale-[1.02] disabled:cursor-not-allowed disabled:opacity-70" style="background: var(--gradient-main);">
                            <span wire:loading.remove wire:target="submit">Send Message</span>
                            <span wire:loading wire:target="submit" class="inline-flex items-center gap-2">
                                <i class="ri-loader-4-line animate-spin"></i>
                                Sending
                            </span>
                            <i wire:loading.remove wire:target="submit" class="ri-arrow-right-line"></i>
                        </button>
                    </div>
                </form>
            </div>

            <aside class="lg:col-span-5 space-y-4">
                <div class="rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-6">
                    <h2 class="text-xl font-semibold text-white">Store Details</h2>
                    <div class="mt-5 space-y-3">
                        <a href="mailto:{{ $email }}" class="flex gap-3 rounded-xl border border-white/10 bg-white/[0.03] p-3.5 transition hover:border-cyan-300/40">
                            <span class="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/[0.05] text-cyan-300">
                                <i class="ri-mail-line"></i>
                            </span>
                            <span class="min-w-0">
                                <span class="block text-xs uppercase tracking-[0.12em] text-slate-500">Email</span>
                                <span class="mt-1 block break-all text-sm text-slate-200">{{ $email }}</span>
                            </span>
                        </a>

                        <div class="flex gap-3 rounded-xl border border-white/10 bg-white/[0.03] p-3.5">
                            <span class="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/[0.05] text-cyan-300">
                                <i class="ri-time-line"></i>
                            </span>
                            <span>
                                <span class="block text-xs uppercase tracking-[0.12em] text-slate-500">Store Hours</span>
                                <span class="mt-1 block text-sm text-slate-200">Mon-Sun, 11:00 AM - 11:00 PM</span>
                            </span>
                        </div>

                        <div class="flex gap-3 rounded-xl border border-white/10 bg-white/[0.03] p-3.5">
                            <span class="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/[0.05] text-cyan-300">
                                <i class="ri-map-pin-line"></i>
                            </span>
                            <span>
                                <span class="block text-xs uppercase tracking-[0.12em] text-slate-500">Address</span>
                                <span class="mt-1 block text-sm leading-relaxed text-slate-200">{{ $address }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-6">
                    <h2 class="text-xl font-semibold text-white">How We Can Help</h2>
                    <div class="mt-4 grid grid-cols-1 gap-2.5">
                        @foreach([
                            ['ri-shopping-bag-3-line', 'Order and delivery support'],
                            ['ri-vip-crown-2-line', 'Premium hookah recommendations'],
                            ['ri-tools-line', 'Accessories and replacement guidance'],
                            ['ri-store-3-line', 'Store visit and product availability'],
                        ] as [$icon, $text])
                            <div class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/[0.03] px-3.5 py-3 text-sm text-slate-200">
                                <i class="{{ $icon }} text-cyan-300"></i>
                                <span>{{ $text }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </section>

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pb-16 sm:pb-20">
        <div class="grid lg:grid-cols-12 gap-6">
            <div class="lg:col-span-5 rounded-2xl border border-subtle bg-[#0b0d0f] p-5 sm:p-6">
                <p class="text-xs uppercase tracking-[0.14em] text-slate-400">Find Us</p>
                <h2 class="mt-3 text-2xl font-semibold text-white">Visit Tobac-Go in Noida</h2>
                <p class="mt-3 text-sm leading-relaxed text-slate-300">Come by the store for a closer look at our hookahs, bongs, accessories, and current stock.</p>
                <div class="mt-5 flex flex-col sm:flex-row lg:flex-col xl:flex-row gap-3">
                    <a href="https://maps.google.com/?q=Shop+No.+38-39,+Street+76+Market,+Amarpali+Silicon+City,+Sector+76,+Noida" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-full border border-white/20 bg-white/[0.05] px-5 py-3 text-xs font-semibold uppercase tracking-[0.12em] text-white transition hover:border-cyan-300/40 hover:bg-white/[0.08]">
                        <i class="ri-road-map-line"></i>
                        Get Directions
                    </a>
                    <a href="{{ route('products') }}" wire:navigate class="inline-flex items-center justify-center gap-2 rounded-full border border-white/20 px-5 py-3 text-xs font-semibold uppercase tracking-[0.12em] text-white/75 transition hover:border-white/40 hover:text-white">
                        <i class="ri-shopping-bag-line"></i>
                        Shop Online
                    </a>
                </div>
            </div>

            <div class="lg:col-span-7 overflow-hidden rounded-2xl border border-subtle bg-[#0b0d0f]">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3504.049782791698!2d77.38031990974697!3d28.56826777559849!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cef9220724b87%3A0x21576299f7843f22!2sTobac-go!5e0!3m2!1sen!2sin!4v1777036091183!5m2!1sen!2sin" title="Tobac-Go Noida map" class="h-[360px] w-full sm:h-[430px]" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
</div>
