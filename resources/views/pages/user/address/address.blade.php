<div class="min-h-screen bg-[#060707] py-10 px-4">
<div class="max-w-3xl mx-auto space-y-6">

    {{-- ── Page title ── --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold text-white tracking-tight">My Addresses</h1>
            <p class="text-sm text-white/40 mt-0.5">Manage your saved delivery addresses</p>
        </div>
        <div class="flex items-center gap-2">
            <a wire:navigate href="{{ route('user.profile') }}"
               class="flex items-center gap-2 px-4 py-2 rounded-xl border border-white/10 bg-white/5 text-sm text-white/70 hover:text-white transition-all">
                <i class="ri-user-line"></i> Profile
            </a>
            @if(!$showForm)
            <button wire:click="openForm()"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-black text-sm font-semibold hover:bg-white/90 transition-all">
                <i class="ri-add-line"></i> Add New
            </button>
            @endif
        </div>
    </div>

    {{-- ── Add / Edit Form ── --}}
    @if($showForm)
    <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-6 space-y-5"
         x-data="{
            pincodeLoading: false,
            async lookupPincode(pin) {
                if (pin.length !== 6 || !/^\d{6}$/.test(pin)) return;
                this.pincodeLoading = true;
                try {
                    const res = await fetch('https://api.postalpincode.in/pincode/' + pin);
                    const data = await res.json();
                    if (data[0].Status === 'Success' && data[0].PostOffice.length > 0) {
                        const po = data[0].PostOffice[0];
                        $wire.set('city', po.District);
                        $wire.set('state', po.State);
                        $wire.set('country', 'India');
                    }
                } catch(e) {}
                this.pincodeLoading = false;
            }
         }">

        <div class="flex items-center justify-between">
            <h2 class="text-sm font-semibold text-white">
                {{ $editingId ? 'Edit Address' : 'Add New Address' }}
            </h2>
            <button wire:click="$set('showForm', false)" type="button"
                    class="w-8 h-8 rounded-xl bg-white/5 flex items-center justify-center text-white/50 hover:text-white transition-all">
                <i class="ri-close-line"></i>
            </button>
        </div>

        <form wire:submit="save" class="space-y-4">

            {{-- ── Type selector (wire:click — fixes the broken highlight) ── --}}
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex gap-2">
                    <button type="button" wire:click="$set('type','home')"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-sm transition-all {{ $type === 'home' ? 'border-white/30 bg-white/8 text-white' : 'border-white/8 text-white/45 hover:text-white/70' }}">
                        <i class="ri-home-4-line"></i> Home
                    </button>
                    <button type="button" wire:click="$set('type','work')"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-sm transition-all {{ $type === 'work' ? 'border-white/30 bg-white/8 text-white' : 'border-white/8 text-white/45 hover:text-white/70' }}">
                        <i class="ri-building-line"></i> Work
                    </button>
                    <button type="button" wire:click="$set('type','other')"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-sm transition-all {{ $type === 'other' ? 'border-white/30 bg-white/8 text-white' : 'border-white/8 text-white/45 hover:text-white/70' }}">
                        <i class="ri-map-pin-line"></i> Other
                    </button>
                </div>
                <label class="flex items-center gap-2 text-sm text-white/60 cursor-pointer ml-auto">
                    <input type="checkbox" wire:model="is_default" class="w-4 h-4 accent-white rounded">
                    Set as default
                </label>
            </div>

            {{-- Recipient --}}
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-white/50 mb-1.5">Full Name *</label>
                    <input type="text" wire:model="full_name" placeholder="Recipient name"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-white/5 border border-white/8 text-white text-sm placeholder-white/25 focus:outline-none focus:border-white/25 transition-all">
                    @error('full_name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-white/50 mb-1.5">Phone *</label>
                    <input type="tel" wire:model="phone" placeholder="+91 00000 00000"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-white/5 border border-white/8 text-white text-sm placeholder-white/25 focus:outline-none focus:border-white/25 transition-all">
                    @error('phone') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-white/50 mb-1.5">Alternate Phone</label>
                <input type="tel" wire:model="alternate_phone" placeholder="Optional"
                       class="w-full px-3.5 py-2.5 rounded-xl bg-white/5 border border-white/8 text-white text-sm placeholder-white/25 focus:outline-none focus:border-white/25 transition-all">
            </div>

            {{-- Address lines --}}
            <div>
                <label class="block text-xs font-medium text-white/50 mb-1.5">House / Flat / Building *</label>
                <input type="text" wire:model="address_line1" placeholder="e.g. Flat 301, Sunshine Apartments"
                       class="w-full px-3.5 py-2.5 rounded-xl bg-white/5 border border-white/8 text-white text-sm placeholder-white/25 focus:outline-none focus:border-white/25 transition-all">
                @error('address_line1') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-medium text-white/50 mb-1.5">Street / Area / Locality</label>
                <input type="text" wire:model="address_line2" placeholder="e.g. MG Road, Koramangala"
                       class="w-full px-3.5 py-2.5 rounded-xl bg-white/5 border border-white/8 text-white text-sm placeholder-white/25 focus:outline-none focus:border-white/25 transition-all">
            </div>

            <div>
                <label class="block text-xs font-medium text-white/50 mb-1.5">Landmark</label>
                <input type="text" wire:model="landmark" placeholder="e.g. Near City Mall"
                       class="w-full px-3.5 py-2.5 rounded-xl bg-white/5 border border-white/8 text-white text-sm placeholder-white/25 focus:outline-none focus:border-white/25 transition-all">
            </div>

            {{-- ── Pincode (auto-fill) ── --}}
            <div>
                <label class="block text-xs font-medium text-white/50 mb-1.5">
                    Pincode *
                    <span x-show="pincodeLoading" class="ml-2 inline-flex items-center gap-1 text-white/30 font-normal">
                        <svg class="animate-spin w-3 h-3" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="32" stroke-dashoffset="10"/>
                        </svg>
                        Looking up…
                    </span>
                </label>
                <div class="relative">
                    <input type="text" wire:model="pincode"
                           maxlength="6" placeholder="e.g. 560001"
                           x-on:input.debounce.600ms="lookupPincode($event.target.value)"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-white/5 border border-white/8 text-white text-sm placeholder-white/25 focus:outline-none focus:border-white/25 transition-all">
                    <span x-show="pincodeLoading"
                          class="absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin w-4 h-4 text-white/30" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="32" stroke-dashoffset="10"/>
                        </svg>
                    </span>
                </div>
                @error('pincode') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-white/25 mt-1">City, State and Country will auto-fill for Indian pincodes</p>
            </div>

            {{-- City + State + Country (auto-filled) --}}
            <div class="grid sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-white/50 mb-1.5">City *</label>
                    <input type="text" wire:model="city" placeholder="Auto-filled"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-white/5 border border-white/8 text-white text-sm placeholder-white/20 focus:outline-none focus:border-white/25 transition-all">
                    @error('city') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-white/50 mb-1.5">State *</label>
                    <input type="text" wire:model="state" placeholder="Auto-filled"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-white/5 border border-white/8 text-white text-sm placeholder-white/20 focus:outline-none focus:border-white/25 transition-all">
                    @error('state') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-white/50 mb-1.5">Country *</label>
                    <input type="text" wire:model="country" placeholder="India"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-white/5 border border-white/8 text-white text-sm placeholder-white/20 focus:outline-none focus:border-white/25 transition-all">
                    @error('country') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-3 pt-1">
                <button type="button" wire:click="$set('showForm', false)"
                        class="px-4 py-2.5 rounded-xl border border-white/10 text-sm text-white/60 hover:text-white hover:bg-white/5 transition-all">
                    Cancel
                </button>
                <button type="submit" wire:loading.attr="disabled"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-black text-sm font-semibold hover:bg-white/90 active:scale-[0.98] transition-all disabled:opacity-60">
                    <svg wire:loading wire:target="save" class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="10" stroke="rgba(0,0,0,0.2)" stroke-width="3"/>
                        <path d="M12 2a10 10 0 0 1 10 10" stroke="#000" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                    <span wire:loading.remove wire:target="save">{{ $editingId ? 'Update Address' : 'Save Address' }}</span>
                    <span wire:loading wire:target="save">Saving…</span>
                </button>
            </div>

        </form>
    </div>
    @endif

    {{-- ── Addresses list ── --}}
    @if($addresses->isEmpty() && !$showForm)
    <div class="rounded-2xl border border-dashed border-white/10 p-12 text-center">
        <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center mx-auto mb-4">
            <i class="ri-map-pin-line text-2xl text-white/30"></i>
        </div>
        <p class="text-white/50 font-medium">No addresses saved yet</p>
        <p class="text-sm text-white/30 mt-1 mb-5">Add a delivery address to speed up checkout</p>
        <button wire:click="openForm()"
                class="px-5 py-2.5 rounded-xl bg-white text-black text-sm font-semibold hover:bg-white/90 transition-all">
            Add First Address
        </button>
    </div>
    @else
    <div class="space-y-3">
        @foreach($addresses as $address)
        <div class="rounded-2xl border {{ $address->is_default ? 'border-white/20 bg-white/[0.05]' : 'border-white/8 bg-white/[0.02]' }} p-5">

            <div class="flex items-start justify-between gap-4">

                <div class="flex items-start gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-white/5 border border-white/8 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="{{ $address->type === 'home' ? 'ri-home-4-line' : ($address->type === 'work' ? 'ri-building-line' : 'ri-map-pin-line') }} text-white/50 text-base"></i>
                    </div>

                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-semibold text-white">{{ $address->full_name }}</span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full border {{ $address->type === 'home' ? 'border-sky-500/30 text-sky-400' : ($address->type === 'work' ? 'border-violet-500/30 text-violet-400' : 'border-white/15 text-white/40') }}">
                                {{ ucfirst($address->type) }}
                            </span>
                            @if($address->is_default)
                            <span class="text-[10px] px-2 py-0.5 rounded-full border border-emerald-500/30 text-emerald-400">Default</span>
                            @endif
                        </div>

                        <p class="text-sm text-white/55 mt-1 leading-relaxed">
                            {{ $address->address_line1 }}
                            @if($address->address_line2), {{ $address->address_line2 }}@endif
                            @if($address->landmark), {{ $address->landmark }}@endif
                        </p>
                        <p class="text-sm text-white/55">
                            {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}, {{ $address->country }}
                        </p>
                        <p class="text-xs text-white/35 mt-1">
                            <i class="ri-phone-line mr-1"></i>{{ $address->phone }}
                            @if($address->alternate_phone) · {{ $address->alternate_phone }}@endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-1.5 flex-shrink-0">
                    @if(!$address->is_default)
                    <button wire:click="setDefault({{ $address->id }})"
                            class="px-2.5 py-1.5 rounded-lg border border-white/8 text-xs text-white/40 hover:text-white hover:bg-white/5 transition-all">
                        Set Default
                    </button>
                    @endif
                    <button wire:click="openForm({{ $address->id }})"
                            class="w-8 h-8 rounded-lg bg-white/5 border border-white/8 flex items-center justify-center text-white/50 hover:text-white transition-all">
                        <i class="ri-edit-line text-sm"></i>
                    </button>
                    <button wire:click="$set('confirmingDeletionId', {{ $address->id }})"
                            class="w-8 h-8 rounded-lg bg-white/5 border border-white/8 flex items-center justify-center text-white/50 hover:text-red-400 transition-all">
                        <i class="ri-delete-bin-line text-sm"></i>
                    </button>
                </div>

            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ── Deletion Confirmation Modal ── --}}
    @if($confirmingDeletionId)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-[#060707]/80 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-[#111213] rounded-3xl shadow-2xl border border-white/10 max-w-sm w-full p-6 space-y-6"
             @click.away="$wire.set('confirmingDeletionId', null)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div class="flex flex-col items-center text-center space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-red-500/10 flex items-center justify-center text-red-500">
                    <i class="ri-delete-bin-fill text-3xl"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="text-xl font-semibold text-white">Delete Address?</h3>
                    <p class="text-sm text-white/50 leading-relaxed">
                        This will permanently remove this address from your profile. This action cannot be undone.
                    </p>
                </div>
            </div>

            <div class="flex gap-3">
                <button @click="$wire.set('confirmingDeletionId', null)"
                        class="flex-1 px-4 py-3 rounded-xl border border-white/10 text-sm font-semibold text-white/70 hover:text-white hover:bg-white/5 transition-all">
                    Cancel
                </button>
                <button wire:click="delete({{ $confirmingDeletionId }})"
                        class="flex-1 px-4 py-3 rounded-xl bg-red-600 text-white text-sm font-semibold hover:bg-red-500 active:scale-[0.98] transition-all">
                    Confirm Delete
                </button>
            </div>
        </div>
    </div>
    @endif

</div>
</div>