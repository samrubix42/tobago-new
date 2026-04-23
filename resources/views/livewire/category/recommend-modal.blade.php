<div
    x-data="{ recommendOpen: false }"
    x-on:open-recommend-modal.window="recommendOpen = true"
    x-on:close-recommend-modal.window="recommendOpen = false"
    x-cloak
>
    <template x-teleport="body">
        <div x-show="recommendOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div @click="recommendOpen = false" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

            <div
                x-show="recommendOpen"
                x-transition
                x-trap.inert.noscroll="recommendOpen"
                class="relative w-full max-w-2xl rounded-2xl border border-slate-200 bg-white shadow-xl overflow-hidden"
            >
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Recommended Categories</h3>
                        <p class="text-xs text-slate-500 mt-1">Choose multiple categories to recommend together.</p>
                    </div>
                    <button @click="recommendOpen = false" class="h-9 w-9 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <div class="px-6 py-5 max-h-[60vh] overflow-y-auto">
                    @php
                        $selectedCategory = collect($categories)->firstWhere('id', $recommendationCategoryId);
                    @endphp

                    <div class="mb-4 rounded-lg border border-blue-100 bg-blue-50 px-3 py-2 text-sm text-blue-800">
                        Base Category:
                        <span class="font-semibold">{{ $selectedCategory?->title ?? 'N/A' }}</span>
                    </div>

                    <div class="space-y-4">
                        @forelse($recommendationOptions as $parent)
                            <div wire:key="recommend-parent-{{ $parent->id }}" class="rounded-xl border border-slate-200 bg-white p-3">
                                @if((int) $parent->id !== (int) $recommendationCategoryId)
                                    <label class="flex items-center gap-3 rounded-lg px-2 py-2 hover:bg-slate-50 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            value="{{ $parent->id }}"
                                            wire:model="recommendedCategoryIds"
                                            class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                        >
                                        <span class="text-sm font-medium text-slate-800">{{ $parent->title }}</span>
                                    </label>
                                @else
                                    <div class="px-2 py-2 text-sm font-medium text-slate-400">
                                        {{ $parent->title }} (Current)
                                    </div>
                                @endif

                                @if($parent->children->isNotEmpty())
                                    <div class="mt-2 ml-5 space-y-2 border-l border-slate-200 pl-3">
                                        @foreach($parent->children as $child)
                                            @if((int) $child->id !== (int) $recommendationCategoryId)
                                                <label wire:key="recommend-child-{{ $child->id }}" class="flex items-center gap-3 rounded-lg px-2 py-1.5 hover:bg-slate-50 cursor-pointer">
                                                    <input
                                                        type="checkbox"
                                                        value="{{ $child->id }}"
                                                        wire:model="recommendedCategoryIds"
                                                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                                    >
                                                    <span class="text-sm text-slate-700">{{ $child->title }}</span>
                                                </label>
                                            @else
                                                <div class="px-2 py-1.5 text-sm text-slate-400">
                                                    {{ $child->title }} (Current)
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="rounded-lg border border-dashed border-slate-200 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
                                No categories available.
                            </div>
                        @endforelse
                    </div>

                    @error('recommendedCategoryIds.*')
                        <p class="mt-3 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 px-6 py-4 border-t border-slate-200 bg-slate-50">
                    <button
                        @click="recommendOpen = false"
                        class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-700 hover:bg-slate-100"
                    >
                        Cancel
                    </button>

                    <button
                        wire:click="saveRecommendations"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-60"
                    >
                        <svg wire:loading wire:target="saveRecommendations" class="h-4 w-4 animate-spin" viewBox="0 0 24 24"></svg>
                        <span wire:loading.remove wire:target="saveRecommendations">Save</span>
                        <span wire:loading wire:target="saveRecommendations">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
