<div
    x-data="categoryModalEditor()"
    x-init="init()"
    x-on:open-modal.window="modalOpen = true"
    x-on:close-modal.window="modalOpen = false"
    x-cloak
>
    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">

            <!-- 🔷 Overlay -->
            <div @click="modalOpen=false" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

            <!-- 🔷 Modal -->
            <div
                x-show="modalOpen"
                x-transition
                x-trap.inert.noscroll="modalOpen"
                class="relative w-full max-w-5xl bg-white rounded-2xl border border-slate-200 shadow-lg max-h-[90vh] flex flex-col overflow-hidden"
            >

                <!-- 🔷 Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-white">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">
                            {{ $categoryId ? 'Edit Category' : 'Add Category' }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Manage your category details
                        </p>
                    </div>

                    <button @click="modalOpen=false"
                        class="h-9 w-9 flex items-center justify-center rounded-lg text-slate-400 hover:bg-slate-200 hover:text-slate-600 transition">
                        <i class="ri-close-line text-lg"></i>
                    </button>
                </div>

                <!-- 🔷 Body -->
                <div class="flex-1 overflow-y-auto px-6 py-5 space-y-6 text-sm bg-slate-50/40">

                    <!-- 🔷 Top Section -->
                    <div class="grid grid-cols-1 gap-6 rounded-2xl border border-slate-200 bg-white p-4 sm:p-5">

                        <!-- Image Upload -->
                        <div class="space-y-3">
                            <label class="text-xs font-semibold text-slate-600 uppercase tracking-wider">Image</label>

                            <div class="relative w-full h-36 rounded-xl border border-dashed border-slate-300 bg-slate-50 flex items-center justify-center overflow-hidden hover:border-blue-400 transition">
                                @if($image)
                                    <img src="{{ $image->temporaryUrl() }}" class="object-cover w-full h-full">
                                @elseif($existingImage)
                                    <img src="{{ asset('storage/' . $existingImage) }}" class="object-cover w-full h-full">
                                @else
                                    <div class="text-center text-slate-400">
                                        <i class="ri-image-line text-2xl"></i>
                                        <p class="text-xs mt-1">Upload</p>
                                    </div>
                                @endif
                            </div>

                            <input id="category-image-input" type="file" wire:model="image" accept="image/*" class="hidden">

                            <div class="flex flex-col items-start gap-2">
                                <button type="button" onclick="document.getElementById('category-image-input').click()"
                                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-100 transition">
                                    <i class="ri-upload-2-line text-sm"></i>
                                    {{ $image || $existingImage ? 'Change Image' : 'Choose Image' }}
                                </button>
                                <span class="text-[11px] text-slate-500 leading-4">JPG, PNG, WEBP up to 2MB</span>
                            </div>

                            @error('image')
                                <p class="text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Form Fields -->
                        <div class="grid grid-cols-1 gap-4">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Name -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Name</label>
                                    <input
                                        wire:model.live="title"
                                        class="w-full h-10 rounded-lg border border-slate-300 px-3 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none"
                                    >
                                    @error('title')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Slug</label>
                                    <input
                                        wire:model.live="slug"
                                        class="w-full h-10 rounded-lg border border-slate-300 px-3 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none"
                                    >
                                    @error('slug')
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Description</label>
                                <div wire:ignore>
                                    <textarea id="category-description-editor"></textarea>
                                </div>
                            </div>

                            <!-- Toggles -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                <label class="flex items-center gap-2 h-10 px-3 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-700">
                                    <input type="checkbox" wire:model.live="isSubcategory"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span>Subcategory</span>
                                </label>

                                <label class="flex items-center gap-2 h-10 px-3 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-700">
                                    <input type="checkbox" wire:model.live="status"
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span>Active</span>
                                </label>
                            </div>

                            <!-- Parent -->
                            @if($isSubcategory)
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Parent Category</label>
                                    <select
                                        wire:model.live="parentId"
                                        class="w-full h-10 rounded-lg border border-slate-300 px-3 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none"
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
                    <div class="rounded-2xl border border-slate-200 bg-white p-4 sm:p-5 space-y-4">
                        <p class="text-sm font-semibold text-slate-800">SEO Settings</p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Meta Title</label>
                                <input wire:model.live="meta_title" placeholder="Meta title"
                                    class="w-full h-10 rounded-lg border border-slate-300 px-3 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Meta Keywords</label>
                                <input wire:model.live="meta_keywords" placeholder="keyword, keyword"
                                    class="w-full h-10 rounded-lg border border-slate-300 px-3 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none">
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">Meta Description</label>
                                <textarea wire:model.live="meta_description" rows="3" placeholder="Meta description"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none"></textarea>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- 🔷 Footer -->
                <div class="flex justify-end gap-3 px-6 py-4 border-t border-slate-200 bg-white">

                    <button @click="modalOpen=false"
                        class="px-4 py-2 text-sm rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100 transition">
                        Cancel
                    </button>

                    <button
                        wire:click="save"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition disabled:opacity-60"
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

<script>
    function categoryModalEditor() {
        return {
            modalOpen: false,
            editorId: 'category-description-editor',
            retries: 0,
            maxRetries: 10,

            init() {
                this.$watch('modalOpen', (isOpen) => {
                    if (isOpen) {
                        this.$nextTick(() => this.mountEditor(true));
                    } else {
                        this.destroyEditor();
                    }
                });

                document.addEventListener('livewire:navigated', () => {
                    if (this.modalOpen) {
                        this.$nextTick(() => this.mountEditor(true));
                    }
                });

                if (window.Livewire?.hook) {
                    window.Livewire.hook('morph.updated', ({ el }) => {
                        if (this.modalOpen && (this.$root.contains(el) || this.$root === el)) {
                            this.$nextTick(() => this.mountEditor(true));
                        }
                    });
                }
            },

            mountEditor(forceSync = false) {
                if (!window.tinymce) return;

                const textarea = document.getElementById(this.editorId);
                if (!textarea || !textarea.isConnected) {
                    if (this.retries < this.maxRetries) {
                        this.retries++;
                        setTimeout(() => this.mountEditor(forceSync), 120);
                    }
                    return;
                }
                this.retries = 0;

                const existing = window.tinymce.get(this.editorId);
                if (existing) {
                    if (forceSync) {
                        existing.setContent(this.$wire.get('description') || '');
                    }
                    return;
                }

                window.tinymce.init({
                    selector: `#${this.editorId}`,
                    menubar: false,
                    branding: false,
                    height: 210,
                    plugins: 'lists link table code autoresize',
                    toolbar: 'undo redo | bold italic underline | bullist numlist | link table | removeformat code',
                    content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; }',
                    setup: (editor) => {
                        editor.on('init', () => {
                            editor.setContent(this.$wire.get('description') || '');
                        });

                        const sync = () => this.$wire.set('description', editor.getContent(), false);
                        editor.on('change keyup input undo redo', sync);
                    },
                });
            },

            destroyEditor() {
                window.tinymce?.get(this.editorId)?.remove();
                this.retries = 0;
            },
        };
    }
</script>