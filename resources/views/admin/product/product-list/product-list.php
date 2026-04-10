<?php

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::admin')] class extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteProduct(int $id): void
    {
        $product = Product::findOrFail($id);
        $product->delete();

        $this->dispatch('toast-show', [
            'message' => 'Product deleted successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    public function updateStatus(int $id, string $status): void
    {
        if (!in_array($status, ['active', 'inactive', 'draft'], true)) {
            return;
        }

        $product = Product::findOrFail($id);
        $product->update(['status' => $status]);

        $this->dispatch('toast-show', [
            'message' => 'Status updated to ' . ucfirst($status),
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    public function render()
    {
        $products = Product::query()
            ->with(['category', 'images'])
            ->when($this->search !== '', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('admin::product.product-list.product-list', [
            'products' => $products,
        ]);
    }
};