<div
    x-data="{ modalOpen: false }"
    x-on:open-inventory-logs-modal.window="modalOpen = true"
    x-on:close-inventory-logs-modal.window="modalOpen = false; $wire.closeLogsModal()"
    x-cloak
>
    <template x-teleport="body">
        <div x-show="modalOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[99] flex items-center justify-center p-4">
            {{-- Overlay --}}
            <div @click="modalOpen=false; $wire.closeLogsModal()" class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"></div>

            {{-- Modal --}}
            <div
                x-show="modalOpen"
                x-trap.inert.noscroll="modalOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-lg bg-white rounded-xl shadow-lg flex flex-col overflow-hidden h-[550px]"
            >
                {{-- Minimal Header --}}
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
                    <div class="flex flex-col">
                        <h3 class="text-sm font-semibold text-gray-900">Inventory Logs</h3>
                        <p class="text-[11px] text-gray-400 mt-0.5 font-medium truncate max-w-[300px]">{{ $productName }}</p>
                    </div>
                    <button @click="modalOpen=false; $wire.closeLogsModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="flex-1 overflow-y-auto px-5 py-5 custom-scrollbar bg-white">
                    @if($logs)
                        @php
                            $increaseTypes = ['in', 'return', 'release', 'replace'];
                            $decreaseTypes = ['out', 'sale', 'reserve'];
                            $runningBalance = (int) ($logsStartBalance ?? $currentStock);

                            $typeMeta = [
                                'adjust' => ['label' => 'ADJ', 'color' => '#f59e0b', 'icon' => 'ri-equalizer-line'],
                                'in' => ['label' => 'IN', 'color' => '#10b981', 'icon' => 'ri-add-box-line'],
                                'sale' => ['label' => 'SALE', 'color' => '#6366f1', 'icon' => 'ri-shopping-bag-3-line'],
                                'out' => ['label' => 'OUT', 'color' => '#ef4444', 'icon' => 'ri-indeterminate-circle-line'],
                                'return' => ['label' => 'RET', 'color' => '#3b82f6', 'icon' => 'ri-refresh-line'],
                            ];
                        @endphp

                        <div class="space-y-4">
                            @forelse($logs as $log)
                                @php
                                    $meta = $typeMeta[$log->type] ?? ['label' => strtoupper($log->type), 'color' => '#64748b', 'icon' => 'ri-history-line'];
                                    
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

                                <div wire:key="inv-log-{{ $log->id }}" class="flex flex-col p-3 rounded-lg border border-gray-100 hover:bg-gray-50/50 transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="flex items-center gap-1 text-[9px] font-bold px-1.5 py-0.5 rounded border uppercase" 
                                                  style="color: {{ $meta['color'] }}; background-color: {{ $meta['color'] }}10; border-color: {{ $meta['color'] }}20">
                                                <i class="{{ $meta['icon'] }} text-[11px]"></i>
                                                {{ $meta['label'] }}
                                            </span>
                                            <span class="text-[10px] font-medium text-gray-400">
                                                {{ $log->created_at?->format('d M, H:i') }}
                                            </span>
                                        </div>
                                        <div class="text-[10px] font-mono text-gray-400">
                                            {{ $before }} → <span class="text-gray-900 font-bold">{{ $after }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-baseline justify-between">
                                        <span class="text-xs font-semibold {{ $delta >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $delta >= 0 ? '+' : '' }}{{ $delta }} Units
                                        </span>
                                        @if($log->reference_type)
                                            <span class="text-[10px] text-gray-400">
                                                {{ strtoupper($log->reference_type) }} #{{ $log->reference_id }}
                                            </span>
                                        @endif
                                    </div>

                                    @if($log->note)
                                        <p class="mt-1.5 text-[10px] text-gray-500 leading-normal">
                                            {{ $log->note }}
                                        </p>
                                    @endif
                                </div>
                            @empty
                                <div class="py-10 text-center text-gray-400 text-xs italic">No activity logs found.</div>
                            @endforelse
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $logs->links() }}
                        </div>
                    @else
                        <div class="flex items-center justify-center h-full">
                            <i class="ri-loader-4-line animate-spin text-2xl text-gray-300"></i>
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50 flex justify-end">
                    <button @click="modalOpen=false; $wire.closeLogsModal()"
                        class="text-[11px] font-semibold text-gray-500 hover:text-gray-900 uppercase tracking-widest">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>


