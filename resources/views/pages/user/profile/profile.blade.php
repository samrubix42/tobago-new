<div class="min-h-screen bg-[#060707] py-10 px-4">
<div class="max-w-3xl mx-auto space-y-6">

    {{-- ── Page title ── --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold text-white tracking-tight">My Profile</h1>
            <p class="text-sm text-white/40 mt-0.5">Manage your personal information and security</p>
        </div>
        <a href="{{ route('user.address') }}"
           class="flex items-center gap-2 px-4 py-2 rounded-xl border border-white/10 bg-white/5 text-sm text-white/70 hover:text-white transition-all">
            <i class="ri-map-pin-line"></i> My Addresses
        </a>
    </div>

    {{-- ── Profile info card ── --}}
    <div class="rounded-2xl border border-white/8 bg-white/[0.03] p-6 space-y-6">

        <h2 class="text-sm font-semibold text-white/70 uppercase tracking-widest text-[11px]">Personal Information</h2>

        {{-- Avatar --}}
        <div class="flex items-center gap-5">
            <div class="relative flex-shrink-0">
                @if ($photo)
                    <img src="{{ $photo->temporaryUrl() }}"
                         class="w-20 h-20 rounded-2xl object-cover border border-white/10" alt="Preview">
                @elseif ($currentAvatar)
                    <img src="{{ str_starts_with($currentAvatar, 'http') ? $currentAvatar : Storage::url($currentAvatar) }}"
                         class="w-20 h-20 rounded-2xl object-cover border border-white/10" alt="Avatar">
                @else
                    <div class="w-20 h-20 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    </div>
                @endif

                <label class="absolute -bottom-1.5 -right-1.5 w-7 h-7 rounded-lg bg-white flex items-center justify-center cursor-pointer hover:bg-white/90 transition-all shadow-lg">
                    <i class="ri-camera-line text-black text-sm"></i>
                    <input type="file" wire:model="photo" accept="image/*" class="hidden">
                </label>
            </div>

            <div>
                <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                <p class="text-xs text-white/40 mt-0.5">{{ auth()->user()->email }}</p>
                <p class="text-xs text-white/30 mt-1">JPG, PNG or WebP · Max 2MB</p>
            </div>
        </div>

        @if ($saved)
            <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
                <i class="ri-checkbox-circle-line"></i> Profile updated successfully!
            </div>
        @endif

        <form wire:submit="updateProfile" class="space-y-4">

            <div class="grid sm:grid-cols-2 gap-4">
                {{-- Name --}}
                <div>
                    <label class="block text-xs font-medium text-white/50 mb-1.5">Full Name</label>
                    <div class="relative">
                        <i class="ri-user-3-line absolute left-3.5 top-1/2 -translate-y-1/2 text-white/30 text-sm"></i>
                        <input type="text" wire:model="name" id="profile-name"
                               class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-white/5 border border-white/8 text-white text-sm placeholder-white/25 focus:outline-none focus:border-white/25 transition-all"
                               placeholder="Your full name">
                    </div>
                    @error('name') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-xs font-medium text-white/50 mb-1.5">Phone Number</label>
                    <div class="relative">
                        <i class="ri-phone-line absolute left-3.5 top-1/2 -translate-y-1/2 text-white/30 text-sm"></i>
                        <input type="tel" wire:model="phone" id="profile-phone"
                               class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-white/5 border border-white/8 text-white text-sm placeholder-white/25 focus:outline-none focus:border-white/25 transition-all"
                               placeholder="+91 00000 00000">
                    </div>
                    @error('phone') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-xs font-medium text-white/50 mb-1.5">Email Address</label>
                <div class="relative">
                    <i class="ri-mail-line absolute left-3.5 top-1/2 -translate-y-1/2 text-white/30 text-sm"></i>
                    <input type="email" wire:model="email" id="profile-email"
                           class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-white/5 border border-white/8 text-white text-sm placeholder-white/25 focus:outline-none focus:border-white/25 transition-all"
                           placeholder="you@example.com">
                </div>
                @error('email') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end pt-1">
                <button type="submit" wire:loading.attr="disabled"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-black text-sm font-semibold hover:bg-white/90 active:scale-[0.98] transition-all disabled:opacity-60">
                    <svg wire:loading wire:target="updateProfile" class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="10" stroke="rgba(0,0,0,0.2)" stroke-width="3"/>
                        <path d="M12 2a10 10 0 0 1 10 10" stroke="#000" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                    <span wire:loading.remove wire:target="updateProfile">Save Changes</span>
                    <span wire:loading wire:target="updateProfile">Saving…</span>
                </button>
            </div>

        </form>
    </div>

    {{-- ── Change password card ── --}}
    @if(auth()->user()->password)
    <div class="rounded-2xl border border-white/8 bg-white/[0.03] p-6 space-y-5">

        <h2 class="text-sm font-semibold text-white/70 uppercase tracking-widest text-[11px]">Change Password</h2>

        @if ($passwordSaved)
            <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
                <i class="ri-checkbox-circle-line"></i> Password changed successfully!
            </div>
        @endif

        <form wire:submit="updatePassword" class="space-y-4">

            <div>
                <label class="block text-xs font-medium text-white/50 mb-1.5">Current Password</label>
                <div class="relative">
                    <i class="ri-lock-line absolute left-3.5 top-1/2 -translate-y-1/2 text-white/30 text-sm"></i>
                    <input type="password" wire:model="current_password" id="current-password"
                           class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-white/5 border border-white/8 text-white text-sm placeholder-white/25 focus:outline-none focus:border-white/25 transition-all"
                           placeholder="Enter current password">
                </div>
                @error('current_password') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-white/50 mb-1.5">New Password</label>
                    <div class="relative">
                        <i class="ri-lock-password-line absolute left-3.5 top-1/2 -translate-y-1/2 text-white/30 text-sm"></i>
                        <input type="password" wire:model="new_password" id="new-password"
                               class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-white/5 border border-white/8 text-white text-sm placeholder-white/25 focus:outline-none focus:border-white/25 transition-all"
                               placeholder="Min. 8 characters">
                    </div>
                    @error('new_password') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-white/50 mb-1.5">Confirm New Password</label>
                    <div class="relative">
                        <i class="ri-lock-password-line absolute left-3.5 top-1/2 -translate-y-1/2 text-white/30 text-sm"></i>
                        <input type="password" wire:model="new_password_confirm" id="confirm-password"
                               class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-white/5 border border-white/8 text-white text-sm placeholder-white/25 focus:outline-none focus:border-white/25 transition-all"
                               placeholder="Re-enter new password">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-1">
                <button type="submit" wire:loading.attr="disabled"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white text-black text-sm font-semibold hover:bg-white/90 active:scale-[0.98] transition-all disabled:opacity-60">
                    <span wire:loading.remove wire:target="updatePassword">Update Password</span>
                    <span wire:loading wire:target="updatePassword">Updating…</span>
                </button>
            </div>

        </form>
    </div>
    @else
    <div class="rounded-2xl border border-white/8 bg-white/[0.03] p-5 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center flex-shrink-0">
            <i class="ri-google-line text-white/50"></i>
        </div>
        <div>
            <p class="text-sm text-white/70 font-medium">Signed in with Google</p>
            <p class="text-xs text-white/35 mt-0.5">Password management is handled by your Google account.</p>
        </div>
    </div>
    @endif

    {{-- ── Danger zone ── --}}
    <div class="rounded-2xl border border-red-500/15 bg-red-500/[0.04] p-6 flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-white/70">Delete Account</p>
            <p class="text-xs text-white/35 mt-0.5">Permanently remove your account and all data. This cannot be undone.</p>
        </div>
        <button class="flex-shrink-0 px-4 py-2 rounded-xl border border-red-500/30 text-red-400 text-sm hover:bg-red-500/10 transition-all">
            Delete
        </button>
    </div>

</div>
</div>