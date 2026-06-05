<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 animate-fade-in">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Configure Recommendations</h1>
            <p class="text-sm text-slate-500 mt-1">Configure related and recommended products to display on this product's detail page.</p>
        </div>
        <button 
           wire:click="cancelEdit"
           class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition"
        >
            <i class="ri-arrow-left-line text-base"></i> Back to Products
        </button>
    </div>

    <!-- Base Product Details Card -->
    @if($selectedBaseProduct)
        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-5 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm animate-fade-in">
            <div class="flex items-center gap-4">
                <div class="h-16 w-16 rounded-xl overflow-hidden border border-slate-200 bg-white shadow-sm flex-shrink-0">
                    @php $baseImg = $selectedBaseProduct->images->where('is_primary', true)->first() ?? $selectedBaseProduct->images->first(); @endphp
                    @if($baseImg)
                        <img src="{{ asset('storage/' . $baseImg->image) }}" class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full flex items-center justify-center text-slate-300">
                            <i class="ri-image-line text-xl"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <p class="text-lg font-bold text-slate-900">{{ $selectedBaseProduct->name }}</p>
                    <p class="text-xs font-mono text-slate-500 mt-0.5">SKU: {{ $selectedBaseProduct->sku ?: 'N/A' }}</p>
                </div>
            </div>
            <div class="shrink-0 bg-white border border-slate-200 rounded-xl px-5 py-2.5 text-center shadow-xs">
                <span class="text-3xl font-extrabold text-blue-600 block leading-none">
                    {{ count($recommendedProductIds) }}
                </span>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1 block">selected</span>
            </div>
        </div>
    @endif

    <!-- Search and Add Section (Inline layout to prevent overlap) -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4 animate-fade-in">
        <div class="space-y-2">
            <label class="text-sm font-semibold text-slate-800">Search Products to Add</label>
            <div class="relative">
                <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input
                    type="text"
                    wire:model.live.debounce.200ms="searchQuery"
                    placeholder="Search by name or SKU to recommend..."
                    class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-10 pr-4 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100"
                >
                @if($searchQuery !== '')
                    <button
                        type="button"
                        wire:click="$set('searchQuery', '')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                    >
                        <i class="ri-close-circle-line text-lg"></i>
                    </button>
                @endif
            </div>
        </div>

        <!-- Inline Search Results (Push elements down instead of overlapping) -->
        @if($searchQuery !== '')
            <div class="rounded-xl border border-slate-200 bg-white overflow-hidden divide-y divide-slate-100 transition duration-150">
                @forelse($searchResults as $result)
                    <div class="flex items-center justify-between px-4 py-3 hover:bg-slate-50/50 transition duration-150">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-slate-900 truncate">{{ $result->name }}</p>
                            <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $result->sku }}</p>
                        </div>
                        <button
                            type="button"
                            wire:click="addProduct({{ $result->id }})"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white text-xs font-semibold transition shrink-0"
                        >
                            <i class="ri-add-line text-xs"></i> Add Product
                        </button>
                    </div>
                @empty
                    <div class="px-4 py-6 text-center text-slate-400 text-sm">
                        No matching products found.
                    </div>
                @endforelse
            </div>
        @endif
    </div>

    <!-- Selected Recommendations List -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6 animate-fade-in">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-base font-semibold text-slate-800">Selected Recommendations</h3>
            @if(count($recommendedProductIds) > 0)
                <button
                    type="button"
                    wire:click="$set('recommendedProductIds', [])"
                    class="text-xs text-rose-500 font-semibold hover:underline"
                >
                    Clear all
                </button>
            @endif
        </div>

        <div class="space-y-4">
            @forelse($selectedProducts as $option)
                <div 
                    wire:key="selected-rec-{{ $option->id }}"
                    class="rounded-2xl border border-slate-100 bg-slate-50/50 p-4 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-slate-200 transition"
                >
                    <div class="flex items-center gap-3.5 min-w-0 flex-1">
                        <div class="h-12 w-12 rounded-xl overflow-hidden border border-slate-200 bg-white flex-shrink-0 shadow-sm">
                            @php $optImg = $option->images->where('is_primary', true)->first() ?? $option->images->first(); @endphp
                            @if($optImg)
                                <img src="{{ asset('storage/' . $optImg->image) }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full flex items-center justify-center text-slate-300">
                                    <i class="ri-image-line text-lg"></i>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-900 truncate" title="{{ $option->name }}">
                                {{ $option->name }}
                            </p>
                            <p class="text-xs font-mono text-slate-400 mt-0.5">
                                {{ $option->sku }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <button
                            type="button"
                            wire:click="removeProduct({{ $option->id }})"
                            class="h-9 w-9 inline-flex items-center justify-center rounded-xl border border-rose-200 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition shrink-0 shadow-sm"
                            title="Remove recommendation"
                        >
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 py-16 text-center text-slate-400">
                    <i class="ri-git-merge-line text-4xl mb-3 text-slate-300 block"></i>
                    <p class="text-sm font-semibold">No recommendations selected yet</p>
                    <p class="text-xs text-slate-400 mt-1">Search for products in the box above to add them to this list.</p>
                </div>
            @endforelse
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="rounded-xl bg-rose-50 border border-rose-100 p-3">
                @foreach ($errors->all() as $error)
                    <p class="text-xs text-rose-600 font-semibold">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Actions Footer -->
        <div class="flex items-center justify-between border-t border-slate-100 pt-4 mt-6">
            <p class="text-xs text-slate-400 hidden sm:block">
                Changes take effect immediately on save.
            </p>

            <div class="flex justify-end gap-3 w-full sm:w-auto">
                <button
                    type="button"
                    wire:click="cancelEdit"
                    class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition w-full sm:w-auto"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    wire:click="saveRecommendations"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60 transition w-full sm:w-auto"
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

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.3s ease-out forwards;
}
</style>