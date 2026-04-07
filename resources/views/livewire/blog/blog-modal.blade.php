<div
    x-data="{ modalOpen: false }"
    x-on:open-modal.window="modalOpen = true; setTimeout(() => { if (window.tinymce) { tinymce.get('blog-content')?.setContent($wire.content || ''); } }, 250)"
    x-on:close-modal.window="modalOpen = false"
    x-cloak
>
    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[99] flex items-center justify-center p-4">
            <div @click="modalOpen=false" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

            <div
                x-show="modalOpen"
                x-transition
                x-trap.inert.noscroll="modalOpen"
                class="relative w-full max-w-3xl bg-white rounded-2xl shadow-xl max-h-[90vh] flex flex-col overflow-hidden"
            >
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $blogId ? 'Edit Blog' : 'Add Blog' }}
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">Create or edit blog posts</p>
                    </div>
                    <button @click="modalOpen=false" class="h-9 w-9 flex items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto px-6 py-5 space-y-6 text-sm">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium text-gray-600">Title</label>
                            <input wire:model.live="title" placeholder="Blog title"
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none text-gray-900">
                            @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-600">Slug</label>
                            <input wire:model.live="slug" placeholder="blog-slug"
                                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none text-gray-900">
                            @error('slug')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-600">Category</label>
                            <select wire:model.live="category_id" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 outline-none text-gray-900">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-gray-600">Featured Image</label>
                            <input type="file" wire:model="featured_image" accept="image/*" class="mt-1 block w-full text-sm text-gray-900">
                            @if($existing_image)
                                <img src="{{ asset('storage/' . $existing_image) }}" class="h-20 mt-2 rounded-md object-cover">
                            @endif
                        </div>

                        <div class="sm:col-span-2">
                            <label class="text-xs font-medium text-gray-600">Content</label>
                            <div
                                wire:ignore
                                x-data="tinymceLivewire({ model: 'content' })"
                                x-init="init()"
                                x-on:open-modal.window="init().then(() => { if ($data.editor) { $data.editor.setContent($wire.content || '') } })"
                            >
                                <textarea
                                    id="blog-content"
                                    x-ref="textarea"
                                    placeholder="Write content..."
                                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 outline-none text-gray-900"
                                >{{ $content }}</textarea>
                            </div>
                            @error('content')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="sm:col-span-2 flex items-center gap-4">
                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" wire:model.live="is_published" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                Published
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <button @click="modalOpen=false" class="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition">Cancel</button>

                    <button wire:click="save" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium rounded-lg bg-blue-600 text-white shadow-sm hover:bg-blue-700 transition disabled:opacity-60">
                        <span wire:loading.remove wire:target="save">Save</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>
