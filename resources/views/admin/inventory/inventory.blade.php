<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">
                Stock Management
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                View and adjust inventory for all product.
            </p>
        </div>
    </div>

    <!-- Search/Filters -->
    <div class="relative w-full sm:w-80">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
            <i class="ri-search-line"></i>
        </span>
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search by SKU or Product..."
            class="w-full rounded-md border border-slate-300 pl-9 pr-4 py-2.5 text-sm
                   focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition">
    </div>

    <!-- Desktop Table -->
    <div class="hidden sm:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-6 py-4 text-left">Product</th>
                    <th class="px-6 py-4 text-left">SKU</th>
                    <th class="px-6 py-4 text-left">Quantity</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right w-40">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($products as $product)
                <tr wire:key="product-row-{{ $product->id }}" class="hover:bg-slate-50 transition">
                    <td class="px-6 py-5">
                        <div class="flex flex-col">
                            <span class="font-medium text-slate-900">{{ $product->name }}</span>
                            <span class="text-xs text-slate-400 mt-0.5">{{ $product->category?->title ?? 'Uncategorized' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-5 font-mono text-xs text-slate-500">
                        {{ $product->sku ?: '—' }}
                    </td>
                    <td class="px-6 py-5">
                        <span class="font-bold {{ $product->stock <= ($product->hurry_stock ?? 5) ? 'text-rose-600' : 'text-slate-900' }}">
                            {{ (int) $product->stock }}
                        </span>
                    </td>
                    <td class="px-6 py-5">
                        @if((int)$product->stock <= 0)
                            <span class="text-rose-600 text-xs font-medium bg-rose-50 px-2 py-1 rounded-full text-[10px] uppercase">Out of Stock</span>
                            @elseif((int)$product->stock <= ($product->hurry_stock ?? 5))
                                <span class="text-amber-600 text-xs font-medium bg-amber-50 px-2 py-1 rounded-full text-[10px] uppercase">Low Stock</span>
                                @else
                                <span class="text-emerald-600 text-xs font-medium bg-emerald-50 px-2 py-1 rounded-full text-[10px] uppercase">In Stock</span>
                                @endif
                    </td>
                    <td class="px-6 py-5 text-right">
                        <div class="flex justify-end gap-2">
                            <button
                                @click="$dispatch('open-inventory-adjust-modal'); $wire.openAdjustModal({{ $product->id }})"
                                class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-md text-xs hover:bg-blue-100 transition">
                                Adjust
                            </button>
                            <button
                                @click="$dispatch('open-inventory-logs-modal'); $wire.openLogsModal({{ $product->id }})"
                                class="bg-slate-50 text-slate-600 px-3 py-1.5 rounded-md text-xs hover:bg-slate-100 transition">
                                Logs
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $products->onEachSide(1)->links() }}
        </div>
    </div>

    <!-- Mobile Cards -->
    <div class="sm:hidden space-y-4">
        @forelse($products as $product)
        <div wire:key="mobile-product-{{ $product->id }}"
            class="bg-white border border-slate-200 rounded-md p-4 shadow-sm space-y-3">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-bold text-slate-900">{{ $product->name }}</p>
                    <p class="text-xs text-slate-500 mt-1">@if($product->sku) {{ $product->sku }} @endif</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2 text-sm pt-2 border-t border-slate-100">
                <div>
                    <span class="text-slate-400 block text-xs uppercase font-bold tracking-widest">Quantity</span>
                    <span class="font-bold {{ (int)$product->stock <= ($product->hurry_stock ?? 5) ? 'text-rose-600' : 'text-slate-900' }}">
                        {{ (int)$product->stock }}
                    </span>
                </div>
            </div>
            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                @if((int)$product->stock <= 0)
                    <span class="text-[10px] font-medium text-rose-600 bg-rose-50 px-2 py-0.5 rounded-full uppercase leading-none">Out of Stock</span>
                    @elseif((int)$product->stock <= ($product->hurry_stock ?? 5))
                        <span class="text-[10px] font-medium text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full uppercase leading-none">Low Stock</span>
                        @else
                        <span class="text-[10px] font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full uppercase leading-none">In Stock</span>
                        @endif
                        <div class="flex gap-2">
                            <button
                                @click="$dispatch('open-inventory-adjust-modal'); $wire.openAdjustModal({{ $product->id }})"
                                class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-md text-xs font-medium">
                                Adjust
                            </button>
                            <button
                                @click="$dispatch('open-inventory-logs-modal'); $wire.openLogsModal({{ $product->id }})"
                                class="bg-slate-50 text-slate-600 px-3 py-1.5 rounded-md text-xs font-medium">
                                Logs
                            </button>
                        </div>
            </div>
        </div>
        @empty
        <div class="rounded-md border border-dashed border-slate-200 bg-slate-50 py-10 text-center text-slate-400">
            No products found.
        </div>
        @endforelse
        <div class="mt-4">
            {{ $products->onEachSide(1)->links() }}
        </div>
    </div>

    @include('livewire.inventory.adjust-modal')
    @include('livewire.inventory.logs-modal')

</div>

