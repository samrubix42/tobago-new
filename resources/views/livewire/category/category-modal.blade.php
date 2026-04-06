<div
    x-data="{ modalOpen: false }"
    x-on:open-modal.window="modalOpen = true"
    x-on:close-modal.window="modalOpen = false"
    x-cloak
>
    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4">

            <!-- 🔷 Overlay -->
            <div @click="modalOpen=false" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

            <!-- 🔷 Modal -->
            <div
                x-show="modalOpen"
                x-transition
                x-trap.inert.noscroll="modalOpen"
                class="relative w-full max-w-3xl bg-white rounded-2xl shadow-xl max-h-[90vh] flex flex-col overflow-hidden"
            >

                <!-- 🔷 Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $categoryId ? 'Edit Category' : 'Add Category' }}
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Manage your category details
                        </p>
                    </div>

                    <button @click="modalOpen=false"
                        class="h-9 w-9 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <!-- 🔷 Body -->
                <div class="flex-1 overflow-y-auto px-6 py-5 space-y-6 text-sm">

                    <!-- 🔷 Top Section -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <!-- Image Upload -->
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-gray-600">Image</label>

                            <div class="relative w-full h-32 rounded-xl border border-dashed border-gray-300 bg-gray-50 flex items-center justify-center overflow-hidden hover:border-blue-400 transition">
                                @if($image)
                                    <img src="{{ $image->temporaryUrl() }}" class="object-cover w-full h-full">
                                @elseif($existingImage)
                                    <img src="{{ asset('storage/' . $existingImage) }}" class="object-cover w-full h-full">
                                @else
                                    <div class="text-center text-gray-400">
                                        <i class="ri-image-line text-2xl"></i>
                                        <p class="text-xs mt-1">Upload</p>
                                    </div>
                                @endif
                            </div>

                            <input
                                type="file"
                                wire:model="image"
                                accept="image/*"
                                class="w-full text-xs border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none"
                            >

                            @error('image')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Form Fields -->
                        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <!-- Name -->
                            <div>
                                <label class="text-xs font-medium text-gray-600">Name</label>
                                <input
                                    wire:model.live="title"
                                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none"
                                >
                                @error('title')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Slug -->
                            <div>
                                <label class="text-xs font-medium text-gray-600">Slug</label>
                                <input
                                    wire:model.live="slug"
                                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none"
                                >
                                @error('slug')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="sm:col-span-2">
                                <label class="text-xs font-medium text-gray-600">Description</label>
                                <textarea
                                    wire:model.live="description"
                                    rows="3"
                                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none"
                                ></textarea>
                            </div>

                            <!-- Toggles -->
                            <div class="sm:col-span-2 flex items-center gap-6 pt-1">
                                <label class="flex items-center gap-2 text-sm text-gray-700">
                                    <input type="checkbox" wire:model.live="isSubcategory"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    Subcategory
                                </label>

                                <label class="flex items-center gap-2 text-sm text-gray-700">
                                    <input type="checkbox" wire:model.live="status"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    Active
                                </label>
                            </div>

                            <!-- Parent -->
                            @if($isSubcategory)
                                <div class="sm:col-span-2">
                                    <label class="text-xs font-medium text-gray-600">Parent Category</label>
                                    <select
                                        wire:model.live="parentId"
                                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none"
                                    >
                                        <option value="">Select Parent</option>
                                        @foreach($parentCategories as $parent)
                                            <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                        </div>
                    </div>

                    <!-- 🔷 SEO Section -->
                    <div class="border-t border-gray-200 pt-5 space-y-4">
                        <p class="text-sm font-semibold text-gray-800">SEO Settings</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <input wire:model.live="meta_title" placeholder="Meta Title"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">

                            <input wire:model.live="meta_keywords" placeholder="Meta Keywords"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none">

                            <textarea wire:model.live="meta_description" rows="2" placeholder="Meta Description"
                                class="sm:col-span-2 w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none"></textarea>

                        </div>
                    </div>
                </div>

                <!-- 🔷 Footer -->
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50">

                    <button @click="modalOpen=false"
                        class="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition">
                        Cancel
                    </button>

                    <button
                        wire:click="save"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium rounded-lg bg-blue-600 text-white shadow-sm hover:bg-blue-700 transition disabled:opacity-60"
                    >
                        <svg wire:loading wire:target="save"
                            class="animate-spin h-4 w-4"
                            viewBox="0 0 24 24"></svg>

                        <span wire:loading.remove wire:target="save">Save</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>

                </div>
            </div>
        </div>
    </template>
</div>