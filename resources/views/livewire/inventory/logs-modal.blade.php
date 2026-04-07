<div
    x-data="{ modalOpen: false }"
    x-on:open-inventory-logs-modal.window="modalOpen = true"
    x-on:close-inventory-logs-modal.window="modalOpen = false; $wire.closeLogsModal()"
    x-cloak
>
    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4">
            {{-- Overlay --}}
            <div @click="modalOpen=false; $wire.closeLogsModal()" class="absolute inset-0 bg-slate-900/10 backdrop-blur-[2px]"></div>

            {{-- Modal --}}
            <div
                x-show="modalOpen"
                x-transition
                x-trap.inert.noscroll="modalOpen"
                class="relative w-full max-w-2xl bg-white rounded-xl shadow-2xl flex flex-col overflow-hidden max-h-[85vh] my-auto"
            >
                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-3.5 border-b border-slate-50">
                    <div>
                        <h3 class="text-xs font-bold text-slate-800 tracking-tight">Inventory History</h3>
                        <p class="text-[10px] font-medium text-slate-500 leading-none mt-1">{{ $productName }}</p>
                    </div>
                    <button @click="modalOpen=false; $wire.closeLogsModal()" class="text-slate-400 hover:text-slate-600 transition">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="flex-1 overflow-y-auto px-6 py-6 custom-scrollbar">
                    @if($logs)
                        @php
                            $increaseTypes = ['in', 'return', 'release', 'replace'];
                            $decreaseTypes = ['out', 'sale', 'reserve'];
                            $runningBalance = (int) ($logsStartBalance ?? $currentStock);

                            $typeMeta = [
                                'adjust' => ['label' => 'ADJ', 'color' => '#f59e0b', 'icon' => 'ri-equalizer-fill'],
                                'in' => ['label' => 'IN', 'color' => '#10b981', 'icon' => 'ri-add-circle-fill'],
                                'sale' => ['label' => 'SALE', 'color' => '#6366f1', 'icon' => 'ri-shopping-cart-2-fill'],
                                'out' => ['label' => 'OUT', 'color' => '#ef4444', 'icon' => 'ri-indeterminate-circle-fill'],
                                'return' => ['label' => 'RET', 'color' => '#0ea5e9', 'icon' => 'ri-arrow-go-back-fill'],
                            ];
                        @endphp

                        <div class="relative space-y-7">
                            {{-- Vertical Line --}}
                            <div class="absolute left-[15px] top-4 bottom-4 w-px bg-slate-100"></div>

                            @forelse($logs as $log)
                                @php
                                    $meta = $typeMeta[$log->type] ?? ['label' => strtoupper($log->type), 'color' => '#64748b', 'icon' => 'ri-history-fill'];
                                    
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
                                @endphp

                                <div wire:key="inv-log-{{ $log->id }}" class="relative flex items-start gap-5 group">
                                    {{-- Mini Icon Node --}}
                                    <div class="relative z-10 shrink-0 mt-0.5">
                                        <div class="h-8 w-8 rounded-full border border-white bg-white flex items-center justify-center shadow-sm" style="color: {{ $meta['color'] }}">
                                            <i class="{{ $meta['icon'] }} text-base"></i>
                                        </div>
                                    </div>

                                    {{-- Content --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-4">
                                            <div class="flex items-center gap-2">
                                                <span class="text-[9px] font-bold tracking-wider uppercase px-1.5 py-0.5 rounded bg-opacity-10" style="color: {{ $meta['color'] }}; background-color: {{ $meta['color'] }}">
                                                    {{ $meta['label'] }}
                                                </span>
                                                <span class="text-[10px] font-medium text-slate-400">
                                                    {{ $log->created_at?->format('d M, H:i') }}
                                                </span>
                                            </div>

                                            <div class="flex items-center gap-1.5 text-[10px] font-mono font-medium text-slate-500">
                                                <span>{{ $before }}</span>
                                                <i class="ri-arrow-right-line text-[8px] text-slate-300"></i>
                                                <span class="text-slate-800 font-bold border-b border-slate-100">{{ $after }}</span>
                                            </div>
                                        </div>

                                        <div class="mt-1 flex items-baseline gap-2">
                                            <span class="text-xs font-bold text-slate-700">{{ $delta >= 0 ? '+' : '' }}{{ $delta }} Units</span>
                                            @if($log->reference_type)
                                                <span class="text-[9px] font-normal text-slate-400 uppercase tracking-tighter">
                                                    {{ $log->reference_type }} {{ $log->reference_id ? '#'.$log->reference_id : '' }}
                                                </span>
                                            @endif
                                        </div>

                                        @if($log->note)
                                            <p class="mt-0.5 text-[10px] text-slate-500 leading-normal font-normal">
                                                {{ $log->note }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="py-10 text-center text-slate-400 italic text-[10px]">No activity logs found.</div>
                            @endforelse
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-8 pt-4 border-t border-slate-50 flex justify-center">
                            {{ $logs->links() }}
                        </div>
                    @else
                        <div class="py-20 text-center">
                            <i class="ri-loader-4-line animate-spin text-xl text-slate-300"></i>
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="px-5 py-3 border-t border-slate-50 bg-white flex items-center justify-end">
                    <button @click="modalOpen=false; $wire.closeLogsModal()"
                        class="text-[9px] font-bold text-slate-400 hover:text-slate-600 uppercase tracking-widest">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
