<?php

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $search = '';
    public ?int $categoryId = null;
    public ?int $deleteId = null;

    public string $title = '';
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

    public function mount(): void
    {
        $this->resetForm();
    }

    public function render()
    {
        $categories = Category::query()
            ->with('parent')
            ->when($this->search !== '', function ($query) {
                $query->where(function ($nested) {
                    $nested->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('slug', 'like', '%' . $this->search . '%')
                        ->orWhere('meta_title', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('order')
            ->orderBy('title')
            ->get();

        $parentCategories = Category::query()
            ->whereNull('parent_id')
            ->when($this->categoryId, fn ($query) => $query->where('id', '!=', $this->categoryId))
            ->orderBy('title')
            ->get();

        return view('admin.category-list.category-list', [
            'categories' => $categories,
            'parentCategories' => $parentCategories,
        ])->layout('layouts.admin', [
            'title' => 'Categories',
        ]);
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
        $this->title = '';
        $this->slug = '';
        $this->description = null;
        $this->image = null;
        $this->existingImage = null;
        $this->isSubcategory = false;
        $this->parentId = null;
        $this->status = true;
        $this->meta_title = null;
        $this->meta_description = null;
        $this->meta_keywords = null;
        $this->deleteId = null;
    }

    public function openEditModal(int $categoryId): void
    {
        $category = Category::findOrFail($categoryId);

        $this->resetValidation();
        $this->categoryId = $category->id;
        $this->title = $category->title;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->image = null;
        $this->existingImage = $category->image;
        $this->isSubcategory = $category->parent_id !== null;
        $this->parentId = $category->parent_id;
        $this->status = (bool) $category->is_active;
        $this->meta_title = $category->meta_title;
        $this->meta_description = $category->meta_description;
        $this->meta_keywords = $category->meta_keywords;
    }

    public function confirmDelete(int $categoryId): void
    {
        $this->deleteId = $categoryId;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:categories,slug,' . ($this->categoryId ?? 'NULL') . ',id'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'parentId' => ['nullable', 'exists:categories,id'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
        ]);

        $category = $this->categoryId ? Category::findOrFail($this->categoryId) : new Category();

        $imagePath = $category->image;
        if ($this->image) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $this->image->store('categories', 'public');
        }

        $category->fill([
            'parent_id' => $this->isSubcategory ? $this->parentId : null,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['slug']),
            'description' => $validated['description'],
            'image' => $imagePath,
            'is_active' => $this->status,
            'meta_title' => $validated['meta_title'],
            'meta_description' => $validated['meta_description'],
            'meta_keywords' => $validated['meta_keywords'],
        ]);

        if (! $category->exists) {
            $category->order = (int) Category::max('order') + 1;
        }

        $category->save();

        $this->dispatch('close-modal');
        $this->resetForm();
    }

    public function delete(?int $categoryId = null): void
    {
        $id = $categoryId ?? $this->deleteId;

        if (! $id) {
            return;
        }

        $category = Category::findOrFail($id);

        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        $this->dispatch('close-delete-modal');
        $this->deleteId = null;
    }

    public function handleCategorySort($item, $position): void
    {
        $category = Category::find($item);

        if (! $category) {
            return;
        }

        $category->update(['order' => (int) $position]);
    }
};
