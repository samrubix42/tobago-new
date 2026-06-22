<div class="max-w-6xl mx-auto space-y-8 p-2">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-200">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Category</h1>
            <p class="text-sm text-slate-500 mt-1">Modify the category details and configuration.</p>
        </div>
        <a href="{{ route('admin.categories') }}" 
           class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 transition"
        >
            <i class="ri-arrow-left-line"></i>
            Back to List
        </a>
    </div>

    <!-- Form Section -->
    <form wire:submit.prevent="save" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Side: Main Form Fields -->
        <div class="lg:col-span-2 space-y-6">
            <!-- General Info Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
                <div class="flex items-center gap-2 border-b border-slate-100 pb-4">
                    <i class="ri-information-line text-blue-600 text-lg"></i>
                    <h3 class="text-base font-semibold text-slate-900">General Information</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Title -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Category Name <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            wire:model.live.debounce.250ms="title"
                            placeholder="e.g. Hooks & Bowls"
                            class="w-full h-11 rounded-lg border border-slate-300 px-4 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition"
                        >
                        @error('title')
                            <p class="text-xs text-red-500 mt-1.5"><i class="ri-error-warning-line"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">URL Slug <span class="text-red-500">*</span></label>
                        <input
                            type="text"
                            wire:model.live="slug"
                            placeholder="e.g. hooks-and-bowls"
                            class="w-full h-11 rounded-lg border border-slate-300 px-4 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition"
                        >
                        @error('slug')
                            <p class="text-xs text-red-500 mt-1.5"><i class="ri-error-warning-line"></i> {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- H2 Heading -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">H2 Sub-Heading</label>
                    <input
                        type="text"
                        wire:model="h2"
                        placeholder="Add sub-heading text for page layout"
                        class="w-full h-11 rounded-lg border border-slate-300 px-4 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition"
                    >
                    @error('h2')
                        <p class="text-xs text-red-500 mt-1.5"><i class="ri-error-warning-line"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Description (TinyMCE Full Tool) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Description</label>
                    <div
                        wire:ignore
                        x-data="{
                            editor: null,
                            init() {
                                const textarea = this.$refs.textarea;
                                const existing = window.tinymce?.get(textarea.id);
                                if (existing) existing.remove();

                                window.tinymce.init({
                                    target: textarea,
                                    height: 320,
                                    menubar: 'file edit view insert format tools table help',
                                    branding: false,
                                    plugins: 'preview importcss searchreplace autolink directionality code visualblocks visualchars fullscreen image link media table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion',
                                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | alignnumlist bullist | link image | table media | forecolor backcolor removeformat | charmap emoticons | code fullscreen preview',
                                    content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; }',
                                    setup: (editor) => {
                                        this.editor = editor;
                                        editor.on('init', () => editor.setContent(textarea.value || ''));
                                        editor.on('Change KeyUp Undo Redo', () => this.$wire.set('description', editor.getContent()));
                                    },
                                });
                            },
                            destroy() {
                                if (this.editor) this.editor.remove();
                            }
                        }"
                    >
                        <textarea
                            id="category-edit-description"
                            x-ref="textarea"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition"
                            placeholder="Describe this category..."
                        >{{ $description }}</textarea>
                    </div>
                    @error('description')
                        <p class="text-xs text-red-500 mt-1.5"><i class="ri-error-warning-line"></i> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- SEO Settings Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
                <div class="flex items-center gap-2 border-b border-slate-100 pb-4">
                    <i class="ri-search-eye-line text-blue-600 text-lg"></i>
                    <h3 class="text-base font-semibold text-slate-900">SEO Settings</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Meta Title -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Meta Title</label>
                        <input
                            type="text"
                            wire:model.live="meta_title"
                            placeholder="SEO optimized title"
                            class="w-full h-11 rounded-lg border border-slate-300 px-4 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition"
                        >
                        <p class="text-[10px] text-slate-400 mt-1">Recommended: 60 characters or less.</p>
                    </div>

                    <!-- Meta Keywords -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Meta Keywords</label>
                        <input
                            type="text"
                            wire:model.live="meta_keywords"
                            placeholder="e.g. shisha, hookah accessories"
                            class="w-full h-11 rounded-lg border border-slate-300 px-4 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition"
                        >
                    </div>
                </div>

                <!-- Meta Description -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Meta Description</label>
                    <textarea
                        wire:model.live="meta_description"
                        rows="3"
                        placeholder="Detailed snippet for search engine listings..."
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition"
                    ></textarea>
                    <p class="text-[10px] text-slate-400 mt-1">Recommended: 160 characters or less.</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Sidebar Cards -->
        <div class="space-y-6">
            <!-- Publishing & Configuration Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
                <div class="flex items-center gap-2 border-b border-slate-100 pb-4">
                    <i class="ri-settings-4-line text-blue-600 text-lg"></i>
                    <h3 class="text-base font-semibold text-slate-900">Configuration</h3>
                </div>

                <!-- Active Toggle -->
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-150">
                    <span class="text-sm font-medium text-slate-700">Active status</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="status" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                </div>

                <!-- Subcategory Toggle -->
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-150">
                    <span class="text-sm font-medium text-slate-700">Subcategory</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="isSubcategory" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <!-- Parent Dropdown (Conditional) -->
                @if($isSubcategory)
                    <div class="space-y-2 pt-2 animate-fade-in">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Parent Category</label>
                        <select
                            wire:model.live="parentId"
                            class="w-full h-11 rounded-lg border border-slate-300 px-3 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition"
                        >
                            <option value="">Select Parent</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                            @endforeach
                        </select>
                        @error('parentId')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>

            <!-- Image Upload Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
                <div class="flex items-center gap-2 border-b border-slate-100 pb-4">
                    <i class="ri-image-line text-blue-600 text-lg"></i>
                    <h3 class="text-base font-semibold text-slate-900">Category Banner</h3>
                </div>

                <!-- Drag & Drop / Preview area -->
                <div 
                    x-data="{ isDropping: false }" 
                    @dragover.prevent="isDropping = true" 
                    @dragleave.prevent="isDropping = false" 
                    @drop.prevent="isDropping = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));"
                    @click="$refs.fileInput.click()"
                    :class="isDropping ? 'border-blue-500 bg-blue-50/50' : 'border-slate-300 bg-slate-50/50'"
                    class="relative w-full h-48 rounded-xl border border-dashed flex flex-col items-center justify-center overflow-hidden hover:border-blue-500 hover:bg-slate-50/30 transition cursor-pointer group"
                >
                    @if($image)
                        <img src="{{ $image->temporaryUrl() }}" class="object-cover w-full h-full">
                    @elseif($existingImage)
                        <img src="{{ asset('storage/' . $existingImage) }}" class="object-cover w-full h-full">
                    @else
                        <div class="text-center text-slate-400 p-4">
                            <div class="h-12 w-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center mx-auto shadow-sm group-hover:scale-105 transition">
                                <i class="ri-upload-cloud-2-line text-xl text-blue-600"></i>
                            </div>
                            <p class="text-xs font-semibold mt-3 text-slate-700">Click or Drag Image</p>
                            <p class="text-[10px] mt-1 text-slate-400">JPG, PNG, WEBP up to 2MB</p>
                        </div>
                    @endif

                    <div wire:loading wire:target="image" class="absolute inset-0 bg-white/90 backdrop-blur-xs flex flex-col items-center justify-center">
                        <i class="ri-loader-4-line text-2xl text-blue-600 animate-spin mb-1"></i>
                        <span class="text-xs font-medium text-slate-500">Uploading preview...</span>
                    </div>
                </div>

                <input type="file" wire:model="image" x-ref="fileInput" accept="image/*" class="hidden">

                @if($image || $existingImage)
                    <div class="flex items-center gap-2">
                        <button type="button" @click="$refs.fileInput.click()"
                                class="flex-1 inline-flex justify-center items-center gap-1.5 rounded-lg border border-slate-350 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-sm transition">
                            <i class="ri-refresh-line"></i> Change
                        </button>
                        @if($image)
                            <button type="button" wire:click="$set('image', null)"
                                    class="inline-flex justify-center items-center h-8 w-8 rounded-lg bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-100 hover:border-rose-300 shadow-sm transition">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        @endif
                    </div>
                @endif

                @error('image')
                    <p class="text-xs text-red-500 mt-1.5"><i class="ri-error-warning-line"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Action buttons -->
            <div class="flex gap-4">
                <a href="{{ route('admin.categories') }}"
                   class="flex-1 inline-flex justify-center items-center h-12 rounded-xl border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition"
                >
                    Cancel
                </a>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="flex-1 inline-flex justify-center items-center gap-2 h-12 rounded-xl bg-blue-600 px-4 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition disabled:opacity-60"
                >
                    <i class="ri-save-line text-base"></i>
                    <span>Save Changes</span>
                </button>
            </div>
        </div>
    </form>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in { animation: fade-in 0.2s ease-out forwards; }
</style>