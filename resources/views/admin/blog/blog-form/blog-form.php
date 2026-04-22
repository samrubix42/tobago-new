<?php

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts::admin')] class extends Component
{
    use WithFileUploads;

    public ?int $blogId = null;

    public string $title = '';
    public string $slug = '';
    public ?int $category_id = null;
    public $featured_image = null;
    public ?string $existing_image = null;
    public string $content = '';
    public string $tags = '';
    public bool $is_published = false;

    public function mount(?int $id = null): void
    {
        $this->blogId = $id;

        if ($this->blogId) {
            $blog = Blog::findOrFail($this->blogId);
            $this->title = $blog->title;
            $this->slug = $blog->slug;
            $this->category_id = $blog->category_id;
            $this->existing_image = $blog->featured_image;
            $this->content = $blog->content;
            $this->tags = (string) $blog->tags;
            $this->is_published = (bool) $blog->is_published;
        }
    }

    public function updatedTitle(string $value): void
    {
        if ($this->slug === '' || $this->blogId === null) {
            $this->slug = Str::slug($value);
        }
    }

    protected function makeUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value);
        $slug = $base;
        $suffix = 2;

        while (
            Blog::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:blogs,slug,' . ($this->blogId ?? 'NULL') . ',id'],
            'category_id' => ['required', 'exists:blog_categories,id'],
            'content' => ['required', 'string'],
            'tags' => ['nullable', 'string', 'max:1000'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $blog = $this->blogId ? Blog::findOrFail($this->blogId) : new Blog();

        $imagePath = $blog->featured_image;
        if ($this->featured_image) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $this->featured_image->store('blogs', 'public');
        }

        $blog->fill([
            'title' => $validated['title'],
            'slug' => $this->makeUniqueSlug($validated['slug'], $this->blogId),
            'category_id' => $validated['category_id'],
            'content' => $validated['content'],
            'tags' => Blog::normalizeTags($validated['tags'] ?? null),
            'featured_image' => $imagePath,
            'is_published' => $this->is_published,
        ]);

        if (! $this->blogId) {
            $blog->author_id = auth()->id();
        }

        $blog->save();

        $this->dispatch('toast-show', [
            'message' => $this->blogId ? 'Blog updated' : 'Blog created',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->redirectRoute('admin.blogs', navigate: true);
    }

    public function render()
    {
        return view('admin.blog.blog-form.blog-form', [
            'categories' => BlogCategory::query()->where('is_active', true)->orderBy('title')->get(),
        ]);
    }
};
