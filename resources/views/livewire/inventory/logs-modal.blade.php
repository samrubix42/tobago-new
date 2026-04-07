<div
    x-data="{ modalOpen: false }"
    x-on:open-inventory-logs-modal.window="modalOpen = true"
    x-on:close-inventory-logs-modal.window="modalOpen = false; $wire.closeLogsModal()"
    x-cloak
>
    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4">
            <div @click="modalOpen=false; $wire.closeLogsModal()" class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

            <div
                x-show="modalOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                x-trap.inert.noscroll="modalOpen"
                class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl max-h-[90vh] flex flex-col overflow-hidden"
            >
                {{-- Header --}}
                <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400">
                            <i class="ri-history-line text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 tracking-tight">Stock Activity</h3>
                            <p class="text-[11px] text-gray-400 uppercase tracking-widest font-bold mt-1">
                                {{ $productName }} &bull; SKU: {{ $productSku ?: 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <button @click="modalOpen=false; $wire.closeLogsModal()"
                        class="h-10 w-10 flex items-center justify-center rounded-xl text-gray-400 hover:bg-gray-50 hover:text-gray-900 transition-all">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto px-8 py-8 bg-white">
                    @if($logs)
                        @php
                            $typeStyles = [
                                'in' => ['icon' => 'ri-add-circle-line', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
                                'out' => ['icon' => 'ri-indeterminate-circle-line', 'color' => 'text-rose-600', 'bg' => 'bg-rose-50'],
                                'sale' => ['icon' => 'ri-shopping-bag-3-line', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
                                'return' => ['icon' => 'ri-reply-line', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50'],
                                'adjust' => ['icon' => 'ri-equalizer-2-line', 'color' => 'text-violet-600', 'bg' => 'bg-violet-50'],
                                'reserve' => ['icon' => 'ri-lock-line', 'color' => 'text-gray-600', 'bg' => 'bg-gray-50'],
                                'release' => ['icon' => 'ri-lock-unlock-line', 'color' => 'text-teal-600', 'bg' => 'bg-teal-50'],
                            ];
                            $increaseTypes = ['in', 'return', 'release', 'replace'];
                            $decreaseTypes = ['out', 'sale', 'reserve'];
                            $runningBalance = (int) ($logsStartBalance ?? $currentStock);
                        @endphp

                        <div class="relative space-y-12">
                            {{-- Vertical Line --}}
                            <div class="absolute left-[23px] top-6 bottom-6 w-px bg-slate-100"></div>

                            @forelse($logs as $log)
                                @php
                                    $meta = $typeMeta[$log->type] ?? ['label' => strtoupper($log->type), 'color' => '#64748b', 'icon' => 'ri-record-circle-line'];
                                    
                                    $rawQty = (int) $log->quantity;
                                    if (in_array($log->type, $increaseTypes, true)) {
                                        $delta = abs($rawQty);
                                    } elseif (in_array($log->type, $decreaseTypes, true)) {
                                        $delta = -abs($rawQty);
                                    } else {
                                        $delta = $rawQty;
                                    }

                                    $after = $runningBalance;
                                    $before = $after - $delta;
                                    $runningBalance = $before;

                                    $qtySign = $delta >= 0 ? '+' : '';
                                    $qtyHint = $delta >= 0 ? 'Added to inventory' : 'Removed from inventory';
                                @endphp

                                <div wire:key="inv-log-{{ $log->id }}" class="relative flex items-start gap-10 group">
                                    {{-- Icon Node --}}
                                    <div class="relative z-10 shrink-0 mt-1">
                                            <div class="h-12 w-12 rounded-full border border-slate-50 bg-white flex items-center justify-center shadow-sm" style="color: {{ $meta['color'] }}">
                                                <div class="h-10 w-10 rounded-full flex items-center justify-center transition-all bg-opacity-5" style="background-color: {{ $meta['color'] }}">
                                                    <i class="{{ $meta['icon'] }} text-xl"></i>
                                                </div>
                                            </div>
                                    </div>

                                    {{-- Content --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-4">
                                            <div class="flex items-center gap-3">
                                                <span class="text-[11px] font-black tracking-widest uppercase" style="color: {{ $meta['color'] }}">
                                                    {{ $meta['label'] }}
                                                </span>

                                                @if($log->reference_type)
                                                    <span class="rounded bg-slate-50 border border-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-400 uppercase tracking-tighter">
                                                        REF: {{ $log->reference_type }} {{ $log->reference_id ? '#'.$log->reference_id : '' }}
                                                    </span>
                                                @endif

                                                <span class="text-xs font-semibold text-slate-300">
                                                    {{ $log->created_at?->format('M d, h:i A') }}
                                                </span>
                                            </div>

                                            {{-- Balance Float --}}
                                            <div class="text-right shrink-0">
                                                <p class="text-[10px] font-black uppercase tracking-widest text-[#B5C0CC]">Balance</p>
                                                <div class="mt-1 flex items-center gap-2 text-xs font-bold">
                                                    <span class="text-slate-300">{{ $before }}</span>
                                                    <i class="ri-arrow-right-s-line text-slate-200"></i>
                                                    <span class="flex items-center justify-center min-w-[32px] h-7 rounded-lg border border-slate-100 bg-white text-slate-900 shadow-sm">
                                                        {{ $after }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-1">
                                            <span class="text-lg font-black text-slate-800 tracking-tight">{{ $qtySign }}{{ $delta }} Units</span>
                                            <span class="ml-1.5 text-xs text-slate-400 font-medium">({{ $qtyHint }})</span>
                                        </div>

                                        @if($log->note)
                                            <p class="mt-2 text-sm text-slate-400 leading-relaxed font-medium">
                                                {{ $log->note }}
                                            </p>
                                        @endif
                                    </div>
                                    {{-- Change --}}
                                    <div class="text-right shrink-0">
                                        <p class="text-lg font-bold {{ $delta >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $delta >= 0 ? '+' : '' }}{{ $delta }}
                                        </p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">
                                            Bal: {{ $after }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="py-20 text-center">
                                    <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-50 text-gray-300 mb-4">
                                        <i class="ri-inbox-line text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-900">No Activity Yet</p>
                                    <p class="text-xs text-gray-400 mt-1">Start by adjusting the stock levels.</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-8">
                            {{ $logs->links() }}
                        </div>
                    @else
                        <div class="py-20 text-center text-gray-400">Loading activity...</div>
                    @endif
                </div>

                <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end">
                    <button @click="modalOpen=false; $wire.closeLogsModal()"
                        class="px-6 py-2.5 text-sm font-bold text-gray-600 hover:text-gray-900 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
