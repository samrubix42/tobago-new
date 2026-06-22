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

    public string $title = '';
    public ?string $h2 = null;
    public string $slug = '';
    public ?string $description = null;
    public $image = null;

    public bool $isSubcategory = false;
    public ?int $parentId = null;
    public bool $status = true;

    public ?string $meta_title = null;
    public ?string $meta_description = null;
    public ?string $meta_keywords = null;

    public $parentCategories = [];

    public function mount(): void
    {
        $this->loadParentCategories();
    }

    public function loadParentCategories(): void
    {
        $this->parentCategories = Category::query()
            ->whereNull('parent_id')
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
            'slug' => ['required', 'string', 'max:255', 'unique:categories,slug'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'parentId' => ['nullable', 'exists:categories,id'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
        ]);

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('categories', 'public');
        }

        Category::create([
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
            'message' => 'Category created successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        return $this->redirect(route('admin.categories'));
    }
};