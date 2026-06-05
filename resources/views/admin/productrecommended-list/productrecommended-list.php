<?php

use App\Models\Product;
use App\Models\ProductRecommendation;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::admin')] class extends Component
{
    public ?int $baseProductId = null;
    public $recommendedProductIds = [];
    
    public string $searchQuery = '';

    public function mount(): void
    {
        $urlProductId = request()->query('product_id');
        if ($urlProductId) {
            $product = Product::find($urlProductId);
            if ($product) {
                $this->editRecommendations((int) $urlProductId);
                return;
            }
        }

        // Redirect to products list if no product ID is supplied
        $this->redirect(route('admin.products.index'), navigate: true);
    }

    public function editRecommendations(int $productId): void
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
    }

    public function resetForm(): void
    {
        $this->resetValidation();
        $this->baseProductId = null;
        $this->recommendedProductIds = [];
        $this->searchQuery = '';
    }

    public function cancelEdit()
    {
        $this->redirect(route('admin.products.index'), navigate: true);
    }

    public function addProduct(int $productId): void
    {
        $productId = (int) $productId;
        if (!in_array($productId, $this->recommendedProductIds, true)) {
            $this->recommendedProductIds[] = $productId;
        }
        $this->searchQuery = ''; // Clear search query
    }

    public function removeProduct(int $productId): void
    {
        $productId = (int) $productId;
        $this->recommendedProductIds = array_diff($this->recommendedProductIds, [$productId]);
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
    }

    public function saveRecommendations(): void
    {
        $this->validate([
            'baseProductId' => ['required', 'integer', 'exists:products,id'],
            'recommendedProductIds' => ['array'],
            'recommendedProductIds.*' => ['integer', 'exists:products,id', 'distinct'],
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

        $this->redirect(route('admin.products.index'), navigate: true);
    }

    public function render()
    {
        $selectedProducts = collect();
        if (!empty($this->recommendedProductIds)) {
            $selectedProducts = Product::query()
                ->whereIn('id', $this->recommendedProductIds)
                ->with(['images', 'category'])
                ->get();
        }

        $searchResults = collect();
        if ($this->searchQuery !== '') {
            $searchResults = Product::query()
                ->where('id', '!=', $this->baseProductId)
                ->whereNotIn('id', $this->recommendedProductIds)
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->searchQuery . '%')
                          ->orWhere('sku', 'like', '%' . $this->searchQuery . '%');
                })
                ->limit(6)
                ->get();
        }

        $selectedBaseProduct = $this->baseProductId ? Product::find($this->baseProductId) : null;

        return view('admin::productrecommended-list.productrecommended-list', [
            'searchResults' => $searchResults,
            'selectedProducts' => $selectedProducts,
            'selectedBaseProduct' => $selectedBaseProduct,
        ]);
    }
}