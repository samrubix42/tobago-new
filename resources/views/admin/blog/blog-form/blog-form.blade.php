<div class="max-w-4xl mx-auto p-6 bg-white rounded-2xl border border-slate-100 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">{{ $blogId ? 'Edit Blog' : 'Add Blog' }}</h2>
            <p class="text-sm text-slate-500 mt-1">{{ $blogId ? 'Update your blog post.' : 'Create a new blog post.' }}</p>
        </div>
        <a href="{{ route('admin.blogs') }}" wire:navigate class="text-sm text-slate-600 hover:text-slate-900">Back to list</a>
    </div>

    <div class="space-y-4">
        <div>
            <label class="text-xs font-medium text-gray-600">Title</label>
            <input wire:model.live="title" placeholder="Post title" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900">
            @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">Slug</label>
            <input wire:model.live="slug" placeholder="post-slug" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900">
            @error('slug')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">Category</label>
            <select wire:model.live="category_id" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900">
                <option value="">Select category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                @endforeach
            </select>
            @error('category_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">Content</label>
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
                            menubar: false,
                            plugins: 'lists link image paste help wordcount',
                            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image',
                            setup: (editor) => {
                                this.editor = editor;
                                editor.on('init', () => editor.setContent(textarea.value || ''));
                                editor.on('Change KeyUp Undo Redo', () => this.$wire.set('content', editor.getContent()));
                            },
                        });
                    },
                    destroy() {
                        if (this.editor) this.editor.remove();
                    },
                }"
            >
                <textarea
                    id="{{ $blogId ? 'blog-update-content' : 'blog-add-content' }}"
                    x-ref="textarea"
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900"
                    placeholder="Write your post..."
                >{{ $content }}</textarea>
            </div>
            @error('content')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="text-xs font-medium text-gray-600">Tags</label>
            <input wire:model.live="tags" placeholder="hookah, setup, cleaning" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900">
            <p class="mt-1 text-xs text-gray-500">Comma-separated tags used on the public blog filters.</p>
            @error('tags')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-4">
            <label class="text-xs font-medium text-gray-600">Featured Image</label>
            <input type="file" wire:model="featured_image" accept="image/*" class="text-sm text-gray-900">
            @if($existing_image)
                <img src="{{ asset('storage/' . $existing_image) }}" class="h-16 rounded-md object-cover" alt="Featured">
            @endif
            @error('featured_image')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-3">
            <label class="flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" wire:model.live="is_published" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                Published
            </label>
        </div>

        <div class="border-t border-slate-100 pt-4">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest mb-3">SEO Optimization</h3>
            
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-medium text-gray-600">Meta Title</label>
                        <input wire:model.live="meta_title" placeholder="SEO title (optional)" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600">Meta Keywords</label>
                        <input wire:model.live="meta_keywords" placeholder="keyword1, keyword2" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-medium text-gray-600">Meta Description</label>
                    <textarea wire:model.live="meta_description" rows="2" placeholder="SEO description (optional)" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900"></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button wire:click="save" wire:loading.attr="disabled" class="px-4 py-2 rounded-md bg-blue-600 text-white disabled:opacity-60">
                {{ $blogId ? 'Save' : 'Create' }}
            </button>
        </div>
    </div>
</div>
