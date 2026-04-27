<?php

use App\Models\Blog;
use App\Models\BlogCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

new #[Layout('layouts::admin')] class extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $slug = '';
    public ?int $category_id = null;
    public $featured_image = null;
    public string $content = '';
    public bool $is_published = false;
    public ?string $meta_title = null;
    public ?string $meta_description = null;
    public ?string $meta_keywords = null;
    public $categories = [];

    public function mount(): void
    {
        $this->resetForm();
        $this->categories = BlogCategory::orderBy('title')->get();
    }



    public function resetForm(): void
    {
        $this->resetValidation();
        $this->title = '';
        $this->slug = '';
        $this->category_id = null;
        $this->featured_image = null;
        $this->content = '';
        $this->is_published = false;
    }

    public function updatedTitle(string $value): void
    {
        if ($this->slug === '') {
            $this->slug = Str::slug($value);
        }
    }

    protected function makeUniqueSlug(string $value): string
    {
        $base = Str::slug($value);
        $slug = $base;
        $suffix = 2;

        while (Blog::query()->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('blogs', 'slug')],
            'category_id' => ['required', 'exists:blog_categories,id'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $imagePath = null;
        if ($this->featured_image) {
            $imagePath = $this->featured_image->store('blogs', 'public');
        }

        $slugSource = trim((string) ($validated['slug'] ?? ''));

        Blog::create([
            'title' => $validated['title'],
            'slug' => $this->makeUniqueSlug($slugSource !== '' ? $slugSource : $validated['title']),
            'category_id' => $validated['category_id'],
            'content' => $validated['content'],
            'featured_image' => $imagePath,
            'is_published' => $this->is_published,
            'author_id' => auth()->id(),
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
        ]);

        $this->dispatch('toast-show', ['message' => 'Blog created', 'type' => 'success', 'position' => 'top-right']);
        $this->redirect(route('admin.blogs'), navigate: true);
    }
};
?>

<div class="max-w-4xl mx-auto p-6 bg-white rounded-2xl border border-slate-100 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-900">Add Blog</h2>
            <p class="text-sm text-slate-500 mt-1">Create a new blog post.</p>
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
                    id="blog-add-content"
                    x-ref="textarea"
                    class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900"
                    placeholder="Write your post...">{{ $content }}</textarea>
            </div>
            @error('content')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-4">
            <label class="text-xs font-medium text-gray-600">Featured Image</label>
            <input type="file" wire:model="featured_image" accept="image/*" class="text-sm text-gray-900">
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
                        <p class="text-[11px] text-gray-400 mt-1">Recommended: 60 characters or less.</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-600">Meta Keywords</label>
                        <input wire:model.live="meta_keywords" placeholder="keyword1, keyword2" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-medium text-gray-600">Meta Description</label>
                    <textarea wire:model.live="meta_description" rows="2" placeholder="SEO description (optional)" class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900"></textarea>
                    <p class="text-[11px] text-gray-400 mt-1">Recommended: 160 characters or less.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button wire:click="save" wire:loading.attr="disabled" class="px-4 py-2 rounded-md bg-blue-600 text-white disabled:opacity-60">Create</button>
        </div>
    </div>

</div>
