<div 
    class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8"
    x-data="{ recommendOpen: false }"
    x-on:open-recommend-modal.window="recommendOpen = true"
    x-on:close-recommend-modal.window="recommendOpen = false"
>
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 tracking-tight">
                Product Recommendations
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Configure related and recommended products to display on product detail pages.
            </p>
        </div>

        <a href="{{ route('admin.products.index') }}" 
           class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 transition"
        >
            <i class="ri-arrow-left-line text-base"></i>
            Back to Products
        </a>
    </div>

    <!-- Filters Section -->
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm flex flex-col lg:flex-row lg:items-center gap-4">
        <!-- Search base product -->
        <div class="relative flex-1 lg:max-w-xs">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <i class="ri-search-line"></i>
            </span>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search products..."
                class="w-full rounded-xl border border-slate-200 pl-9 pr-4 py-2.5 text-sm focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition duration-200 bg-slate-50/50 focus:bg-white"
            >
        </div>
        
        <div class="flex items-center gap-2 lg:ml-auto">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Show</span>
            <select
                wire:model.live="perPage"
                class="h-10 rounded-xl border border-slate-200 bg-slate-50/50 px-3 text-sm font-medium text-slate-700 focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition focus:bg-white"
            >
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>

    <!-- Desktop Recommendations Table -->
    <div class="hidden sm:block bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden text-nowrap">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-left font-semibold">Base Product</th>
                    <th class="px-6 py-4 text-left font-semibold">Category</th>
                    <th class="px-6 py-4 text-left font-semibold">Recommendations</th>
                    <th class="px-6 py-4 text-right font-semibold">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($products as $product)
                    <tr wire:key="prod-rec-row-{{ $product->id }}" class="hover:bg-slate-50/80 transition duration-150">
                        <!-- Product Info -->
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-xl bg-slate-50 border border-slate-100 overflow-hidden shadow-sm flex-shrink-0">
                                    @php $primaryImg = $product->images->where('is_primary', true)->first() ?? $product->images->first(); @endphp
                                    @if($primaryImg)
                                        <img src="{{ asset('storage/' . $primaryImg->image) }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-slate-300 bg-slate-50">
                                            <i class="ri-image-line text-lg"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-900 truncate max-w-[220px]" title="{{ $product->name }}">
                                        {{ $product->name }}
                                    </p>
                                    <p class="text-xs text-slate-400 font-mono tracking-tight mt-0.5 truncate max-w-[150px]">
                                        {{ $product->sku }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <!-- Category -->
                        <td class="px-6 py-5">
                            @if($product->category)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                    {{ $product->category->title }}
                                </span>
                            @else
                                <span class="text-xs text-slate-400 italic">Uncategorized</span>
                            @endif
                        </td>

                        <!-- Recommendations list -->
                        <td class="px-6 py-5">
                            @if($product->recommendedProducts->isNotEmpty())
                                <div class="flex flex-wrap gap-1.5 max-w-[400px]">
                                    @foreach($product->recommendedProducts->take(3) as $rec)
                                        <span class="inline-flex items-center rounded-lg bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                            {{ $rec->name }}
                                            @if($rec->pivot->title)
                                                <span class="ml-1 text-[10px] text-blue-500 font-normal">({{ $rec->pivot->title }})</span>
                                            @endif
                                        </span>
                                    @endforeach
                                    @if($product->recommendedProducts->count() > 3)
                                        <span class="inline-flex items-center rounded-lg bg-slate-50 px-2 py-1 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-600/10">
                                            +{{ $product->recommendedProducts->count() - 3 }} more
                                        </span>
                                    @endif
                                </div>
                            @else
                                <span class="text-xs text-slate-400 italic">No recommendations configured</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <button
                                    wire:click="openRecommendModal({{ $product->id }})"
                                    class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-xl text-xs font-medium hover:bg-blue-600 hover:text-white transition duration-200"
                                >
                                    Manage Recommendations
                                </button>
                                @if($product->recommendedProducts->isNotEmpty())
                                    <button
                                        wire:click="deleteRecommendations({{ $product->id }})"
                                        wire:confirm="Are you sure you want to remove all recommendations for this product?"
                                        class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-xl text-xs font-medium hover:bg-rose-600 hover:text-white transition duration-200"
                                    >
                                        Clear
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center text-slate-400">
                            No products found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Mobile View -->
    <div class="sm:hidden space-y-4">
        @forelse($products as $product)
            <div wire:key="mobile-rec-{{ $product->id }}" class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm space-y-4">
                <div class="flex items-start gap-4">
                    <div class="h-16 w-16 rounded-xl bg-slate-50 border border-slate-100 overflow-hidden shadow-sm flex-shrink-0">
                        @php $primaryImg = $product->images->where('is_primary', true)->first() ?? $product->images->first(); @endphp
                        @if($primaryImg)
                            <img src="{{ asset('storage/' . $primaryImg->image) }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full flex items-center justify-center text-slate-300">
                                <i class="ri-image-line text-xl"></i>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-900 truncate">{{ $product->name }}</p>
                        <p class="text-xs text-slate-400 mt-0.5 italic">{{ $product->category->title ?? 'No Category' }}</p>
                        <p class="text-xs font-mono text-slate-400 mt-1">{{ $product->sku }}</p>
                    </div>
                </div>

                @if($product->recommendedProducts->isNotEmpty())
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mb-1.5">Recommendations</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($product->recommendedProducts as $rec)
                                <span class="inline-flex items-center rounded-lg bg-blue-50 px-2 py-0.5 text-xs text-blue-700 font-medium">
                                    {{ $rec->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex gap-2 pt-3 border-t border-slate-50">
                    <button
                        wire:click="openRecommendModal({{ $product->id }})"
                        class="flex-1 bg-blue-50 text-blue-600 px-3 py-2 rounded-xl text-xs font-medium hover:bg-blue-600 hover:text-white transition"
                    >
                        Manage
                    </button>
                    @if($product->recommendedProducts->isNotEmpty())
                        <button
                            wire:click="deleteRecommendations({{ $product->id }})"
                            wire:confirm="Are you sure you want to remove all recommendations?"
                            class="bg-rose-50 text-rose-600 px-3 py-2 rounded-xl text-xs font-medium hover:bg-rose-600 hover:text-white transition"
                        >
                            Clear
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white border border-dashed border-slate-200 rounded-2xl py-12 px-6 text-center text-slate-400">
                No products found.
            </div>
        @endforelse

        <div class="pt-1">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Teleported Recommendations Setup Modal -->
    <template x-teleport="body">
        <div x-show="recommendOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
            <!-- Backdrop -->
            <div @click="recommendOpen = false" class="absolute inset-0 bg-slate-900/45 backdrop-blur-sm transition-opacity"></div>

            <!-- Modal box -->
            <div
                x-show="recommendOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative w-full max-w-4xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl flex flex-col max-h-[85vh]"
            >
                <!-- Header -->
                <div class="flex items-start justify-between gap-4 border-b border-slate-100 bg-white px-6 py-5 shrink-0">
                    <div class="min-w-0">
                        <h3 class="text-xl font-semibold text-slate-900">Manage Product Recommendations</h3>
                        <p class="text-sm text-slate-500 mt-1">
                            Configure which products appear as recommendations/related items.
                        </p>
                    </div>
                    <button
                        type="button"
                        @click="recommendOpen = false"
                        class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                    >
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <!-- Scrollable Body Content -->
                <div class="px-6 py-5 overflow-y-auto flex-1 space-y-6">
                    @if($selectedBaseProduct)
                        <!-- Base Product Display -->
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Base Product</p>
                                <div class="mt-2 flex items-center gap-3">
                                    <div class="h-12 w-12 rounded-xl overflow-hidden border border-slate-200 bg-white shadow-sm flex-shrink-0">
                                        @php $baseImg = $selectedBaseProduct->images->where('is_primary', true)->first() ?? $selectedBaseProduct->images->first(); @endphp
                                        @if($baseImg)
                                            <img src="{{ asset('storage/' . $baseImg->image) }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-slate-300">
                                                <i class="ri-image-line"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-base font-bold text-slate-900">{{ $selectedBaseProduct->name }}</p>
                                        <p class="text-xs font-mono text-slate-500 mt-0.5">{{ $selectedBaseProduct->sku }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="shrink-0 bg-white border border-slate-200 rounded-xl px-4 py-2 text-center">
                                <span class="text-2xl font-extrabold text-blue-600 block leading-tight">
                                    {{ count($recommendedProductIds) }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">selected</span>
                            </div>
                        </div>
                    @endif

                    <!-- Modal Controls (Search & Quick Tools) -->
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 space-y-3">
                        <div class="flex flex-col md:flex-row gap-3 items-center justify-between">
                            <div class="relative flex-1 w-full">
                                <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input
                                    type="text"
                                    wire:model.live.debounce.200ms="modalSearch"
                                    placeholder="Search products to recommend..."
                                    class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100"
                                >
                            </div>

                            <div class="flex items-center gap-2 w-full md:w-auto justify-end">
                                <button
                                    type="button"
                                    wire:click="$set('recommendedProductIds', [])"
                                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition"
                                >
                                    Clear selections
                                </button>
                                <button
                                    type="button"
                                    wire:click="$set('recommendationTitles', [])"
                                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition"
                                >
                                    Clear custom titles
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid/List to Check -->
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Available Products</p>
                        <div class="grid gap-3 sm:grid-cols-2 max-h-[35vh] overflow-y-auto pr-1">
                            @forelse($recommendationOptions as $option)
                                @php
                                    $isSelected = in_array((int) $option->id, $recommendedProductIds, true);
                                @endphp
                                <div 
                                    wire:key="rec-opt-{{ $option->id }}"
                                    class="rounded-xl border p-3.5 transition duration-150 flex flex-col justify-between gap-3 {{ $isSelected ? 'border-blue-500 bg-blue-50/20' : 'border-slate-200 bg-white hover:border-slate-300' }}"
                                >
                                    <div class="flex items-start justify-between gap-3">
                                        <label class="flex items-start gap-3 cursor-pointer flex-1 min-w-0">
                                            <input
                                                type="checkbox"
                                                value="{{ $option->id }}"
                                                wire:model.live="recommendedProductIds"
                                                class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                            >
                                            <div class="min-w-0">
                                                <p class="text-sm font-semibold text-slate-900 truncate" title="{{ $option->name }}">
                                                    {{ $option->name }}
                                                </p>
                                                <p class="text-xs font-mono text-slate-400 truncate mt-0.5">
                                                    {{ $option->sku }}
                                                </p>
                                            </div>
                                        </label>

                                        @if($isSelected)
                                            <span class="rounded-full bg-blue-100 text-blue-700 px-2 py-0.5 text-[10px] font-bold">
                                                Selected
                                            </span>
                                        @endif
                                    </div>

                                    @if($isSelected)
                                        <div class="mt-1">
                                            <input
                                                type="text"
                                                wire:model.live.debounce.250ms="recommendationTitles.{{ $option->id }}"
                                                placeholder="Custom title, e.g. Related Product"
                                                class="w-full rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                                            >
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="col-span-2 rounded-xl border border-dashed border-slate-200 bg-slate-50 py-8 text-center text-sm text-slate-400">
                                    No matching products available.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="rounded-xl bg-rose-50 border border-rose-100 p-3">
                            @foreach ($errors->all() as $error)
                                <p class="text-xs text-rose-600 font-semibold">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-between border-t border-slate-100 bg-white px-6 py-4 shrink-0">
                    <p class="text-xs text-slate-500 hidden md:block">
                        Recommendations update instantly on save.
                    </p>

                    <div class="flex justify-end gap-3 w-full md:w-auto">
                        <button
                            type="button"
                            @click="recommendOpen = false"
                            class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition w-full md:w-auto"
                        >
                            Cancel
                        </button>

                        <button
                            type="button"
                            wire:click="saveRecommendations"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60 transition w-full md:w-auto"
                        >
                            <svg wire:loading wire:target="saveRecommendations" class="h-4 w-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="saveRecommendations">Save Changes</span>
                            <span wire:loading wire:target="saveRecommendations">Saving...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>