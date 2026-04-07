<?php

use App\Models\Blog;
use App\Models\BlogCategory;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

new #[Layout('layouts::admin')] class extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public int $perPage = 10;

    public ?int $blogId = null;
    public ?int $deleteId = null;

    public string $title = '';
    public string $slug = '';
    public ?int $category_id = null;
    public $featured_image = null;
    public ?string $existing_image = null;
    public string $content = '';
    public bool $is_published = false;

    #[On('refresh-blog-list')]
    public function mount(): void
    {
        $this->resetForm();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->resetValidation();
        $this->blogId = null;
        $this->title = '';
        $this->slug = '';
        $this->category_id = null;
        $this->featured_image = null;
        $this->existing_image = null;
        $this->content = '';
        $this->is_published = false;
        $this->deleteId = null;
    }

    public function openEditModal(int $id): void
    {
        $blog = Blog::findOrFail($id);

        $this->resetValidation();
        $this->blogId = $blog->id;
        $this->title = $blog->title;
        $this->slug = $blog->slug;
        $this->category_id = $blog->category_id;
        $this->existing_image = $blog->featured_image;
        $this->content = $blog->content;
        $this->is_published = (bool) $blog->is_published;
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:blogs,slug,' . ($this->blogId ?? 'NULL') . ',id'],
            'category_id' => ['required', 'exists:blog_categories,id'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $blog = $this->blogId ? Blog::findOrFail($this->blogId) : new Blog();

        $imagePath = $blog->featured_image;
        if ($this->featured_image) {
            if ($imagePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($imagePath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $this->featured_image->store('blogs', 'public');
        }

        $blog->fill([
            'title' => $validated['title'],
            'slug' => \Illuminate\Support\Str::slug($validated['slug']),
            'category_id' => $validated['category_id'],
            'content' => $validated['content'],
            'featured_image' => $imagePath,
            'is_published' => $this->is_published,
            'author_id' => auth()->id(),
        ]);

        $blog->save();

        $this->dispatch('toast-show', [
            'message' => 'Blog saved successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->dispatch('close-modal');
        $this->dispatch('refresh-blog-list');
        $this->resetForm();
    }

    public function delete(?int $id = null): void
    {
        $id = $id ?? $this->deleteId;

        if (! $id) {
            return;
        }

        $blog = Blog::findOrFail($id);
        $blog->delete();

        $this->dispatch('toast-show', [
            'message' => 'Blog deleted successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->dispatch('close-delete-modal');
        $this->deleteId = null;
        $this->resetPage();
        $this->dispatch('refresh-blog-list');
    }

    public function render()
    {
        $query = Blog::query()->with('category')->when($this->search !== '', fn($q) => $q->where('title', 'like', '%' . $this->search . '%'));

        return view('admin.blog.blog-list', [
            'blogs' => $query->latest()->paginate($this->perPage),
            'categories' => BlogCategory::orderBy('name')->get(),
        ]);
    }
};
