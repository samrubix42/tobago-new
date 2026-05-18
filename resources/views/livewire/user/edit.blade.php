<div
    x-data="{ editOpen: false }"
    x-on:open-edit-modal.window="editOpen = true"
    x-on:close-edit-modal.window="editOpen = false"
    x-cloak
>
    <template x-teleport="body">
        <div x-show="editOpen" class="fixed inset-0 z-[99] flex items-center justify-center px-4">
            <!-- Backdrop -->
            <div @click="editOpen = false" class="absolute inset-0 bg-black/40"></div>

            <!-- Modal Content -->
            <div
                x-show="editOpen"
                x-transition
                x-trap.inert.noscroll="editOpen"
                class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl border border-slate-100"
            >
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-900">Edit User Profile</h3>
                    <button @click="editOpen = false" class="text-slate-400 hover:text-slate-600 transition">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveUser" class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Full Name</label>
                        <input
                            type="text"
                            wire:model.defer="editName"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 outline-none focus:border-blue-600 focus:bg-white transition"
                            placeholder="User's Full Name"
                        >
                        @error('editName') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Email Address</label>
                        <input
                            type="email"
                            wire:model.defer="editEmail"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 outline-none focus:border-blue-600 focus:bg-white transition"
                            placeholder="user@example.com"
                        >
                        @error('editEmail') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Phone Number</label>
                        <input
                            type="text"
                            wire:model.defer="editPhone"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 outline-none focus:border-blue-600 focus:bg-white transition"
                            placeholder="Phone number"
                        >
                        @error('editPhone') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Role Selector -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Account Role</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center justify-center p-3 rounded-xl border cursor-pointer transition select-none
                                {{ $editIsAdmin ? 'border-slate-200 bg-slate-50 text-slate-600' : 'border-blue-600 bg-blue-50/30 text-blue-600 font-bold' }}">
                                <input type="radio" wire:model="editIsAdmin" :value="false" class="hidden">
                                Customer
                            </label>
                            <label class="flex items-center justify-center p-3 rounded-xl border cursor-pointer transition select-none
                                {{ $editIsAdmin ? 'border-blue-600 bg-blue-50/30 text-blue-600 font-bold' : 'border-slate-200 bg-slate-50 text-slate-600' }}">
                                <input type="radio" wire:model="editIsAdmin" :value="true" class="hidden">
                                Admin
                            </label>
                        </div>
                        @error('editIsAdmin') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Footer Buttons -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 mt-6">
                        <button
                            type="button"
                            @click="editOpen = false"
                            class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 transition"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="inline-flex items-center gap-1 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-blue-500 transition shadow-lg shadow-blue-100"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
