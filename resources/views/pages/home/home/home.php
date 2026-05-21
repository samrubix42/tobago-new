<?php

use App\Models\Blog;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    protected function productsByCategorySlug(string $slug, int $limit = 8): Collection
    {
        return Product::query()
            ->with(['images', 'category'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->whereHas('category', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->latest()
            ->take($limit)
            ->get();
    }

   
    #[Computed]
    public function featuredProducts(): Collection
    { 
        // Fetch products specifically belonging to the "tobac-go-hookah" category slug and are featured
        $exclusive = Product::query()
            ->with(['images', 'category'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->whereHas('category', function($q) {
                $q->where('slug', 'tobac-go-hookah');
            })
            ->latest()
            ->take(8)
            ->get();
         

        if ($exclusive->isNotEmpty()) {
            return $exclusive;
        }

        // Fallback to active products specifically belonging to the "tobac-go-hookah" category
        return Product::query()
            ->with(['images', 'category'])
            ->where('status', 'active')
            ->whereHas('category', function($q) {
                $q->where('slug', 'tobac-go-hookah');
            })
            ->latest()
            ->take(8)
            ->get();
    }

    #[Computed]
    public function hookahChillumProducts(): Collection
    {
        return $this->productsByCategorySlug('hookah-chillum');
    }

    #[Computed]
    public function pipeAndHandleProducts(): Collection
    {
        return $this->productsByCategorySlug('pipe-and-handle');
    }

    #[Computed]
    public function latestProducts(): Collection
    {
        return Product::query()
            ->with(['images'])
            ->where('status', 'active')
            ->latest()
            ->take(4)
            ->get();
    }

    #[Computed]
    public function categories(): Collection
    {
        return Category::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->take(8)
            ->get();
    }

    #[Computed]
    public function latestBlogs(): Collection
    {
        return Blog::query()
            ->with('category')
            ->where('is_published', true)
            ->latest()
            ->take(3)
            ->get();
    }

    #[Computed]
    public function testimonials(): Collection
    {
        return Testimonial::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->take(9)
            ->get();
    }

    #[Computed]
    public function productCount(): int
    {
        return Product::query()->where('status', 'active')->count();
    }

    #[Computed]
    public function categoryCount(): int
    {
        return Category::query()->where('is_active', true)->count();
    }

    #[Computed]
    public function categoryCards(): array
    {
        $cards = $this->categories->map(function (Category $category): array {
            $image = $category->image
                ? asset('storage/' . ltrim($category->image, '/'))
                : asset('images/hero.png');

            return [
                'title' => $category->title,
                'image' => $image,
                'slug' => $category->slug,
            ];
        })->values()->all();

        if (!empty($cards)) {
            return $cards;
        }

        return [
            ['title' => 'Hookahs', 'image' => asset('images/hero.png'), 'slug' => 'hookahs'],
            ['title' => 'Charcoal', 'image' => asset('images/hero.png'), 'slug' => 'charcoal'],
            ['title' => 'Accessories', 'image' => asset('images/hero.png'), 'slug' => 'accessories'],
            ['title' => 'Vapes', 'image' => asset('images/hero.png'), 'slug' => 'vapes'],
        ];
    }

    #[Computed]
    public function testimonialItems(): array
    {
        $fromDb = $this->testimonials->map(function (Testimonial $testimonial): array {
            return [
                'name' => $testimonial->name,
                'city' => $testimonial->city ?: 'Verified Buyer',
                'review' => $testimonial->review,
            ];
        })->values()->all();

        if (!empty($fromDb)) {
            return $fromDb;
        }

        return [
            ['name' => 'Rahul Sharma', 'city' => 'Verified Buyer', 'review' => 'Amazing quality hookah! Smooth airflow and premium design.'],
            ['name' => 'Aman Verma', 'city' => 'Delhi', 'review' => 'Fast delivery and excellent packaging.'],
            ['name' => 'Sahil Khan', 'city' => 'Mumbai', 'review' => 'Best hookah store online. Great variety.'],
            ['name' => 'Rohit Singh', 'city' => 'Patna', 'review' => 'Affordable and stylish products.'],
            ['name' => 'Ali Khan', 'city' => 'Lucknow', 'review' => 'Very smooth experience. Loved it!'],
            ['name' => 'Vikas Kumar', 'city' => 'Kolkata', 'review' => 'Top-notch quality and fast shipping.'],
        ];
    }

    public function price(?string $amount): string
    {
        return number_format((float) $amount, 2);
    }

    public function shortText(?string $text, int $limit = 80): string
    {
        return Str::limit(trim(strip_tags((string) $text)), $limit);
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
};

