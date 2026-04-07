<div
    x-data="{ modalOpen: false }"
    x-on:open-inventory-adjust-modal.window="modalOpen = true"
    x-on:close-inventory-adjust-modal.window="modalOpen = false; $wire.resetAdjustForm()"
    x-cloak
>
    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4">
            {{-- Overlay --}}
            <div @click="modalOpen=false; $wire.resetAdjustForm()" class="absolute inset-0 bg-slate-900/10 backdrop-blur-[2px]"></div>

            {{-- Modal --}}
            <div
                x-show="modalOpen"
                x-transition
                x-trap.inert.noscroll="modalOpen"
                class="relative w-full max-w-md bg-white rounded-xl shadow-2xl flex flex-col overflow-hidden my-auto"
            >
                {{-- Header --}}
                <div class="px-5 py-3 border-b border-slate-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold text-[#002B5B]">Adjust Stock</h3>
                        <p class="text-[9px] text-slate-400 font-medium leading-none mt-0.5">{{ $productName }}</p>
                    </div>
                    <button @click="modalOpen=false; $wire.resetAdjustForm()" class="text-slate-300 hover:text-slate-500 transition">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="flex-1 px-5 py-5 space-y-4 text-sm">
                    {{-- Adjustment Type --}}
                    <div class="space-y-2">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Adjustment Type</label>
                        <div class="grid grid-cols-4 sm:grid-cols-7 gap-1 pt-0.5">
                            @foreach(['in' => 'In', 'out' => 'Out', 'sale' => 'Sale', 'return' => 'Ret', 'adjust' => 'Adj', 'reserve' => 'Res', 'release' => 'Rel'] as $key => $label)
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model.live="type" value="{{ $key }}" class="peer hidden">
                                    <div class="py-1.5 text-center rounded-lg border border-slate-100 text-[9px] font-bold uppercase transition 
                                        peer-checked:bg-[#EFFFF6] peer-checked:text-[#00D47E] peer-checked:border-[#00D47E]
                                        hover:bg-slate-50">
                                        {{ $label }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Quantity --}}
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Quantity Change</label>
                        <input
                            wire:model="quantity"
                            type="number"
                            placeholder="0"
                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 outline-none transition"
                        >
                    </div>

                    {{-- Reference --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Reference Type</label>
                            <select
                                wire:model.live="reference_type"
                                class="w-full rounded-lg border border-slate-200 bg-white px-2 py-2 text-[11px] focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 outline-none transition"
                            >
                                <option value="">None</option>
                                <option value="Order No">Order No</option>
                                <option value="Customer">Customer</option>
                                <option value="Supplier">Supplier</option>
                                <option value="SKU / Variant">SKU / Variant</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Reference ID</label>
                            <input
                                wire:model="reference_id"
                                type="text"
                                placeholder="#"
                                class="w-full rounded-lg border border-slate-200 bg-white px-2 py-2 text-[11px] focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 outline-none transition"
                            >
                        </div>
                    </div>

                    {{-- Note --}}
                    <div class="space-y-1.5">
                        <label class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Note (Optional)</label>
                        <textarea
                            wire:model="note"
                            rows="2"
                            placeholder="Reason..."
                            class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 outline-none transition resize-none"
                        ></textarea>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-5 py-3 border-t border-slate-50 bg-slate-50/10 flex items-center justify-end gap-3">
                    <button @click="modalOpen=false; $wire.resetAdjustForm()" class="text-[9px] font-bold text-slate-400 hover:text-slate-600 uppercase tracking-widest">Cancel</button>
                    <button
                        wire:click="updateStock"
                        wire:loading.attr="disabled"
                        class="px-5 py-2 rounded-lg bg-blue-600 text-white text-[9px] font-bold uppercase tracking-widest shadow-sm hover:bg-blue-700 active:scale-95 transition-all"
                    >
                        Update Stock
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
