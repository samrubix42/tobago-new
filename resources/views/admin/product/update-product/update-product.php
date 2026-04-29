<?php

namespace App\Livewire\Admin\Product;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\InventoryLog;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts::admin')] class extends Component 
{
    use WithFileUploads;

    public int $currentStep = 1;
    public ?int $productId = null;

    // Basic Info
    public string $name = '';
    public string $slug = '';
    public string $sku = '';
    public ?string $description = null;
    public ?string $feature_and_specifications = null;
    public ?int $category_id = null;
    public string $status = 'draft';
    public $is_featured = false;
    public $is_trending = false;
    public ?string $meta_title = null;
    public ?string $meta_description = null;
    public ?string $meta_keywords = null;


    // Pricing
    public ?float $cost_price = null;
    public $selling_price = 0;
    public $compare_price = 0;

    // Stock
    public $stock = 0;
    public ?int $hurry_stock = null;
    public $is_out_of_stock = false;

    // Images
    public $images = [];
    public $productImages = []; 

    protected function rules(): array
    {
        return [
            1 => [
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'string', 'max:255', 'unique:products,slug,' . $this->productId],
                'sku' => ['required', 'string', 'max:50', 'unique:products,sku,' . $this->productId],
                'description' => ['nullable', 'string'],
                'feature_and_specifications' => ['nullable', 'string'],
                'category_id' => ['required', 'exists:categories,id'],
                'is_featured' => ['boolean'],
                'is_trending' => ['boolean'],
                'meta_title' => ['nullable', 'string', 'max:255'],
                'meta_description' => ['nullable', 'string'],
                'meta_keywords' => ['nullable', 'string'],
            ],
            2 => [
                'cost_price' => ['nullable', 'numeric', 'min:0'],
                'selling_price' => ['required', 'numeric', 'min:0'],
                'compare_price' => ['nullable', 'numeric', 'min:0'],
            ],
            3 => [
                'stock' => ['required', 'integer', 'min:0'],
                'hurry_stock' => ['nullable', 'integer', 'min:0'],
                'is_out_of_stock' => ['boolean'],
            ],
            4 => [
                'status' => ['required', 'in:active,inactive,draft'],
            ]
        ];
    }

    public function mount(int $id): void
    {
        $this->productId = $id;
        $product = Product::with('images')->findOrFail($id);
        
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->sku = $product->sku ?? '';
        $this->description = $product->short_description;
        $this->feature_and_specifications = $product->feature_and_specifications;
        $this->category_id = $product->category_id;
        $this->status = $product->status;
        $this->is_featured = $product->is_featured;
        $this->is_trending = $product->is_trending;
        $this->cost_price = (float) $product->cost_price;
        $this->selling_price = (float) $product->selling_price;
        $this->compare_price = (float) ($product->compare_price ?? 0);
        $this->stock = $product->stock;
        $this->hurry_stock = $product->hurry_stock;
        $this->is_out_of_stock = (bool) $product->is_out_of_stock;
        $this->meta_title = $product->meta_title;
        $this->meta_description = $product->meta_description;
        $this->meta_keywords = $product->meta_keywords;
        $this->productImages = $product->images->toArray();

    }

    public function updatedName($value): void
    {
        
            $this->slug = Str::slug($value);
      
    }

    public function setStep(int $step): void
    {
        if ($step < $this->currentStep) {
            $this->currentStep = $step;
            $this->dispatch('product-step-changed', ['step' => $this->currentStep]);
            return;
        }

        $this->validate($this->rules()[$this->currentStep] ?? []);
        $this->currentStep = $step;
        $this->dispatch('product-step-changed', ['step' => $this->currentStep]);
    }

    public function nextStep(): void
    {
        $this->validate($this->rules()[$this->currentStep] ?? []);

        if ($this->currentStep < 4) {
            $this->currentStep++;
            $this->dispatch('product-step-changed', ['step' => $this->currentStep]);
        }
    }

    public function prevStep(): void
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->dispatch('product-step-changed', ['step' => $this->currentStep]);
        }
    }

    public function saveProduct()
    {
        $this->validate($this->rules()[4]);

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->slug),
            'sku' => $this->sku ?: Product::generateSkuFromName($this->name, $this->productId),
            'short_description' => $this->description,
            'feature_and_specifications' => $this->feature_and_specifications,
            'category_id' => $this->category_id,
            'status' => $this->status,
            'is_featured' => (bool) $this->is_featured,
            'is_trending' => (bool) $this->is_trending,
            'cost_price' => (is_numeric($this->cost_price) && $this->cost_price !== '') ? (float) $this->cost_price : null,
            'selling_price' => (float) $this->selling_price,
            'compare_price' => (is_numeric($this->compare_price) && $this->compare_price !== '') ? (float) $this->compare_price : null,
            'stock' => (int) $this->stock,
            'hurry_stock' => (is_numeric($this->hurry_stock) && $this->hurry_stock !== '') ? (int) $this->hurry_stock : null,
            'is_out_of_stock' => (bool) $this->is_out_of_stock,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
        ];


        DB::transaction(function () use ($data) {
            /** @var Product $product */
            $product = Product::query()->lockForUpdate()->findOrFail($this->productId);

            if (! $product->sku) {
                $data['sku'] = Product::generateSkuFromName($this->name, $product->id);
            }

            $oldStock = (int) $product->stock;
            $newStock = (int) $this->stock;
            $delta = $newStock - $oldStock;

            if ($delta !== 0) {
                InventoryLog::create([
                    'product_id' => $product->id,
                    'type' => 'adjust',
                    'quantity' => $delta,
                    'reference_type' => 'admin',
                    'reference_id' => null,
                    'note' => 'Stock updated from product edit',
                ]);
            }

            $product->fill($data);
            $product->save();
        });

        $this->dispatch('toast-show', [
            'message' => 'Product updated successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);
        
        return $this->redirectRoute('admin.products.index', navigate: true);
    }

    public function uploadImages(): void
    {
        $this->validate([
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if (!empty($this->images)) {
            foreach ($this->images as $image) {
                $maxSort = ProductImage::where('product_id', $this->productId)->max('sort_order');
                $nextSort = is_numeric($maxSort) ? ((int) $maxSort + 1) : 1;

                $hasPrimary = ProductImage::where('product_id', $this->productId)->where('is_primary', true)->exists();

                $path = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $this->productId,
                    'image' => $path,
                    'is_primary' => !$hasPrimary,
                    'sort_order' => $nextSort,
                ]);
            }
        }

        $this->images = [];
        $this->productImages = ProductImage::where('product_id', $this->productId)->orderBy('sort_order')->get()->toArray();

        $this->dispatch('toast-show', [
            'message' => 'Images uploaded successfully!',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    public function deleteImage(int $imageId): void
    {
        $image = ProductImage::findOrFail($imageId);
        
        if (Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }
        
        $wasPrimary = $image->is_primary;
        $productId = $image->product_id;
        $image->delete();

        if ($wasPrimary) {
            $newPrimary = ProductImage::where('product_id', $productId)->orderBy('sort_order')->first();
            if ($newPrimary) {
                $newPrimary->update(['is_primary' => true]);
            }
        }

        $this->productImages = ProductImage::where('product_id', $this->productId)->orderBy('sort_order')->get()->toArray();
    }

    public function setPrimary(int $imageId): void
    {
        ProductImage::where('product_id', $this->productId)->update(['is_primary' => false]);
        ProductImage::where('id', $imageId)->update(['is_primary' => true]);

        $this->productImages = ProductImage::where('product_id', $this->productId)->orderBy('sort_order')->get()->toArray();
    }

    public function handleImageSort($item, $position): void
    {
        $image = ProductImage::find($item);

        if (!$image) return;

        $image->update(['sort_order' => (int) $position]);

        $this->productImages = ProductImage::where('product_id', $this->productId)->orderBy('sort_order')->get()->toArray();
    }

    public function render()
    {
        return view('admin::product.update-product.update-product', [
            'categories' => Category::with('parent')->orderBy('title')->get(),
        ]);
    }
};
