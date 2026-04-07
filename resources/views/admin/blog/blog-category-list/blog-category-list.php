<?php

use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::admin')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    public ?int $categoryId = null;
    public ?int $deleteId = null;

    public string $title = '';
    public string $slug = '';
    public bool $is_active = true;

    #[On('refresh-blog-category-list')]
    public function mount(): void
    {
        $this->resetForm();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedTitle(string $value): void
    {
        if ($this->slug === '' || $this->categoryId === null) {
            $this->slug = Str::slug($value);
        }
    }

    public function resetForm(): void
    {
        $this->resetValidation();
        $this->categoryId = null;
        $this->deleteId = null;
        $this->title = '';
        $this->slug = '';
        $this->is_active = true;
    }

    public function openEditModal(int $id): void
    {
        $category = BlogCategory::findOrFail($id);

        $this->resetValidation();
        $this->categoryId = $category->id;
        $this->title = $category->title;
        $this->slug = $category->slug;
        $this->is_active = (bool) $category->is_active;
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
    }

    protected function makeUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $base = Str::slug($value);
        $slug = $base;
        $suffix = 2;

        while (
            BlogCategory::query()
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
            'slug' => ['required', 'string', 'max:255', 'unique:blog_categories,slug,' . ($this->categoryId ?? 'NULL') . ',id'],
            'is_active' => ['boolean'],
        ]);

        $category = $this->categoryId ? BlogCategory::findOrFail($this->categoryId) : new BlogCategory();

        $category->fill([
            'title' => $validated['title'],
            'slug' => $this->makeUniqueSlug($validated['slug'], $this->categoryId),
            'is_active' => $this->is_active,
        ]);

        $category->save();

        $this->dispatch('toast-show', [
            'message' => 'Blog category saved successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->dispatch('close-modal');
        $this->dispatch('refresh-blog-category-list');
        $this->resetForm();
    }

    public function delete(?int $id = null): void
    {
        $id = $id ?? $this->deleteId;

        if (! $id) {
            return;
        }

        $category = BlogCategory::findOrFail($id);

        if ($category->blogs()->exists()) {
            $this->dispatch('toast-show', [
                'message' => 'Delete blog posts in this category first.',
                'type' => 'warning',
                'position' => 'top-right',
            ]);

            $this->dispatch('close-delete-modal');
            $this->deleteId = null;
            return;
        }

        $category->delete();

        $this->dispatch('toast-show', [
            'message' => 'Blog category deleted successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->dispatch('close-delete-modal');
        $this->deleteId = null;
        $this->resetPage();
        $this->dispatch('refresh-blog-category-list');
    }

    public function render()
    {
        $query = BlogCategory::query()
            ->when($this->search !== '', function ($q) {
                $q->where(function ($nested) {
                    $nested->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('slug', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('title');

        return view('admin.blog.blog-category-list.blog-category-list', [
            'categories' => $query->paginate($this->perPage),
        ]);
    }
};

