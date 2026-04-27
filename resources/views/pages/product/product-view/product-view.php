<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\RecommendedCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public Product $product;

    public Collection $relatedProducts;
    public Collection $recommendedSections;

    public array $galleryImages = [];

    public int $quantity = 1;

    public function mount(string $slug): void
    {
        $this->product = Product::query()
            ->with(['images', 'category'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $this->galleryImages = $this->product->images
            ->map(function ($image): array {
                return [
                    'src' => asset('storage/' . ltrim((string) $image->image, '/')),
                    'alt' => $this->product->name,
                ];
            })
            ->values()
            ->all();

        if (empty($this->galleryImages)) {
            $this->galleryImages = [[
                'src' => asset('images/hero.png'),
                'alt' => $this->product->name,
            ]];
        }

        $this->relatedProducts = Product::query()
            ->with(['images', 'category'])
            ->where('status', 'active')
            ->whereKeyNot($this->product->id)
            ->when($this->product->category_id, fn ($query) => $query->where('category_id', $this->product->category_id))
            ->latest('id')
            ->limit(4)
            ->get();

        if ($this->relatedProducts->count() < 4) {
            $existingIds = $this->relatedProducts
                ->pluck('id')
                ->push($this->product->id)
                ->all();

            $remaining = Product::query()
                ->with(['images', 'category'])
                ->where('status', 'active')
                ->whereNotIn('id', $existingIds)
                ->latest('id')
                ->limit(4 - $this->relatedProducts->count())
                ->get();

            $this->relatedProducts = $this->relatedProducts->concat($remaining)->values();
        }

        $this->recommendedSections = collect();

        if ($this->product->category_id) {
            $recommendations = RecommendedCategory::query()
                ->with(['recommendedCategory.parent'])
                ->where('category_id', $this->product->category_id)
                ->get();

            $this->recommendedSections = $recommendations
                ->map(function (RecommendedCategory $recommendation) {
                    $recommendedCategory = $recommendation->recommendedCategory;

                    if (! $recommendedCategory) {
                        return null;
                    }

                    $products = Product::query()
                        ->with(['images', 'category'])
                        ->where('status', 'active')
                        ->where('category_id', $recommendedCategory->id)
                        ->whereKeyNot($this->product->id)
                        ->latest('id')
                        ->limit(4)
                        ->get();

                    if ($products->isEmpty()) {
                        return null;
                    }

                    return [
                        'id' => $recommendedCategory->id,
                        'title' => trim((string) ($recommendation->title ?? '')) ?: $recommendedCategory->title,
                        'category' => $recommendedCategory,
                        'products' => $products,
                    ];
                })
                ->filter()
                ->values();
        }
    }

    public function productImageUrl(Product $product): string
    {
        $image = optional($product->images->firstWhere('is_primary', true))->image
            ?? optional($product->images->first())->image;

        return $image ? asset('storage/' . ltrim((string) $image, '/')) : asset('images/hero.png');
    }

    public function price(float|string|null $amount): string
    {
        return number_format((float) $amount, 2);
    }

    public function shortText(?string $text, int $limit = 80): string
    {
        return \Illuminate\Support\Str::limit(trim(strip_tags((string) $text)), $limit);
    }

    public function isOutOfStock(): bool
    {
        return $this->product->is_out_of_stock || (int) $this->product->stock <= 0;
    }

    public function shouldShowLowStock(): bool
    {
        $stock = (int) $this->product->stock;
        $hurryStock = (int) ($this->product->hurry_stock ?? 0);

        return $stock > 0 && $hurryStock > 0;
    }

    public function fomoStockValue(): int
    {
        $stock = (int) $this->product->stock;
        $hurryStock = (int) ($this->product->hurry_stock ?? 0);

        if ($stock <= 0 || $hurryStock <= 0) {
            return 0;
        }

        return $stock < $hurryStock ? $stock : $hurryStock;
    }

    public function discountPercent(): ?int
    {
        $sellingPrice = (float) $this->product->selling_price;
        $comparePrice = (float) $this->product->compare_price;

        if ($comparePrice <= 0 || $comparePrice <= $sellingPrice) {
            return null;
        }

        return (int) round((($comparePrice - $sellingPrice) / $comparePrice) * 100);
    }

    public function addToCart(int $qty = 1): void
    {
        if (! $this->syncCartItem($qty)) {
            return;
        }

        $this->dispatch('toast-show', [
            'message' => 'Product added to cart.',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    public function buyNow(int $qty = 1): void
    {
        if (! $this->syncCartItem($qty)) {
            return;
        }

        $this->redirectRoute('cart', navigate: true);
    }

    protected function syncCartItem(int $qty): bool
    {
        if ($this->isOutOfStock()) {
            $this->dispatch('toast-show', [
                'message' => 'Product is out of stock.',
                'type' => 'warning',
                'position' => 'top-right',
            ]);

            return false;
        }

        $qty = max(1, min($qty, (int) $this->product->stock));

        $cart = $this->resolveCart();

        $item = CartItem::query()->firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
        ]);

        $item->price = (float) $this->product->selling_price;
        $item->quantity = (int) ($item->exists ? $item->quantity + $qty : $qty);
        $item->total = $item->quantity * $item->price;
        $item->save();

        $this->recalculateCart($cart->fresh());

        $this->dispatch('cart-updated', count: current_cart_items_count());

        return true;
    }

    protected function resolveCart(): Cart
    {
        $sessionId = session()->getId();

        if (Auth::check()) {
            $userId = (int) Auth::id();

            $cart = Cart::query()
                ->where('user_id', $userId)
                ->latest('id')
                ->first();

            if (! $cart) {
                $guestCart = Cart::query()
                    ->whereNull('user_id')
                    ->where('session_id', $sessionId)
                    ->latest('id')
                    ->first();

                if ($guestCart) {
                    $guestCart->user_id = $userId;
                    $guestCart->save();

                    return $guestCart;
                }

                return Cart::query()->create([
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                ]);
            }

            return $cart;
        }

        return Cart::query()->firstOrCreate([
            'user_id' => null,
            'session_id' => $sessionId,
        ]);
    }

    protected function recalculateCart(Cart $cart): void
    {
        $cart->loadMissing('items', 'coupon');

        $subtotal = (float) $cart->items->sum('total');
        $discount = 0.0;

        $coupon = $cart->coupon;
        if ($coupon && $coupon->is_active && $subtotal >= (float) $coupon->min_amount) {
            if ($coupon->type === 'percentage') {
                $discount = ($subtotal * (float) $coupon->value) / 100;
            } else {
                $discount = min((float) $coupon->value, $subtotal);
            }
        }

        $total = max($subtotal - $discount, 0);

        $cart->update([
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
        ]);
    }
};
