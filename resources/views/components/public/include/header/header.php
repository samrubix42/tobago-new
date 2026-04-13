<?php

use App\Models\Product;
use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component
{
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->search = trim($this->search);
    }

    public function searchResults(): Collection
    {
        if (mb_strlen($this->search) < 2) {
            return collect();
        }

        return Product::query()
            ->with(['images', 'category'])
            ->where('status', 'active')
            ->where(function ($query): void {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%');
            })
            ->latest('id')
            ->limit(5)
            ->get();
    }

    public function searchImage(Product $product): string
    {
        $image = optional($product->images->firstWhere('is_primary', true))->image
            ?? optional($product->images->first())->image;

        return $image ? asset('storage/' . ltrim((string) $image, '/')) : asset('images/hero.png');
    }
};