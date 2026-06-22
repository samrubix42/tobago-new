<?php

use App\Models\Category;
use App\Models\RecommendedCategory;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

new #[Layout('layouts::admin')] class extends Component
{
    public string $search = '';
    public ?int $deleteId = null;
    public ?int $recommendationCategoryId = null;
    public $categories = [];
    public $recommendationOptions = [];
    public array $recommendedCategoryIds = [];
    public array $recommendationTitles = [];

    #[On('refresh-category-list')]
    public function mount(): void
    {
        $this->resetForm();
        $this->loadCategories();
    }

    public function updatedSearch(): void
    {
        $this->loadCategories();
    }

    public function loadCategories(): void
    {
        $this->categories = Category::query()
            ->with('parent')
            ->when($this->search !== '', function ($query) {
                $query->where(function ($nested) {
                    $nested->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('slug', 'like', '%' . $this->search . '%')
                        ->orWhere('meta_title', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('title')
            ->get();

        $this->recommendationOptions = Category::query()
            ->with(['children' => fn ($query) => $query->orderBy('title')])
            ->whereNull('parent_id')
            ->orderBy('title')
            ->get(['id', 'title']);
    }

    public function resetForm(): void
    {
        $this->resetValidation();
        $this->deleteId = null;
        $this->recommendationCategoryId = null;
        $this->recommendedCategoryIds = [];
        $this->recommendationTitles = [];
    }

    public function confirmDelete(int $categoryId): void
    {
        $this->deleteId = $categoryId;
    }

    public function openRecommendModal(int $categoryId): void
    {
        Category::findOrFail($categoryId);

        $this->resetValidation();
        $this->recommendationCategoryId = $categoryId;
        $recommendations = RecommendedCategory::query()
            ->where('category_id', $categoryId)
            ->get(['recommended_category_id', 'title']);

        $this->recommendedCategoryIds = $recommendations
            ->pluck('recommended_category_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $this->recommendationTitles = $recommendations
            ->mapWithKeys(fn ($recommendation) => [
                (int) $recommendation->recommended_category_id => (string) ($recommendation->title ?? ''),
            ])
            ->all();
    }

    public function updatedRecommendedCategoryIds(): void
    {
        $selectedIds = collect($this->recommendedCategoryIds)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values()
            ->all();

        $this->recommendedCategoryIds = $selectedIds;
        $this->recommendationTitles = collect($this->recommendationTitles)
            ->filter(fn ($_title, $categoryId) => in_array((int) $categoryId, $selectedIds, true))
            ->map(fn ($title) => trim((string) $title))
            ->all();
    }

    public function saveRecommendations(): void
    {
        $this->validate([
            'recommendationCategoryId' => ['required', 'integer', 'exists:categories,id'],
            'recommendedCategoryIds' => ['array'],
            'recommendedCategoryIds.*' => ['integer', 'exists:categories,id', 'distinct'],
            'recommendationTitles' => ['array'],
            'recommendationTitles.*' => ['nullable', 'string', 'max:255'],
        ]);

        $categoryId = (int) $this->recommendationCategoryId;
        $selectedIds = collect($this->recommendedCategoryIds)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->reject(fn ($id) => $id === $categoryId)
            ->unique()
            ->values();

        RecommendedCategory::query()
            ->where('category_id', $categoryId)
            ->delete();

        if ($selectedIds->isNotEmpty()) {
            $now = now();

            RecommendedCategory::query()->insert(
                $selectedIds->map(fn ($recommendedId) => [
                    'category_id' => $categoryId,
                    'recommended_category_id' => $recommendedId,
                    'title' => trim((string) ($this->recommendationTitles[$recommendedId] ?? '')) ?: null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all()
            );
        }

        $this->dispatch('toast-show', [
            'message' => 'Recommended categories updated successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->dispatch('close-recommend-modal');
    }

    public function delete(?int $categoryId = null): void
    {
        $id = $categoryId ?? $this->deleteId;

        if (! $id) {
            return;
        }

        $category = Category::findOrFail($id);

        if ($category->children()->exists()) {
            $this->dispatch('toast-show', [
                'message' => 'Delete Its subcategories first Before deleting this category.',
                'type' => 'warning',
                'position' => 'top-right',
            ]);

            $this->dispatch('close-delete-modal');
            $this->deleteId = null;
            return;
        }

        $category->delete();

        $this->dispatch('toast-show', [
            'message' => 'Category deleted successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->loadCategories();

        $this->dispatch('close-delete-modal');
        $this->deleteId = null;
    }
};
