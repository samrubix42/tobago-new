<?php

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts::admin')] class extends Component
{
    use WithFileUploads;

    public ?int $categoryId = null;
    public string $title = '';
    public ?string $h2 = null;
    public string $slug = '';
    public ?string $description = null;
    public $image = null;
    public ?string $existingImage = null;

    public bool $isSubcategory = false;
    public ?int $parentId = null;
    public bool $status = true;

    public ?string $meta_title = null;
    public ?string $meta_description = null;
    public ?string $meta_keywords = null;

    public $parentCategories = [];

    public function mount(int $id): void
    {
        $category = Category::findOrFail($id);

        $this->categoryId = $category->id;
        $this->title = $category->title;
        $this->h2 = $category->h2;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->existingImage = $category->image;
        $this->isSubcategory = $category->parent_id !== null;
        $this->parentId = $category->parent_id;
        $this->status = (bool) $category->is_active;
        $this->meta_title = $category->meta_title;
        $this->meta_description = $category->meta_description;
        $this->meta_keywords = $category->meta_keywords;

        $this->loadParentCategories();
    }

    public function loadParentCategories(): void
    {
        $this->parentCategories = Category::query()
            ->whereNull('parent_id')
            ->when($this->categoryId, fn($query) => $query->where('id', '!=', $this->categoryId))
            ->orderBy('title')
            ->get();
    }

    public function updatedTitle(string $value): void
    {
        if ($this->slug === '') {
            $this->slug = Str::slug($value);
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'h2' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:categories,slug,' . $this->categoryId . ',id'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'parentId' => ['nullable', 'exists:categories,id'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
        ]);

        $category = Category::findOrFail($this->categoryId);

        $imagePath = $category->image;
        if ($this->image) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $this->image->store('categories', 'public');
        }

        $category->update([
            'parent_id' => $this->isSubcategory ? $this->parentId : null,
            'title' => $validated['title'],
            'h2' => $validated['h2'],
            'slug' => Str::slug($validated['slug']),
            'description' => $validated['description'],
            'image' => $imagePath,
            'is_active' => $this->status,
            'meta_title' => $validated['meta_title'],
            'meta_description' => $validated['meta_description'],
            'meta_keywords' => $validated['meta_keywords'],
        ]);

        $this->dispatch('toast-show', [
            'message' => 'Category updated successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        return $this->redirect(route('admin.categories'));
    }
};