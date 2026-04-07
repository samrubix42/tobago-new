<div
    x-data="{ modalOpen: false }"
    x-on:open-inventory-adjust-modal.window="modalOpen = true"
    x-on:close-inventory-adjust-modal.window="modalOpen = false; $wire.resetAdjustForm()"
    x-cloak
>
    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4">
            {{-- Overlay --}}
            <div @click="modalOpen=false; $wire.resetAdjustForm()" class="absolute inset-0 bg-slate-900/20 backdrop-blur-[2px]"></div>

            {{-- Modal --}}
            <div
                x-show="modalOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                x-trap.inert.noscroll="modalOpen"
                class="relative w-full max-w-2xl bg-white rounded-xl shadow-2xl flex flex-col overflow-hidden"
            >
                {{-- Header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                    <h3 class="text-xl font-bold text-[#002B5B]">Adjust Stock</h3>
                    <button @click="modalOpen=false; $wire.resetAdjustForm()" class="text-slate-400 hover:text-slate-600 transition">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="flex-1 px-8 py-8 space-y-6">
                    {{-- Row 1: Adjustment Type --}}
                    <div class="space-y-3">
                        <label class="text-sm font-semibold text-slate-600">Adjustment Type</label>
                        <div class="flex flex-wrap gap-3">
                            @foreach(['in' => 'IN', 'out' => 'OUT', 'sale' => 'SALE', 'return' => 'RETURN', 'adjust' => 'ADJUST', 'reserve' => 'RESERVE', 'release' => 'RELEASE'] as $key => $label)
                                <button
                                    type="button"
                                    wire:click="$set('type','{{ $key }}')"
                                    class="px-6 py-2.5 rounded-xl text-[11px] font-bold border transition-all duration-200
                                        {{ $type === $key 
                                            ? ($key === 'in' ? 'border-[#00D47E] bg-[#EFFFF6] text-[#00D47E]' : 'border-blue-600 bg-blue-50 text-blue-600') 
                                            : 'border-slate-200 bg-white text-slate-900 hover:border-slate-300' }}"
                                >
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Row 2: Quantity Change --}}
                    <div class="space-y-3">
                        <label class="text-sm font-semibold text-slate-600">Quantity Change</label>
                        <input
                            wire:model="quantity"
                            type="number"
                            placeholder="0"
                            class="w-full rounded-xl border border-slate-200 bg-white px-5 py-3 text-lg text-slate-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 outline-none transition"
                        >
                        @error('quantity')<p class="text-xs text-rose-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Row 3: Reference Info --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <label class="text-sm font-semibold text-slate-600">Reference Type</label>
                            <div class="relative">
                                <select
                                    wire:model="reference_type"
                                    class="w-full appearance-none rounded-xl border border-slate-200 bg-white px-5 py-3 text-base text-slate-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 outline-none transition"
                                >
                                    <option value="">None</option>
                                    <option value="order">Order</option>
                                    <option value="admin">Admin</option>
                                    <option value="purchase">Purchase</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                    <i class="ri-arrow-down-s-line text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-sm font-semibold text-slate-600">Reference ID (Optional)</label>
                            <input
                                wire:model="reference_id"
                                type="text"
                                placeholder="e.g. 1024"
                                class="w-full rounded-xl border border-slate-200 bg-white px-5 py-3 text-base text-slate-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 outline-none transition placeholder:text-slate-300"
                            >
                        </div>
                    </div>

                    {{-- Row 4: Note --}}
                    <div class="space-y-3">
                        <label class="text-sm font-semibold text-slate-600">Note (Optional)</label>
                        <textarea
                            wire:model="note"
                            rows="3"
                            placeholder="e.g. Added stock from supplier X"
                            class="w-full rounded-xl border border-slate-200 bg-white px-5 py-4 text-base text-slate-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 outline-none transition resize-none placeholder:text-slate-300"
                        ></textarea>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-8 py-5 border-t border-slate-100 bg-white flex items-center justify-end gap-4 mt-auto">
                    <button @click="modalOpen=false; $wire.resetAdjustForm()"
                        class="px-8 py-3 rounded-xl border border-slate-200 text-base font-semibold text-[#002B5B] hover:bg-slate-50 transition">
                        Cancel
                    </button>

                    <button
                        wire:click="updateStock"
                        wire:loading.attr="disabled"
                        class="px-8 py-3 rounded-xl bg-[#4F00FF] text-white text-base font-bold shadow-lg shadow-indigo-100 hover:bg-[#4300DB] active:scale-[0.98] transition-all flex items-center gap-2"
                    >
                        <i wire:loading.remove wire:target="updateStock" class="ri-check-line text-xl"></i>
                        <span wire:loading wire:target="updateStock" class="ri-loader-4-line animate-spin text-xl"></span>
                        
                        <span>Update Stock</span>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
