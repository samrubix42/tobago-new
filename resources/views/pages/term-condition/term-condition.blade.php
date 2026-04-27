
@section('meta_title', 'Terms & Conditions | Tobac-Go Hookah Store')
@section('meta_description', 'Read the terms and conditions for using the Tobac-Go website and purchasing our premium hookah products.')

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

            <h1 class="mt-6 text-3xl sm:text-5xl font-semibold tracking-tight text-white">Terms and Conditions</h1>
            <p class="mt-4 max-w-3xl text-sm sm:text-base text-slate-300 leading-relaxed">
                These Terms and Conditions govern your access to and use of the Tobac-Go website and services. Please read them carefully before using the Service.
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
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">The words with the first letter capitalized have specific meanings defined below. These definitions apply whether they appear in singular or plural form.</p>
                    </div>

                    <div class="space-y-4">
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                            <h3 class="text-sm uppercase tracking-[0.14em] text-slate-400">Affiliate</h3>
                            <p class="mt-2 text-sm text-slate-200">An entity that controls, is controlled by, or is under common control with a party, where control means ownership of 50% or more of voting securities.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                            <h3 class="text-sm uppercase tracking-[0.14em] text-slate-400">Country</h3>
                            <p class="mt-2 text-sm text-slate-200">Uttar Pradesh, India.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                            <h3 class="text-sm uppercase tracking-[0.14em] text-slate-400">Company</h3>
                            <p class="mt-2 text-sm text-slate-200">Tobac-Go, 38,39, Lower Ground Floor, Street 76, Market, Amarpali Silicon City, Sector 76, Noida, Uttar Pradesh 201306.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                            <h3 class="text-sm uppercase tracking-[0.14em] text-slate-400">Device</h3>
                            <p class="mt-2 text-sm text-slate-200">Any device that can access the Service, including computers, cellphones, and tablets.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                            <h3 class="text-sm uppercase tracking-[0.14em] text-slate-400">Service</h3>
                            <p class="mt-2 text-sm text-slate-200">The Tobac-Go website.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                            <h3 class="text-sm uppercase tracking-[0.14em] text-slate-400">Terms</h3>
                            <p class="mt-2 text-sm text-slate-200">These Terms and Conditions that form the entire agreement between You and the Company.</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-5">
                            <h3 class="text-sm uppercase tracking-[0.14em] text-slate-400">Third‑party Social Media Service</h3>
                            <p class="mt-2 text-sm text-slate-200">Content or services provided by a third party that may be displayed or made available through the Service.</p>
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

                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                        <h2 class="text-xl font-semibold text-white">Acknowledgment</h2>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">These Terms govern the use of the Service and form the agreement between You and the Company. By accessing or using the Service, You agree to be bound by these Terms. If You disagree, do not use the Service.</p>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">You represent that you are over 18 years old. The Company does not permit those under 18 to use the Service.</p>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">Your use of the Service is also subject to the Company’s Privacy Policy. Please read it carefully before using the Service.</p>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                        <h2 class="text-xl font-semibold text-white">Links to Other Websites</h2>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">The Service may contain links to third-party websites and services not controlled by the Company.</p>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">The Company is not responsible for the content, privacy policies, or practices of any third party. You should review the terms and privacy policies of any third-party site you visit.</p>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                        <h2 class="text-xl font-semibold text-white">Termination</h2>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">The Company may suspend or terminate your access immediately, without notice, for any reason including breach of these Terms.</p>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">Upon termination, your right to use the Service ends immediately.</p>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                        <h2 class="text-xl font-semibold text-white">Limitation of Liability</h2>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">The Company’s total liability is limited to the amount actually paid by You through the Service, or USD 100 if you have not purchased anything.</p>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">In no event will the Company be liable for special, incidental, indirect, or consequential damages, including loss of profits, data, business interruption, personal injury, or loss of privacy.</p>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">Some jurisdictions do not allow limitation of liability, so these limits may not apply to you in full.</p>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                        <h2 class="text-xl font-semibold text-white">"AS IS" and "AS AVAILABLE" Disclaimer</h2>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">The Service is provided "AS IS" and "AS AVAILABLE" with all faults and without warranty of any kind.</p>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">The Company disclaims all warranties, express or implied, including merchantability, fitness for a particular purpose, title, and non-infringement.</p>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">The Company does not guarantee the Service will be uninterrupted, error-free, or compatible with your devices, software, or services.</p>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                        <h2 class="text-xl font-semibold text-white">Governing Law</h2>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">These Terms are governed by the laws of Uttar Pradesh, India, excluding conflict of law rules.</p>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                        <h2 class="text-xl font-semibold text-white">Disputes Resolution</h2>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">If you have a concern or dispute, you agree to first try to resolve it informally by contacting the Company.</p>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                        <h2 class="text-xl font-semibold text-white">For European Union Users</h2>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">EU consumers benefit from any mandatory provisions of the law of their country of residence.</p>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                        <h2 class="text-xl font-semibold text-white">United States Legal Compliance</h2>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">You represent that you are not located in a country subject to US embargo, and you are not listed on any prohibited or restricted parties list.</p>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                        <h2 class="text-xl font-semibold text-white">Severability and Waiver</h2>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">If any provision is held invalid, it will be reinterpreted to achieve the original intent, and the remaining Terms will remain in effect.</p>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">Failure to enforce a right does not waive the Company’s ability to enforce it later.</p>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                        <h2 class="text-xl font-semibold text-white">Translation Interpretation</h2>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">If a translation is provided, the English version shall prevail in case of dispute.</p>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                        <h2 class="text-xl font-semibold text-white">Changes to These Terms and Conditions</h2>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">The Company may modify or replace these Terms at any time. Material changes will be communicated with reasonable notice.</p>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">By continuing to use the Service after revisions take effect, you accept the updated Terms.</p>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6">
                        <h2 class="text-xl font-semibold text-white">Contact Us</h2>
                        <p class="mt-4 text-sm leading-relaxed text-slate-300">If you have questions about these Terms, contact us at <a href="mailto:info@tobacgo.in" class="text-cyan-300 hover:text-cyan-200">info@tobacgo.in</a>.</p>
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
                        <p class="text-xs uppercase tracking-[0.14em] text-slate-400">Policy</p>
                        <p class="mt-3 text-sm text-slate-200">Read our Privacy Policy and other legal documents for full details.</p>
                    </div>
                </div>
            </aside>
        </div>
    </section>
</div>
