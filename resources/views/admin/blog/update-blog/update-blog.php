<?php

use App\Models\Blog;
use App\Models\BlogCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts::admin')] class extends Component
{
    use WithFileUploads;

    public int $id;
    public string $title = '';
    public string $slug = '';
    public ?int $category_id = null;
    public $featured_image = null;
    public ?string $existing_image = null;
    public string $content = '';
    public bool $is_published = false;

    public function mount($id): void
    {
        $this->id = (int) $id;
        $this->loadBlog();
    }

    protected function loadBlog(): void
    {
        $blog = Blog::findOrFail($this->id);
        $this->title = $blog->title;
        $this->slug = $blog->slug;
        $this->category_id = $blog->category_id;
        $this->existing_image = $blog->featured_image;
        $this->content = $blog->content;
        $this->is_published = (bool) $blog->is_published;
    }

    protected function makeUniqueSlug(string $value): string
    {
        $base = Str::slug($value);
        $slug = $base;
        $suffix = 2;

        while (Blog::query()->where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:blogs,slug,' . $this->id . ',id'],
            'category_id' => ['required', 'exists:blog_categories,id'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $blog = Blog::findOrFail($this->id);

        if ($this->featured_image) {
            if ($blog->featured_image && Storage::disk('public')->exists($blog->featured_image)) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $blog->featured_image = $this->featured_image->store('blogs', 'public');
        }

        $blog->update([
            'title' => $validated['title'],
            'slug' => $this->makeUniqueSlug($validated['slug']),
            'category_id' => $validated['category_id'],
            'content' => $validated['content'],
            'is_published' => $this->is_published,
        ]);

        $this->dispatch('toast-show', ['message' => 'Blog updated', 'type' => 'success', 'position' => 'top-right']);
        redirect()->route('admin.blogs');
    }

    public function render()
    {
        return view('admin.blog.update-blog.update-blog', [
            'categories' => BlogCategory::orderBy('title')->get(),
        ]);
    }
};
