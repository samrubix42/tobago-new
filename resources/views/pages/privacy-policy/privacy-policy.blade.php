@section('meta_title', 'Privacy Policy | Tobac-Go Hookah Store')
@section('meta_description', 'Read the privacy policy of Tobac-Go. Learn how we collect, use, and protect your personal information when you shop for hookahs with us.')

<div class="relative overflow-hidden">

    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -left-20 h-72 w-72 rounded-full blur-3xl opacity-20" style="background: radial-gradient(circle, #00c6ff, transparent 70%);"></div>
        <div class="absolute top-1/4 -right-24 h-80 w-80 rounded-full blur-3xl opacity-20" style="background: radial-gradient(circle, #6a5cff, transparent 70%);"></div>
    </div>

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-14">
        <div class="rounded-3xl border border-subtle bg-[#0b0d0f]/90 p-6 sm:p-8 shadow-2xl shadow-black/30">
            <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-[11px] uppercase tracking-[0.14em] text-slate-300">
                <span class="h-2 w-2 rounded-full bg-cyan-400"></span>
                Last updated: April 24, 2026
            </div>

            <h1 class="mt-6 text-3xl sm:text-5xl font-semibold tracking-tight text-white">Privacy Policy</h1>
            <p class="mt-4 max-w-3xl text-sm sm:text-base text-slate-300 leading-relaxed">
                This Privacy Policy explains how Tobac-Go collects, uses, and discloses your information when you use our Service, and your privacy rights under applicable law.
            </p>
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

    <section class="relative max-w-7xl mx-auto px-4 sm:px-6 pb-10">
        <div class="grid gap-6 lg:grid-cols-12">
            <article class="lg:col-span-8 rounded-3xl border border-subtle bg-[#0b0d0f] p-6 sm:p-8">
                <div class="space-y-8 text-slate-300">
                    <div>
                        <h2 class="text-xl font-semibold text-white">Interpretation and Definitions</h2>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">Capitalized words have meanings defined below. These definitions apply whether they appear in singular or plural form.</p>
                    </div>

                    <div class="space-y-4">
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                            <h3 class="text-sm uppercase tracking-[0.14em] text-slate-400">Account</h3>
                            <p class="mt-2 text-sm text-slate-200">A unique account created for you to access our Service or parts of our Service.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                            <h3 class="text-sm uppercase tracking-[0.14em] text-slate-400">Affiliate</h3>
                            <p class="mt-2 text-sm text-slate-200">An entity that controls, is controlled by, or is under common control with a party.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                            <h3 class="text-sm uppercase tracking-[0.14em] text-slate-400">Company</h3>
                            <p class="mt-2 text-sm text-slate-200">Tobac-Go, 38,39, Lower Ground Floor, Street 76, Market, Amarpali Silicon City, Sector 76, Noida, Uttar Pradesh 201306.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                            <h3 class="text-sm uppercase tracking-[0.14em] text-slate-400">Cookies</h3>
                            <p class="mt-2 text-sm text-slate-200">Small files placed on your device to track activity and store information.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                            <h3 class="text-sm uppercase tracking-[0.14em] text-slate-400">Country</h3>
                            <p class="mt-2 text-sm text-slate-200">Uttar Pradesh, India.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                            <h3 class="text-sm uppercase tracking-[0.14em] text-slate-400">Device</h3>
                            <p class="mt-2 text-sm text-slate-200">Any device that can access the Service such as a computer, cellphone, or tablet.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                            <h3 class="text-sm uppercase tracking-[0.14em] text-slate-400">Personal Data</h3>
                            <p class="mt-2 text-sm text-slate-200">Information that relates to an identified or identifiable individual.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                            <h3 class="text-sm uppercase tracking-[0.14em] text-slate-400">Service Provider</h3>
                            <p class="mt-2 text-sm text-slate-200">A natural or legal person who processes data on behalf of the Company.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                            <h3 class="text-sm uppercase tracking-[0.14em] text-slate-400">Usage Data</h3>
                            <p class="mt-2 text-sm text-slate-200">Data collected automatically from your use of the Service.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                            <h3 class="text-sm uppercase tracking-[0.14em] text-slate-400">Website</h3>
                            <p class="mt-2 text-sm text-slate-200">Tobacgo, accessible from https://www.tobacgo.in/.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                            <h3 class="text-sm uppercase tracking-[0.14em] text-slate-400">You</h3>
                            <p class="mt-2 text-sm text-slate-200">The individual or entity accessing or using the Service.</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                            <h2 class="text-xl font-semibold text-white">Collecting and Using Your Personal Data</h2>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">We collect your Personal Data to provide and improve the Service. By using the Service, you agree to this collection and use of information.</p>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                            <h3 class="text-lg font-semibold text-white">Types of Data Collected</h3>
                            <div class="mt-4 space-y-4 text-sm leading-relaxed text-slate-300">
                                <div>
                                    <h4 class="font-semibold text-white">Personal Data</h4>
                                    <p class="mt-2">We may collect information such as email address, first and last name, phone number, address, state, ZIP code, city, and Usage Data.</p>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-white">Usage Data</h4>
                                    <p class="mt-2">Automatically collected information such as IP address, browser type and version, pages visited, time and date of your visit, time spent on pages, unique device identifiers, and diagnostic data.</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                            <h3 class="text-lg font-semibold text-white">Tracking Technologies and Cookies</h3>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">We use cookies, beacons, tags, and scripts to track activity on our Service and to improve and analyze the Service.</p>
                            <div class="mt-4 space-y-4 text-sm leading-relaxed text-slate-300">
                                <div>
                                    <strong class="text-white">Cookies or Browser Cookies</strong>
                                    <p class="mt-2">Small files placed on your Device. You can refuse cookies through browser settings, but some Service features may not work without them.</p>
                                </div>
                                <div>
                                    <strong class="text-white">Web Beacons</strong>
                                    <p class="mt-2">Small electronic files used to count visitors or measure page and email engagement.</p>
                                </div>
                                <div>
                                    <strong class="text-white">Persistent Cookies</strong>
                                    <p class="mt-2">Remain on your device after you go offline.</p>
                                </div>
                                <div>
                                    <strong class="text-white">Session Cookies</strong>
                                    <p class="mt-2">Deleted once you close your browser.</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                            <h3 class="text-lg font-semibold text-white">Use of Your Personal Data</h3>
                            <ul class="mt-4 space-y-3 text-sm leading-relaxed text-slate-300 list-disc list-inside">
                                <li>To provide and maintain the Service.</li>
                                <li>To manage your Account and registration.</li>
                                <li>To perform a contract related to your purchases or other agreements.</li>
                                <li>To contact you by email, SMS, or other communication methods.</li>
                                <li>To send news, special offers, and information about similar goods and services unless you opt out.</li>
                                <li>To manage your requests and support inquiries.</li>
                                <li>To evaluate business transfers, such as mergers or restructurings.</li>
                                <li>For data analysis, usage trends, marketing effectiveness, and Service improvement.</li>
                            </ul>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                            <h3 class="text-lg font-semibold text-white">Sharing Your Personal Data</h3>
                            <ul class="mt-4 space-y-3 text-sm leading-relaxed text-slate-300 list-disc list-inside">
                                <li>With Service Providers who monitor and analyze service usage.</li>
                                <li>For business transfers, such as mergers or asset sales.</li>
                                <li>With Affiliates who are required to honor this Privacy Policy.</li>
                                <li>With business partners for offers, services, or promotions.</li>
                                <li>With other users when you share information publicly.</li>
                                <li>With your consent for any other purpose.</li>
                            </ul>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                            <h3 class="text-lg font-semibold text-white">Retention of Your Personal Data</h3>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">We retain Personal Data only as long as necessary to fulfill the purposes described in this Privacy Policy, comply with legal obligations, resolve disputes, and enforce our agreements.</p>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">Usage Data may be retained for a shorter period unless needed for security or service improvement.</p>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                            <h3 class="text-lg font-semibold text-white">Transfer of Your Personal Data</h3>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">Your data may be transferred to and maintained on computers located outside your jurisdiction.</p>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">By submitting your data, you agree to the transfer and we will take reasonable steps to keep it secure.</p>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                            <h3 class="text-lg font-semibold text-white">Delete Your Personal Data</h3>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">You can request deletion of Personal Data we have collected about you.</p>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">Sign in to your account or contact us to request access, correction, or deletion of your personal information.</p>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">Some data may be retained when required by law.</p>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                            <h3 class="text-lg font-semibold text-white">Disclosure of Your Personal Data</h3>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">We may disclose your data for business transactions, legal requests, or to protect rights and safety.</p>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                            <h3 class="text-lg font-semibold text-white">Security of Your Personal Data</h3>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">We use commercially acceptable measures to protect your data, but no method of transmission or storage is 100% secure.</p>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                            <h3 class="text-lg font-semibold text-white">Children's Privacy</h3>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">We do not knowingly collect personal information from anyone under 13. If we learn that we have, we will remove it.</p>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">If parental consent is required by law, we may request it before collecting information from children.</p>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                            <h3 class="text-lg font-semibold text-white">Links to Other Websites</h3>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">Our Service may contain links to third-party websites. We are not responsible for their content, privacy policies, or practices.</p>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                            <h3 class="text-lg font-semibold text-white">Changes to this Privacy Policy</h3>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">We may update this Privacy Policy from time to time. We will notify you by posting the new policy on this page and, where appropriate, by email or notice.</p>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">Changes are effective when posted.</p>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                            <h3 class="text-lg font-semibold text-white">Contact Us</h3>
                            <p class="mt-4 text-sm leading-relaxed text-slate-300">If you have questions about this Privacy Policy, contact us at <a href="mailto:info@tobacgo.in" class="text-cyan-300 hover:text-cyan-200">info@tobacgo.in</a>.</p>
                            <p class="mt-3 text-sm leading-relaxed text-slate-300">Visit: <a href="https://www.tobacgo.in/" class="text-cyan-300 hover:text-cyan-200">https://www.tobacgo.in/</a></p>
                        </div>
                    </div>
                </div>
            </article>

            <aside class="lg:col-span-4 rounded-3xl border border-subtle bg-[#0b0d0f] p-6 sm:p-8">
                <div class="space-y-5">
                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-5">
                        <p class="text-xs uppercase tracking-[0.14em] text-slate-400">Company</p>
                        <p class="mt-3 text-sm text-slate-200">Tobac-Go, 38,39 Lower Ground Floor, Street 76, Market, Amarpali Silicon City, Sector 76, Noida, Uttar Pradesh 201306.</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-5">
                        <p class="text-xs uppercase tracking-[0.14em] text-slate-400">Service</p>
                        <p class="mt-3 text-sm text-slate-200">Tobacgo website at <a href="https://www.tobacgo.in/" class="text-cyan-300 hover:text-cyan-200">tobacgo.in</a>.</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-5">
                        <p class="text-xs uppercase tracking-[0.14em] text-slate-400">Support</p>
                        <p class="mt-3 text-sm text-slate-200">Email: <a href="mailto:info@tobacgo.in" class="text-cyan-300 hover:text-cyan-200">info@tobacgo.in</a></p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-5">
                        <p class="text-xs uppercase tracking-[0.14em] text-slate-400">Privacy</p>
                        <p class="mt-3 text-sm text-slate-200">This page explains how we collect, use, and protect your information.</p>
                    </div>
                </div>
            </aside>
        </div>
    </section>
</div>
