@section('meta_title', 'Secure Checkout | Tobac-Go Hookah Store')
@section('meta_description', 'Complete your order securely at Tobac-Go. Enter your shipping details and choose from multiple payment options for your hookah purchase.')

<div x-data="{ 
        fired: false,
        fireConfetti() {
            if (this.fired) return;
            this.fired = true;
            if (typeof confetti === 'function') {
                confetti({ particleCount: 220, spread: 85, origin: { y: 0.65 } });
            } else {
                const s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js';
                s.onload = () => {
                    confetti({ particleCount: 220, spread: 85, origin: { y: 0.65 } });
                };
                document.head.appendChild(s);
            }
        }
    }"
    x-init="
        if ($wire.showSuccess) { fireConfetti(); }
        $watch('$wire.showSuccess', v => { if (v) fireConfetti(); });
    "
    class="max-w-7xl mx-auto px-4 sm:px-6 py-10 space-y-8">

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Checkout</p>
            <h1 class="text-2xl sm:text-3xl font-semibold text-white mt-1">Complete Your Order</h1>
            <p class="text-sm text-slate-400 mt-2">Pay securely with PhonePe online.</p>
        </div>
        <a href="{{ route('cart') }}" wire:navigate class="inline-flex w-full sm:w-auto justify-center items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-white hover:bg-white/10 transition">
            <i class="ri-arrow-left-line"></i>
            Back to Cart
        </a>
    </div>

    @if($showSuccess)
    <div class="rounded-2xl border border-emerald-400/30 bg-emerald-500/10 p-6 text-center">
        <h2 class="text-xl font-semibold text-emerald-100">Congratulations! Your order is successful.</h2>
        <p class="text-sm text-emerald-200 mt-2">Order Number: <span class="font-semibold">{{ $placedOrderNumber }}</span></p>
        <a href="{{ route('home') }}" wire:navigate class="mt-4 inline-flex rounded-md bg-white text-black px-4 py-2 text-sm font-semibold">Go to Home</a>
    </div>
    @elseif($showFailure)
    <div class="rounded-2xl border border-rose-400/30 bg-rose-500/10 p-6">
        <div class="flex items-start gap-3">
            <span class="mt-0.5 inline-flex h-7 w-7 items-center justify-center rounded-full border border-rose-300/50 text-rose-200">
                <i class="ri-error-warning-line"></i>
            </span>
            <div class="min-w-0 flex-1">
                <h2 class="text-lg font-semibold text-rose-100">Payment initiation failed</h2>
                <p class="text-sm text-rose-200/90 mt-1">{{ $failedPaymentMessage ?: 'We could not redirect to PhonePe right now.' }}</p>
                @if($failedOrderNumber)
                <p class="text-xs text-rose-100/80 mt-2">Order Number: <span class="font-semibold">{{ $failedOrderNumber }}</span></p>
                @endif
                <div class="mt-4 flex flex-wrap gap-2">
                    <button type="button" wire:click="openOrderConfirmation" class="inline-flex items-center gap-2 rounded-md bg-white text-black px-4 py-2 text-sm font-semibold hover:opacity-90 transition">
                        <i class="ri-refresh-line"></i>
                        Try Again
                    </button>
                    @if(auth()->check())
                    <a href="{{ route('user.orders') }}" wire:navigate class="inline-flex items-center gap-2 rounded-md border border-white/20 px-4 py-2 text-sm text-white hover:bg-white/5 transition">
                        <i class="ri-file-list-3-line"></i>
                        My Orders
                    </a>
                    @else
                    <a href="{{ route('cart') }}" wire:navigate class="inline-flex items-center gap-2 rounded-md border border-white/20 px-4 py-2 text-sm text-white hover:bg-white/5 transition">
                        <i class="ri-shopping-cart-2-line"></i>
                        Back To Cart
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @elseif(!$cart || $items->isEmpty())
    <div class="rounded-2xl border border-dashed border-white/15 bg-white/3 p-10 text-center text-slate-300">
        No items found for checkout. Please add items to cart first.
        <div class="mt-4">
            <a href="{{ route('products') }}" wire:navigate class="inline-flex rounded-md bg-blue-600 px-4 py-2 text-sm text-white">Browse Products</a>
        </div>
    </div>
    @else
    @php
    $shouldShowAddressForm = $addresses->isEmpty() || $useNewAddress || $editSelectedAddress;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <section class="lg:col-span-2 rounded-2xl border border-white/10 bg-[#0b0d0f] p-4 sm:p-5 space-y-4">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-base font-semibold text-white">Delivery Address</h2>
                @if($addresses->count() > 0)
                <button type="button" wire:click="openNewAddressForm" class="text-[11px] sm:text-xs text-blue-300 hover:text-blue-200 whitespace-nowrap">Add New Address</button>
                @endif
            </div>

            @if($addresses->count() > 0)
            <div class="space-y-2">
                <div class="flex items-center justify-between gap-2">
                    <label class="text-xs text-slate-400">Select Saved Address</label>
                    <p class="text-[11px] text-slate-500">Tap a card to choose delivery address</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @foreach($addresses as $address)
                    <div
                        role="button"
                        tabindex="0"
                        wire:click="selectAddress({{ $address->id }})"
                        wire:keydown.enter="selectAddress({{ $address->id }})"
                        wire:keydown.space.prevent="selectAddress({{ $address->id }})"
                        class="text-left rounded-lg border p-3 transition {{ (int) $selectedAddressId === (int) $address->id && !$useNewAddress ? 'border-blue-400/70 bg-blue-500/10 ring-1 ring-blue-400/40' : 'border-white/10 bg-white/2 hover:border-white/20' }}">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <p class="text-sm font-semibold text-white truncate">{{ $address->full_name }}</p>
                                    <span class="text-[10px] px-1.5 py-0.5 rounded-full border border-slate-400/30 text-slate-300 uppercase tracking-[0.08em]">{{ ucfirst($address->type) }}</span>
                                    @if($address->is_default)
                                    <span class="text-[10px] px-1.5 py-0.5 rounded-full border border-emerald-400/30 text-emerald-300">Default</span>
                                    @endif
                                    @if((int) $selectedAddressId === (int) $address->id && !$useNewAddress)
                                    <span class="text-[10px] px-1.5 py-0.5 rounded-full border border-blue-400/40 text-blue-300">Selected</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-300 mt-1 truncate">{{ $address->address_line1 }}{{ $address->address_line2 ? ', ' . $address->address_line2 : '' }}</p>
                                <p class="text-xs text-slate-400 mt-1">{{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}</p>
                            </div>
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full border {{ (int) $selectedAddressId === (int) $address->id && !$useNewAddress ? 'border-blue-300 bg-blue-500/20 text-blue-200' : 'border-white/25 text-slate-400' }}">
                                <i class="ri-check-line text-xs"></i>
                            </span>
                        </div>
                        <div class="mt-2 flex justify-end">
                            <button
                                type="button"
                                wire:click.stop="editAddress({{ $address->id }})"
                                class="inline-flex items-center gap-1 text-[11px] text-blue-300 hover:text-blue-200">
                                <i class="ri-edit-line"></i>
                                Edit
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($addresses->count() > 0)
            <div class="rounded-lg border border-white/10 bg-white/2 p-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-xs text-slate-400">Currently Selected For Delivery</p>
                    <p class="text-sm text-white font-medium truncate">{{ $fullName ?: 'Select an address' }}</p>
                    @if($selectedAddressId && !$useNewAddress)
                    <p class="text-[11px] text-slate-400 mt-0.5 truncate">{{ $addressLine1 }}{{ $city ? ', ' . $city : '' }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Type: <span class="text-slate-200">{{ ucfirst($addressType) }}</span></p>
                    @endif
                </div>
                <button type="button" wire:click="startEditSelectedAddress" class="w-full sm:w-auto text-xs rounded-md border border-white/20 px-3 py-1.5 text-white hover:bg-white/5">Edit Address</button>
            </div>
            @endif

            @if($shouldShowAddressForm)
            <div
                x-data="{
                        pinLoading: false,
                        async lookupPincode(pin) {
                            if (!/^\d{6}$/.test(pin)) {
                                $wire.set('pincodeHint', null);
                                return;
                            }

                            this.pinLoading = true;
                            try {
                                const res = await fetch('https://api.postalpincode.in/pincode/' + pin);
                                const data = await res.json();
                                const row = Array.isArray(data) ? data[0] : null;
                                const po = row && row.PostOffice && row.PostOffice.length ? row.PostOffice[0] : null;

                                if (po) {
                                    $wire.set('state', po.State || '');
                                    $wire.set('city', po.District || po.Name || '');
                                    $wire.set('country', po.Country || 'India');
                                    $wire.set('pincodeHint', 'State and city auto-filled from pincode.');
                                } else {
                                    $wire.set('pincodeHint', 'No location found for this pincode.');
                                }
                            } catch (e) {
                                $wire.set('pincodeHint', 'Pincode lookup failed. Please fill state and city manually.');
                            }
                            this.pinLoading = false;
                        }
                    }"
                class="space-y-3">
                @if($editSelectedAddress)
                <div class="rounded-md border border-blue-400/30 bg-blue-500/10 px-3 py-2 text-xs text-blue-200">
                    You are editing selected address.
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div>
                        <label class="mb-1 block text-xs text-slate-400">Full Name</label>
                        <input wire:model.defer="fullName" type="text" placeholder="Full Name" class="w-full rounded-md border border-white/15 bg-white/3 px-3 py-2 text-sm text-white placeholder:text-slate-500 outline-none focus:border-blue-500">
                        @error('fullName') <p class="text-xs text-rose-300 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-xs text-slate-400">Phone Number</label>
                        <input wire:model.defer="phone" type="text" placeholder="Phone Number" class="w-full rounded-md border border-white/15 bg-white/3 px-3 py-2 text-sm text-white placeholder:text-slate-500 outline-none focus:border-blue-500">
                        @error('phone') <p class="text-xs text-rose-300 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-xs text-slate-400">Email</label>
                        <input wire:model.defer="email" type="email" placeholder="Email (optional)" class="w-full rounded-md border border-white/15 bg-white/3 px-3 py-2 text-sm text-white placeholder:text-slate-500 outline-none focus:border-blue-500">
                        @error('email') <p class="text-xs text-rose-300 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-xs text-slate-400">Address Line 1</label>
                        <input wire:model.defer="addressLine1" type="text" placeholder="Address Line 1" class="w-full rounded-md border border-white/15 bg-white/3 px-3 py-2 text-sm text-white placeholder:text-slate-500 outline-none focus:border-blue-500">
                        @error('addressLine1') <p class="text-xs text-rose-300 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-xs text-slate-400">Address Line 2</label>
                        <input wire:model.defer="addressLine2" type="text" placeholder="Address Line 2 (optional)" class="w-full rounded-md border border-white/15 bg-white/3 px-3 py-2 text-sm text-white placeholder:text-slate-500 outline-none focus:border-blue-500">
                        @error('addressLine2') <p class="text-xs text-rose-300 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-xs text-slate-400">Landmark</label>
                        <input wire:model.defer="landmark" type="text" placeholder="Landmark (optional)" class="w-full rounded-md border border-white/15 bg-white/3 px-3 py-2 text-sm text-white placeholder:text-slate-500 outline-none focus:border-blue-500">
                        @error('landmark') <p class="text-xs text-rose-300 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-xs text-slate-400">Address Type</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="inline-flex items-center justify-center rounded-md border border-white/15 bg-white/3 px-3 py-2 text-xs sm:text-sm text-slate-200">
                                <input wire:model="addressType" type="radio" value="home" class="mr-2 text-blue-500 focus:ring-blue-500/20">
                                Home
                            </label>
                            <label class="inline-flex items-center justify-center rounded-md border border-white/15 bg-white/3 px-3 py-2 text-xs sm:text-sm text-slate-200">
                                <input wire:model="addressType" type="radio" value="work" class="mr-2 text-blue-500 focus:ring-blue-500/20">
                                Work
                            </label>
                            <label class="inline-flex items-center justify-center rounded-md border border-white/15 bg-white/3 px-3 py-2 text-xs sm:text-sm text-slate-200">
                                <input wire:model="addressType" type="radio" value="other" class="mr-2 text-blue-500 focus:ring-blue-500/20">
                                Other
                            </label>
                        </div>
                        @error('addressType') <p class="text-xs text-rose-300 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-xs text-slate-400">Pincode</label>
                        <input wire:model.live="pincode" x-on:input.debounce.200ms="lookupPincode($event.target.value)" type="text" maxlength="6" placeholder="Pincode" class="w-full rounded-md border border-white/15 bg-white/3 px-3 py-2 text-sm text-white placeholder:text-slate-500 outline-none focus:border-blue-500">
                        @error('pincode') <p class="text-xs text-rose-300 mt-1">{{ $message }}</p> @enderror
                        @if($isPincodeLoading)
                        <p class="text-xs text-blue-300 mt-1">Checking pincode...</p>
                        @elseif($pincodeHint)
                        <p class="text-xs text-slate-400 mt-1">{{ $pincodeHint }}</p>
                        @endif
                    </div>

                    <div>
                        <label class="mb-1 block text-xs text-slate-400">City</label>
                        <input wire:model.defer="city" type="text" placeholder="City" class="w-full rounded-md border border-white/15 bg-white/3 px-3 py-2 text-sm text-white placeholder:text-slate-500 outline-none focus:border-blue-500">
                        @error('city') <p class="text-xs text-rose-300 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-xs text-slate-400">State</label>
                        <input wire:model.defer="state" type="text" placeholder="State" class="w-full rounded-md border border-white/15 bg-white/3 px-3 py-2 text-sm text-white placeholder:text-slate-500 outline-none focus:border-blue-500">
                        @error('state') <p class="text-xs text-rose-300 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-xs text-slate-400">Country</label>
                        <input wire:model.defer="country" type="text" placeholder="Country" class="w-full rounded-md border border-white/15 bg-white/3 px-3 py-2 text-sm text-white placeholder:text-slate-500 outline-none focus:border-blue-500">
                        @error('country') <p class="text-xs text-rose-300 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-xs text-slate-400">Order Note</label>
                        <textarea wire:model.defer="customerNote" rows="2" placeholder="Order note (optional)" class="w-full rounded-md border border-white/15 bg-white/3 px-3 py-2 text-sm text-white placeholder:text-slate-500 outline-none focus:border-blue-500"></textarea>
                        @error('customerNote') <p class="text-xs text-rose-300 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                @if(!$useNewAddress && $selectedAddressId && $editSelectedAddress)
                <button type="button" wire:click="saveAddressChanges" class="w-full sm:w-auto rounded-md border border-white/20 px-4 py-2 text-sm text-white hover:bg-white/5">Save Address Changes</button>
                @endif

                @if($addresses->count() > 0)
                <button type="button" wire:click="cancelAddressForm" class="w-full sm:w-auto rounded-md border border-white/20 px-4 py-2 text-sm text-white hover:bg-white/5">Cancel</button>
                @endif
            </div>

            @if($useNewAddress && auth()->check())
            <label class="inline-flex items-center gap-2 text-xs text-slate-300">
                <input type="checkbox" wire:model="saveAddressForLater" class="rounded border-white/20 bg-transparent text-blue-500 focus:ring-blue-500/20">
                Save this new address for future orders
            </label>
            @endif
            @endif
        </section>

        <aside class="rounded-2xl border border-white/10 bg-[#0b0d0f] p-4 sm:p-5 space-y-5 h-fit lg:sticky lg:top-24">
            <h2 class="text-base font-semibold text-white">Payment & Summary</h2>

            <div class="space-y-2">
                <input type="hidden" wire:model="paymentMethod" value="online">
                <div class="rounded-md border border-blue-400/30 bg-blue-500/10 px-3 py-2 text-sm text-blue-100">
                    Online Payment (PhonePe)
                </div>
                @error('paymentMethod') <p class="text-xs text-rose-300">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-2 text-sm">
                <div class="flex items-center justify-between text-slate-300">
                    <span>Subtotal</span>
                    <span>Rs {{ number_format((float) ($cart->subtotal ?? 0), 2) }}</span>
                </div>
                <div class="flex items-center justify-between text-slate-300">
                    <span>Discount</span>
                    <span>- Rs {{ number_format((float) ($cart->discount ?? 0), 2) }}</span>
                </div>
                <div class="flex items-center justify-between text-slate-300">
                    <span>Shipping</span>
                    <span>Rs {{ number_format((float) ($shippingAmount ?? 0), 2) }}</span>
                </div>
                <div class="border-t border-white/10 pt-2 flex items-center justify-between text-white font-semibold">
                    <span>Grand Total</span>
                    <span>Rs {{ number_format((float) ($grandTotal ?? 0), 2) }}</span>
                </div>
            </div>

            <button type="button" wire:click="openOrderConfirmation" wire:loading.attr="disabled" wire:target="openOrderConfirmation" class="w-full rounded-md bg-white text-black py-2.5 text-sm font-semibold hover:opacity-90 transition disabled:opacity-60">
                <span wire:loading.remove wire:target="openOrderConfirmation">Review & Confirm Order</span>
                <span wire:loading wire:target="openOrderConfirmation">Preparing Review...</span>
            </button>
        </aside>
    </div>

    @if($showConfirmationSlide)
    <div class="fixed inset-0 z-100">
        <button type="button" wire:click="closeOrderConfirmation" class="absolute inset-0 bg-black/70"></button>

        <div class="absolute inset-x-0 bottom-[calc(env(safe-area-inset-bottom,0px)+4.25rem)] md:bottom-0 md:inset-y-0 md:right-0 md:left-auto flex items-end md:items-stretch md:justify-end">
            <div class="w-full h-[calc(100dvh-env(safe-area-inset-bottom,0px)-4.25rem)] max-h-[calc(100dvh-env(safe-area-inset-bottom,0px)-4.25rem)] sm:h-auto sm:max-h-[calc(100dvh-1rem)] md:h-full md:max-h-none md:max-w-xl lg:max-w-2xl border border-white/10 md:border-y-0 md:border-r-0 bg-[#0b0d0f] rounded-t-2xl md:rounded-none shadow-2xl overflow-hidden flex flex-col">
                <div class="px-4 sm:px-5 md:px-6 py-3 border-b border-white/10 bg-[#0b0d0f]/95 backdrop-blur sticky top-0 z-10">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h3 class="text-base sm:text-lg font-semibold text-white">Confirm Your Order</h3>
                            <p class="text-[11px] sm:text-xs text-slate-400 mt-0.5">Verify details and continue to PhonePe payment.</p>
                        </div>
                        <button type="button" wire:click="closeOrderConfirmation" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-white/20 text-slate-300 hover:bg-white/5">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto px-4 sm:px-5 md:px-6 py-4 space-y-4">
                    <div class="rounded-lg border border-white/10 bg-white/2 p-3 sm:p-4">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-xs uppercase tracking-[0.12em] text-slate-400">Items</p>
                            <span class="text-[11px] text-slate-400">{{ $items->count() }} products</span>
                        </div>
                        <div class="mt-2.5 space-y-2">
                            @foreach($items as $item)
                            <div class="rounded-md border border-white/10 bg-[#0a0c0e] p-2.5">
                                <div class="flex items-start gap-2.5">
                                    @php
                                    $thumb = $item->product?->images?->firstWhere('is_primary', true)?->image
                                    ?? $item->product?->images?->first()?->image;
                                    @endphp
                                    <img src="{{ $thumb ? (str_starts_with($thumb, 'http') ? $thumb : asset('storage/' . ltrim($thumb, '/'))) : asset('images/hero.png') }}" alt="{{ $item->product?->name }}" class="h-12 w-12 rounded object-cover border border-white/10 shrink-0">

                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm text-white truncate">{{ $item->product?->name ?? 'Product' }}</p>
                                        <div class="mt-1 flex flex-wrap items-center gap-1.5 text-[11px] text-slate-400">
                                            <span class="inline-flex rounded-full border border-white/15 px-2 py-0.5">Qty {{ (int) $item->quantity }}</span>
                                            <span>x</span>
                                            <span>Rs {{ number_format((float) $item->price, 2) }}</span>
                                        </div>
                                    </div>

                                    <p class="text-sm font-medium text-slate-200 whitespace-nowrap">Rs {{ number_format((float) $item->total, 2) }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-lg border border-white/10 bg-white/2 p-3 sm:p-4">
                        <p class="text-xs uppercase tracking-[0.12em] text-slate-400">Delivery Address</p>
                        <p class="mt-2 text-sm text-white">{{ $fullName }}</p>
                        <p class="text-xs text-slate-300 mt-1 leading-relaxed">{{ $addressLine1 }}{{ $addressLine2 ? ', ' . $addressLine2 : '' }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ $city }}, {{ $state }} - {{ $pincode }}</p>
                        <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-300">
                            <span>{{ $country }}</span>
                            <span class="text-slate-500">|</span>
                            <span class="inline-flex rounded-full border border-slate-400/30 px-2 py-0.5 uppercase tracking-[0.08em]">{{ ucfirst($addressType) }}</span>
                        </div>
                        <p class="text-xs text-slate-300 mt-1">Phone: {{ $phone }}</p>
                        @if($email)
                        <p class="text-xs text-slate-300 mt-1 break-all">Email: {{ $email }}</p>
                        @endif
                    </div>

                    <div class="rounded-lg border border-white/10 bg-white/2 p-3 sm:p-4 space-y-2 text-sm">
                        <p class="text-xs uppercase tracking-[0.12em] text-slate-400">Payment & Totals</p>
                        <div class="flex items-center justify-between text-slate-300">
                            <span>Payment</span>
                            <span class="inline-flex rounded-full border border-blue-400/30 bg-blue-500/10 px-2 py-0.5 text-xs text-blue-100">PHONEPE ONLINE</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-300">
                            <span>Subtotal</span>
                            <span>Rs {{ number_format((float) ($cart->subtotal ?? 0), 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-300">
                            <span>Discount</span>
                            <span>- Rs {{ number_format((float) ($cart->discount ?? 0), 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-300">
                            <span>Shipping</span>
                            <span>Rs {{ number_format((float) ($shippingAmount ?? 0), 2) }}</span>
                        </div>
                        <div class="border-t border-white/10 pt-2 flex items-center justify-between text-white font-semibold">
                            <span>Grand Total</span>
                            <span>Rs {{ number_format((float) ($grandTotal ?? 0), 2) }}</span>
                        </div>
                    </div>

                    @if($customerNote)
                    <div class="rounded-lg border border-white/10 bg-white/2 p-3 sm:p-4">
                        <p class="text-xs uppercase tracking-[0.12em] text-slate-400">Order Note</p>
                        <p class="text-xs text-slate-300 mt-1 leading-relaxed">{{ $customerNote }}</p>
                    </div>
                    @endif
                </div>

                <div class="border-t border-white/10 px-4 sm:px-5 md:px-6 py-3 pb-[calc(0.75rem+env(safe-area-inset-bottom))] bg-[#0b0d0f]/95 backdrop-blur sticky bottom-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <button type="button" wire:click="closeOrderConfirmation" class="rounded-md border border-white/20 px-4 py-2.5 text-sm text-white hover:bg-white/5">Back</button>
                        <button type="button" wire:click="placeOrder" wire:loading.attr="disabled" wire:target="placeOrder" class="rounded-md bg-white text-black px-4 py-2.5 text-sm font-semibold hover:opacity-90 transition disabled:opacity-60">
                            <span wire:loading.remove wire:target="placeOrder">Proceed To PhonePe</span>
                            <span wire:loading wire:target="placeOrder">Connecting To PhonePe...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif
</div>
