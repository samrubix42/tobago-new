<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public Product $product;

    public Collection $relatedProducts;

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
            ->sortByDesc('is_primary')
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

        $hasCustomRecommendations = \App\Models\ProductRecommendation::where('product_id', $this->product->id)->exists();

        if ($hasCustomRecommendations) {
            $this->relatedProducts = $this->product->recommendedProducts()
                ->with(['images', 'category'])
                ->where('status', 'active')
                ->where('is_out_of_stock', false)
                ->where('stock', '>', 0)
                ->inRandomOrder()
                ->get();
        } else {
            $this->relatedProducts = Product::query()
                ->with(['images', 'category'])
                ->where('status', 'active')
                ->where('is_out_of_stock', false)
                ->where('stock', '>', 0)
                ->where('id', '!=', $this->product->id)
                ->when($this->product->category_id, fn ($query) => $query->where('category_id', $this->product->category_id))
                ->inRandomOrder()
                ->limit(4)
                ->get();
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

    public function addToCartById(int $productId): void
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

        if ($product->is_out_of_stock || (int) $product->stock <= 0) {
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

    public function schemaJson(): string
    {
        $domain = 'https://www.tobacgo.in';
        $productUrl = $domain . '/product/' . $this->product->slug;

        $images = $this->product->images
            ->sortByDesc('is_primary')
            ->map(fn($img) => $domain . '/storage/' . ltrim((string) $img->image, '/'))
            ->values()
            ->all();

        if (empty($images)) {
            $images = [$domain . '/images/hero.png'];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            '@id' => $productUrl . '#product',
            'name' => $this->product->name,
            'description' => $this->product->meta_description ?? strip_tags($this->product->short_description),
            'image' => $images,
            'sku' => $this->product->sku ?: 'N/A',
            'brand' => [
                '@type' => 'Brand',
                'name' => 'Tobac-Go',
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => $productUrl,
                'priceCurrency' => 'INR',
                'price' => number_format((float) $this->product->selling_price, 2, '.', ''),
                'availability' => $this->isOutOfStock() ? 'https://www.tobacgo.in/OutOfStock' : 'https://www.tobacgo.in/InStock',
                'shippingDetails' => [
                    '@type' => 'OfferShippingDetails',
                    'shippingRate' => [
                        '@type' => 'MonetaryAmount',
                        'value' => 0,
                        'currency' => 'INR',
                    ],
                    'shippingDestination' => [
                        '@type' => 'DefinedRegion',
                        'addressCountry' => 'IN',
                    ],
                    'deliveryTime' => [
                        '@type' => 'ShippingDeliveryTime',
                        'handlingTime' => [
                            '@type' => 'QuantitativeValue',
                            'minValue' => 1,
                            'maxValue' => 3,
                            'unitCode' => 'DAY',
                        ],
                        'transitTime' => [
                            '@type' => 'QuantitativeValue',
                            'minValue' => 3,
                            'maxValue' => 7,
                            'unitCode' => 'DAY',
                        ],
                    ],
                ],
            ],
        ];

        if ($this->product->category) {
            $schema['category'] = $this->product->category->title;
        }

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
};
