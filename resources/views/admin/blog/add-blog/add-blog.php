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

    public function mount(): void
    {
        $this->resetForm();
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
        ]);

        $this->dispatch('toast-show', ['message' => 'Blog created', 'type' => 'success', 'position' => 'top-right']);
        redirect()->route('admin.blogs');
    }

    public function render()
    {
        return view('admin.blog.add-blog.add-blog', [
            'categories' => BlogCategory::orderBy('title')->get(),
        ]);
    }
};
