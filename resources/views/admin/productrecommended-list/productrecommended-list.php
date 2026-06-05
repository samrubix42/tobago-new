<?php

use App\Models\Product;
use App\Models\ProductRecommendation;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::admin')] class extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    public ?int $baseProductId = null;
    public $recommendedProductIds = [];
    public $recommendationTitles = [];
    
    // For selecting recommended products in the modal
    public string $modalSearch = '';
    public int $perPage = 10;

    public function mount(): void
    {
        // Check if product_id is passed in the URL query string
        $urlProductId = request()->query('product_id');
        if ($urlProductId) {
            $product = Product::find($urlProductId);
            if ($product) {
                $this->openRecommendModal((int) $urlProductId);
            }
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openRecommendModal(int $productId): void
    {
        $this->resetForm();
        
        $product = Product::findOrFail($productId);
        $this->baseProductId = $product->id;

        $recommendations = ProductRecommendation::query()
            ->where('product_id', $productId)
            ->get();

        $this->recommendedProductIds = $recommendations
            ->pluck('recommended_product_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $this->recommendationTitles = $recommendations
            ->mapWithKeys(fn ($rec) => [
                (int) $rec->recommended_product_id => (string) ($rec->title ?? ''),
            ])
            ->all();

        $this->dispatch('open-recommend-modal');
    }

    public function resetForm(): void
    {
        $this->resetValidation();
        $this->baseProductId = null;
        $this->recommendedProductIds = [];
        $this->recommendationTitles = [];
        $this->modalSearch = '';
    }

    public function updatedRecommendedProductIds(): void
    {
        $selectedIds = collect($this->recommendedProductIds)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->unique()
            ->values()
            ->all();

        $this->recommendedProductIds = $selectedIds;
        $this->recommendationTitles = collect($this->recommendationTitles)
            ->filter(fn ($_title, $productId) => in_array((int) $productId, $selectedIds, true))
            ->map(fn ($title) => trim((string) $title))
            ->all();
    }

    public function saveRecommendations(): void
    {
        $this->validate([
            'baseProductId' => ['required', 'integer', 'exists:products,id'],
            'recommendedProductIds' => ['array'],
            'recommendedProductIds.*' => ['integer', 'exists:products,id', 'distinct'],
            'recommendationTitles' => ['array'],
            'recommendationTitles.*' => ['nullable', 'string', 'max:255'],
        ]);

        $productId = (int) $this->baseProductId;
        $selectedIds = collect($this->recommendedProductIds)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $id > 0)
            ->reject(fn ($id) => $id === $productId)
            ->unique()
            ->values();

        ProductRecommendation::query()
            ->where('product_id', $productId)
            ->delete();

        if ($selectedIds->isNotEmpty()) {
            $now = now();

            ProductRecommendation::query()->insert(
                $selectedIds->map(fn ($recId) => [
                    'product_id' => $productId,
                    'recommended_product_id' => $recId,
                    'title' => trim((string) ($this->recommendationTitles[$recId] ?? '')) ?: null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all()
            );
        }

        $this->dispatch('toast-show', [
            'message' => 'Product recommendations updated successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->dispatch('close-recommend-modal');
        $this->resetForm();
    }

    public function deleteRecommendations(int $productId): void
    {
        ProductRecommendation::query()
            ->where('product_id', $productId)
            ->delete();

        $this->dispatch('toast-show', [
            'message' => 'All recommendations removed for this product.',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    public function render()
    {
        $products = Product::query()
            ->with(['category', 'images', 'recommendedProducts'])
            ->when($this->search !== '', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate($this->perPage);

        $recommendationOptions = Product::query()
            ->when($this->baseProductId, fn ($q) => $q->where('id', '!=', $this->baseProductId))
            ->when($this->modalSearch !== '', function ($query) {
                $query->where('name', 'like', '%' . $this->modalSearch . '%')
                      ->orWhere('slug', 'like', '%' . $this->modalSearch . '%');
            })
            ->orderBy('name')
            ->get(['id', 'name', 'sku']);

        $selectedBaseProduct = $this->baseProductId ? Product::find($this->baseProductId) : null;

        return view('admin::productrecommended-list.productrecommended-list', [
            'products' => $products,
            'recommendationOptions' => $recommendationOptions,
            'selectedBaseProduct' => $selectedBaseProduct,
        ]);
    }
};