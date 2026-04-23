<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component
{
    private const PRICE_MIN_BOUND = 3000;
    private const PRICE_MAX_BOUND = 35000;

    public string $search = '';
    public string $sort = 'latest';
    public ?float $minPrice = self::PRICE_MIN_BOUND;
    public ?float $maxPrice = self::PRICE_MAX_BOUND;

    public ?string $routeCategorySlug = null;
    public ?string $routeSubcategorySlug = null;

    public int $perPage = 12;
    public int $loadedCount = 12;

    public function mount(?string $category = null, ?string $subcategory = null): void
    {
        $this->routeCategorySlug = $category;
        $this->routeSubcategorySlug = $subcategory;
        $this->minPrice = $this->sanitizePrice($this->minPrice, self::PRICE_MIN_BOUND);
        $this->maxPrice = $this->sanitizePrice($this->maxPrice, self::PRICE_MAX_BOUND);
        $this->normalizePriceRange();
    }

    protected $queryString = [
        'search' => ['except' => ''],
        'sort' => ['except' => 'latest'],
        'minPrice' => ['except' => self::PRICE_MIN_BOUND],
        'maxPrice' => ['except' => self::PRICE_MAX_BOUND],
    ];

    public function updatedSearch(): void
    {
        $this->resetFeed();
    }

    public function updatedSort(): void
    {
        $this->resetFeed();
    }

    public function updatedMinPrice(): void
    {
        $this->minPrice = $this->sanitizePrice($this->minPrice, self::PRICE_MIN_BOUND);
        $this->normalizePriceRange();
        $this->resetFeed();
    }

    public function updatedMaxPrice(): void
    {
        $this->maxPrice = $this->sanitizePrice($this->maxPrice, self::PRICE_MAX_BOUND);
        $this->normalizePriceRange();
        $this->resetFeed();
    }

    protected function resetFeed(): void
    {
        $this->loadedCount = $this->perPage;
    }

    public function loadMore(): void
    {
        if (! $this->hasMoreProducts()) {
            return;
        }

        $this->loadedCount += $this->perPage;
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->sort = 'latest';
        $this->minPrice = self::PRICE_MIN_BOUND;
        $this->maxPrice = self::PRICE_MAX_BOUND;
        $this->resetFeed();
    }

    protected function sanitizePrice(?float $value, int $fallback): float
    {
        if ($value === null) {
            return (float) $fallback;
        }

        return (float) max(self::PRICE_MIN_BOUND, min(self::PRICE_MAX_BOUND, (int) round($value)));
    }

    protected function normalizePriceRange(): void
    {
        if ($this->minPrice > $this->maxPrice) {
            $this->maxPrice = $this->minPrice;
        }
    }

    public function activeCategory(): ?Category
    {
        if (! $this->routeCategorySlug) {
            return null;
        }

        return Category::query()
            ->where('is_active', true)
            ->where('slug', $this->routeCategorySlug)
            ->first();
    }

    public function activeSubcategory(?Category $activeCategory): ?Category
    {
        if (! $activeCategory || ! $this->routeSubcategorySlug) {
            return null;
        }

        return Category::query()
            ->where('is_active', true)
            ->where('parent_id', $activeCategory->id)
            ->where('slug', $this->routeSubcategorySlug)
            ->first();
    }

    protected function productsQuery()
    {
        $activeCategory = $this->activeCategory();
        $activeSubcategory = $this->activeSubcategory($activeCategory);

        $query = Product::query()
            ->with(['images', 'category'])
            ->where('status', 'active');

        if ($activeSubcategory) {
            $query->where('category_id', $activeSubcategory->id);
        } elseif ($activeCategory) {
            $categoryIds = Category::query()
                ->where('is_active', true)
                ->where(function ($catQuery) use ($activeCategory): void {
                    $catQuery->whereKey($activeCategory->id)
                        ->orWhere('parent_id', $activeCategory->id);
                })
                ->pluck('id')
                ->all();

            $query->whereIn('category_id', $categoryIds);
        }

        if ($this->search !== '') {
            $query->where(function ($searchQuery): void {
                $searchQuery->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('sku', 'like', '%' . $this->search . '%')
                    ->orWhere('short_description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->minPrice !== null) {
            $query->where('selling_price', '>=', $this->minPrice);
        }

        if ($this->maxPrice !== null) {
            $query->where('selling_price', '<=', $this->maxPrice);
        }

        return match ($this->sort) {
            'price_asc' => $query->orderBy('selling_price'),
            'price_desc' => $query->orderByDesc('selling_price'),
            'name_asc' => $query->orderBy('name'),
            default => $query->latest('id'),
        };
    }

    public function hasMoreProducts(): bool
    {
        $total = (clone $this->productsQuery())->count();

        return $this->loadedCount < $total;
    }

    public function categories(): Collection
    {
        return Category::query()
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->with(['children' => fn ($query) => $query->where('is_active', true)->orderBy('order')])
            ->orderBy('order')
            ->get();
    }

    public function productImage(Product $product): string
    {
        $image = $product->images->firstWhere('is_primary', true)?->image
            ?? $product->images->first()?->image;

        return $image
            ? (str_starts_with((string) $image, 'http') ? (string) $image : asset('storage/' . ltrim((string) $image, '/')))
            : asset('images/hero.png');
    }

    public function addToCart(int $productId): void
    {
        $product = Product::query()
            ->whereKey($productId)
            ->where('status', 'active')
            ->first();

        if (! $product) {
            $this->dispatch('toast-show', [
                'message' => 'Product not available.',
                'type' => 'warning',
                'position' => 'top-right',
            ]);

            return;
        }

        if ($product->is_out_of_stock || $product->stock <= 0) {
            $this->dispatch('toast-show', [
                'message' => 'Product is out of stock.',
                'type' => 'warning',
                'position' => 'top-right',
            ]);

            return;
        }

        $cart = $this->resolveCart();

        $item = CartItem::query()->firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        $item->price = (float) $product->selling_price;
        $item->quantity = (int) ($item->exists ? $item->quantity + 1 : 1);
        $item->total = $item->quantity * $item->price;
        $item->save();

        $this->recalculateCart($cart->fresh());

        $this->dispatch('toast-show', [
            'message' => 'Product added to cart.',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->dispatch('cart-updated', count: current_cart_items_count());
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

    public function render()
    {
        $products = (clone $this->productsQuery())
            ->limit($this->loadedCount)
            ->get();

        $priceLimits = (object) [
            'min_price' => self::PRICE_MIN_BOUND,
            'max_price' => self::PRICE_MAX_BOUND,
        ];

        return view('pages.product.product.product', [
            'products' => $products,
            'hasMore' => $this->hasMoreProducts(),
            'categories' => $this->categories(),
            'activeCategory' => $this->activeCategory(),
            'activeSubcategory' => $this->activeSubcategory($this->activeCategory()),
            'priceLimits' => $priceLimits,
        ]);
    }
};
