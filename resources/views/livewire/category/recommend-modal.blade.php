<div
    x-data="{
        recommendOpen: false,
        search: '',
        normalize(value) {
            return (value || '').toLowerCase().trim();
        },
        matches(value) {
            return this.search === '' || this.normalize(value).includes(this.normalize(this.search));
        }
    }"
    x-on:open-recommend-modal.window="recommendOpen = true"
    x-on:close-recommend-modal.window="recommendOpen = false"
    x-cloak
>
    <template x-teleport="body">
        <div x-show="recommendOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div @click="recommendOpen = false" class="absolute inset-0 bg-slate-900/45"></div>

            <div
                x-show="recommendOpen"
                x-transition
                x-trap.inert.noscroll="recommendOpen"
                class="relative w-full max-w-4xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl"
            >
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 bg-white px-6 py-5">
                    <div class="min-w-0">
                        <h3 class="text-xl font-semibold text-slate-900">Category Recommendation Setup</h3>
                        <p class="text-sm text-slate-600 mt-1">
                            Select the categories you want to show on the product page and add an optional section title for each one.
                        </p>
                    </div>
                    <button
                        type="button"
                        @click="recommendOpen = false"
                        aria-label="Close"
                        class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                    >
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <div class="px-6 py-5 max-h-[72vh] overflow-y-auto">
                    @php
                        $selectedCategory = collect($categories)->firstWhere('id', $recommendationCategoryId);
                        $selectedIds = collect($recommendedCategoryIds)->map(fn ($id) => (int) $id)->all();
                        $selectedCount = count($selectedIds);
                    @endphp

                    <div class="mb-5 grid gap-4 lg:grid-cols-[1.2fr_0.8fr]">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-600">Base Category</p>
                            <div class="mt-2 flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-lg font-semibold text-slate-900">{{ $selectedCategory?->title ?? 'N/A' }}</p>
                                    <p class="text-sm text-slate-600 mt-1">These recommendations will appear on this category's product detail page.</p>
                                </div>
                                <span class="shrink-0 rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-700 ring-1 ring-slate-200">
                                    {{ $selectedCount }} selected
                                </span>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-600">Quick Tips</p>
                            <ul class="mt-2 space-y-1.5 text-sm text-slate-600">
                                <li>Choose one or more related categories.</li>
                                <li>Add a custom title only if you want a special section heading.</li>
                                <li>If title is blank, category name will be used automatically.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="sticky top-0 z-10 mb-5 rounded-2xl border border-slate-200 bg-white p-4">
                        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                            <div class="relative flex-1">
                                <i class="ri-search-line pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input
                                    type="text"
                                    x-model.debounce.150ms="search"
                                    placeholder="Search categories..."
                                    class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200/60"
                                >
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                @if($selectedCount > 0)
                                    <span class="rounded-full bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-700 ring-1 ring-slate-200">
                                        {{ $selectedCount }} selected
                                    </span>
                                @endif
                                <button
                                    type="button"
                                    wire:click="$set('recommendedCategoryIds', [])"
                                    class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-100"
                                >
                                    Clear categories
                                </button>
                                <button
                                    type="button"
                                    wire:click="$set('recommendationTitles', [])"
                                    class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 transition hover:bg-slate-100"
                                >
                                    Clear titles
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($recommendationOptions as $parent)
                            @php
                                $parentSelected = in_array((int) $parent->id, $selectedIds, true);
                                $searchableText = collect([$parent->title])
                                    ->merge($parent->children->pluck('title'))
                                    ->implode(' ');
                            @endphp

                             <div
                                 wire:key="recommend-parent-{{ $parent->id }}"
                                 x-show="matches(@js($searchableText))"
                                 class="rounded-2xl border border-slate-200 bg-white p-4"
                             >
                                 <div class="flex items-start justify-between gap-3">
                                     <label class="flex flex-1 cursor-pointer items-start gap-3 rounded-xl px-2 py-2 transition hover:bg-slate-50">
                                         <input
                                             type="checkbox"
                                             value="{{ $parent->id }}"
                                             wire:model.live="recommendedCategoryIds"
                                             class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                         >
                                         <span class="min-w-0">
                                             <span class="text-base font-semibold text-slate-900">
                                                 {{ $parent->title }}
                                                 @if((int) $parent->id === (int) $recommendationCategoryId)
                                                    <span class="ml-1 text-xs font-medium text-slate-500">(Current category)</span>
                                                @endif
                                            </span>
                                            <span class="mt-1 block text-xs text-slate-500">
                                                Parent category{{ $parent->children->isNotEmpty() ? ' with ' . $parent->children->count() . ' subcategories' : '' }}
                                            </span>
                                        </span>
                                    </label>

                                    @if($parentSelected)
                                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-emerald-100">
                                            Selected
                                        </span>
                                    @endif
                                </div>

                                     @if($parentSelected)
                                         <div class="mt-3 rounded-xl bg-slate-50 p-3">
                                             <label class="block">
                                                 <span class="mb-1.5 block text-xs font-medium uppercase tracking-[0.14em] text-slate-500">Section Title</span>
                                                 <input
                                                     type="text"
                                                     wire:model.live.debounce.250ms="recommendationTitles.{{ $parent->id }}"
                                                     placeholder="Optional, for example: Best {{ $parent->title }} Products"
                                                     class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200/60"
                                                 >
                                             </label>
                                         </div>
                                     @endif

                                @if($parent->children->isNotEmpty())
                                    <div class="mt-4 border-t border-slate-100 pt-4">
                                        <p class="mb-3 text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Subcategories</p>
                                        <div class="grid gap-3 md:grid-cols-2">
                                            @foreach($parent->children as $child)
                                                 <div
                                                     wire:key="recommend-child-{{ $child->id }}"
                                                     x-show="matches(@js($child->title)) || matches(@js($parent->title))"
                                                     class="rounded-xl border border-slate-200 bg-slate-50 p-3"
                                                 >
                                                     <div class="flex items-start justify-between gap-3">
                                                         <label class="flex flex-1 cursor-pointer items-start gap-3">
                                                             <input
                                                                 type="checkbox"
                                                                 value="{{ $child->id }}"
                                                                 wire:model.live="recommendedCategoryIds"
                                                                 class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                                             >
                                                             <span class="min-w-0">
                                                                 <span class="text-sm font-medium text-slate-800">
                                                                     {{ $child->title }}
                                                                    @if((int) $child->id === (int) $recommendationCategoryId)
                                                                        <span class="ml-1 text-xs font-medium text-slate-500">(Current category)</span>
                                                                    @endif
                                                                </span>
                                                                <span class="mt-1 block text-xs text-slate-500">Show products from this subcategory in product view.</span>
                                                            </span>
                                                        </label>

                                                        @if(in_array((int) $child->id, $selectedIds, true))
                                                            <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-medium text-emerald-700 ring-1 ring-emerald-100">
                                                                Selected
                                                            </span>
                                                        @endif
                                                    </div>

                                                    @if(in_array((int) $child->id, $selectedIds, true))
                                                        <div class="mt-3">
                                                            <label class="block">
                                                                <span class="mb-1.5 block text-[11px] font-medium uppercase tracking-[0.14em] text-slate-500">Section Title</span>
                                                             <input
                                                                 type="text"
                                                                 wire:model.live.debounce.250ms="recommendationTitles.{{ $child->id }}"
                                                                 placeholder="Optional, for example: Top {{ $child->title }} Picks"
                                                                 class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200/60"
                                                             >
                                                         </label>
                                                     </div>
                                                 @endif
                                             </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                                No categories available.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-4 space-y-1">
                        @error('recommendedCategoryIds.*')
                            <p class="text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                        @error('recommendationTitles.*')
                            <p class="text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-200 bg-white px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-slate-500">
                        Save to update the recommendation sections shown on the product detail page.
                    </p>

                    <div class="flex justify-end gap-3">
                        <button
                            type="button"
                            @click="recommendOpen = false"
                            class="rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-100"
                        >
                            Cancel
                        </button>

                        <button
                            type="button"
                            wire:click="saveRecommendations"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-60"
                        >
                            <svg wire:loading wire:target="saveRecommendations" class="h-4 w-4 animate-spin" viewBox="0 0 24 24"></svg>
                            <span wire:loading.remove wire:target="saveRecommendations">Save Recommendations</span>
                            <span wire:loading wire:target="saveRecommendations">Saving...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
