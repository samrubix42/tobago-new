
<div class="space-y-6">
    <div class="flex flex-col gap-1">
        <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">Settings</h1>
        <p class="text-sm text-slate-500">Manage system and order level key-value configuration.</p>
    </div>

    <form wire:submit="save" class="space-y-5">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <section class="lg:col-span-1 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-800">Order Settings</h2>
                <p class="mt-1 text-xs text-slate-500">Delivery related configuration values.</p>

                <div class="mt-4 space-y-3">
                    <div>
                        <label for="delivery_fee" class="text-xs font-medium text-slate-600">Delivery Fee</label>
                        <input
                            id="delivery_fee"
                            type="number"
                            step="0.01"
                            wire:model.defer="settings.delivery_fee"
                            class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                            placeholder="0.00"
                        >
                        @error('settings.delivery_fee') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-[11px] text-slate-400">key: delivery_fee</p>
                    </div>

                    <div>
                        <label for="free_delivery_amount" class="text-xs font-medium text-slate-600">Free Delivery Amount</label>
                        <input
                            id="free_delivery_amount"
                            type="number"
                            step="0.01"
                            wire:model.defer="settings.free_delivery_amount"
                            class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                            placeholder="0.00"
                        >
                        @error('settings.free_delivery_amount') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-[11px] text-slate-400">key: free_delivery_amount</p>
                    </div>
                </div>
            </section>

            <section class="lg:col-span-2 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-800">System Settings</h2>
                <p class="mt-1 text-xs text-slate-500">Basic application information and public contact settings.</p>

                <div class="mt-4 rounded-lg border border-slate-200 bg-slate-50/70 p-3">
                    <label class="text-xs font-medium text-slate-700">Logo</label>

                    <div class="mt-2 flex items-center gap-3">
                        <div class="h-14 w-14 overflow-hidden rounded-md border border-slate-200 bg-white flex items-center justify-center">
                            @if($logoFile)
                                <img src="{{ $logoFile->temporaryUrl() }}" alt="Logo Preview" class="h-full w-full object-cover">
                            @elseif(!empty($settings['logo']))
                                <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" class="h-full w-full object-cover">
                            @else
                                <i class="ri-image-line text-slate-300 text-lg"></i>
                            @endif
                        </div>

                        <div class="flex-1">
                            <input
                                id="logo"
                                type="file"
                                accept="image/*"
                                wire:model="logoFile"
                                class="block w-full text-xs text-slate-600 file:mr-3 file:rounded-md file:border-0 file:bg-blue-600 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-white hover:file:bg-blue-500"
                            >
                            <div wire:loading wire:target="logoFile" class="mt-1 text-[11px] text-blue-600">
                                Uploading logo...
                            </div>
                            @error('logoFile') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            <p class="mt-1 text-[11px] text-slate-400">key: logo</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label for="project_name" class="text-xs font-medium text-slate-600">Project Name</label>
                        <input id="project_name" type="text" wire:model.defer="settings.project_name" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="Project name">
                        @error('settings.project_name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-[11px] text-slate-400">key: project_name</p>
                    </div>

                    <div>
                        <label for="phone_number" class="text-xs font-medium text-slate-600">Phone Number</label>
                        <input id="phone_number" type="text" wire:model.defer="settings.phone_number" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="+91xxxxxxxxxx">
                        @error('settings.phone_number') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-[11px] text-slate-400">key: phone_number</p>
                    </div>

                    <div>
                        <label for="email" class="text-xs font-medium text-slate-600">Email</label>
                        <input id="email" type="email" wire:model.defer="settings.email" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="info@example.com">
                        @error('settings.email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-[11px] text-slate-400">key: email</p>
                    </div>

                    <div>
                        <label for="whatsapp_number" class="text-xs font-medium text-slate-600">Whatsapp Number</label>
                        <input id="whatsapp_number" type="text" wire:model.defer="settings.whatsapp_number" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="+91xxxxxxxxxx">
                        @error('settings.whatsapp_number') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-[11px] text-slate-400">key: whatsapp_number</p>
                    </div>

                    <div>
                        <label for="application_name" class="text-xs font-medium text-slate-600">Application Name</label>
                        <input id="application_name" type="text" wire:model.defer="settings.application_name" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="App name">
                        @error('settings.application_name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-[11px] text-slate-400">key: application_name</p>
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="text-xs font-medium text-slate-600">Address</label>
                        <textarea id="address" rows="2" wire:model.defer="settings.address" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="Company address"></textarea>
                        @error('settings.address') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-[11px] text-slate-400">key: address</p>
                    </div>
                </div>

                <div class="mt-4 rounded-lg border border-slate-200 p-3">
                    <h3 class="text-xs font-semibold text-slate-700">Social Media</h3>
                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label for="instagram_url" class="text-xs font-medium text-slate-600">Instagram</label>
                            <input id="instagram_url" type="url" wire:model.defer="settings.instagram_url" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="https://instagram.com/yourpage">
                            @error('settings.instagram_url') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            <p class="mt-1 text-[11px] text-slate-400">key: instagram_url</p>
                        </div>

                        <div>
                            <label for="linkedin_url" class="text-xs font-medium text-slate-600">LinkedIn</label>
                            <input id="linkedin_url" type="url" wire:model.defer="settings.linkedin_url" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="https://linkedin.com/company/yourpage">
                            @error('settings.linkedin_url') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            <p class="mt-1 text-[11px] text-slate-400">key: linkedin_url</p>
                        </div>

                        <div>
                            <label for="facebook_url" class="text-xs font-medium text-slate-600">Facebook</label>
                            <input id="facebook_url" type="url" wire:model.defer="settings.facebook_url" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="https://facebook.com/yourpage">
                            @error('settings.facebook_url') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            <p class="mt-1 text-[11px] text-slate-400">key: facebook_url</p>
                        </div>

                        <div>
                            <label for="x_url" class="text-xs font-medium text-slate-600">X (Twitter)</label>
                            <input id="x_url" type="url" wire:model.defer="settings.x_url" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="https://x.com/yourpage">
                            @error('settings.x_url') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                            <p class="mt-1 text-[11px] text-slate-400">key: x_url</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-3">
                    <div class="md:col-span-2">
                        <label for="footer_text" class="text-xs font-medium text-slate-600">Footer Text</label>
                        <textarea id="footer_text" rows="2" wire:model.defer="settings.footer_text" class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" placeholder="Copyright text"></textarea>
                        @error('settings.footer_text') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-[11px] text-slate-400">key: footer_text</p>
                    </div>
                </div>
            </section>
        </div>

        <div class="flex justify-end">
            <button
                type="submit"
                class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-500"
            >
                <i class="ri-save-line text-base"></i>
                Save Settings
            </button>
        </div>
    </form>
</div>