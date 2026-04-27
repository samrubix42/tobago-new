<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public string $couponCode = '';

    public function increment(int $itemId): void
    {
        $cart = $this->resolveCart(false);
        if (! $cart) {
            return;
        }

        $item = CartItem::query()
            ->where('cart_id', $cart->id)
            ->whereKey($itemId)
            ->first();

        if (! $item) {
            return;
        }

        $item->loadMissing('product');
        $product = $item->product;

        if (! $product) {
            session()->flash('cart_message', 'This product is no longer available.');
            session()->flash('cart_message_type', 'warning');
            return;
        }

        $availableStock = (int) $product->stock;
        if ($product->is_out_of_stock || $availableStock <= 0) {
            session()->flash('cart_message', $product->name . ' is out of stock.');
            session()->flash('cart_message_type', 'warning');
            return;
        }

        if ((int) $item->quantity >= $availableStock) {
            session()->flash('cart_message', 'Only ' . $availableStock . ' item(s) available for ' . $product->name . '.');
            session()->flash('cart_message_type', 'warning');
            return;
        }

        $item->quantity += 1;
        $item->total = $item->quantity * (float) $item->price;
        $item->save();

        $recalc = $this->recalculateCart($cart->fresh());
        $this->notifyCouponStateAfterCartChange($recalc);
        $this->dispatch('cart-updated', count: current_cart_items_count());
    }

    public function decrement(int $itemId): void
    {
        $cart = $this->resolveCart(false);
        if (! $cart) {
            return;
        }

        $item = CartItem::query()
            ->where('cart_id', $cart->id)
            ->whereKey($itemId)
            ->first();

        if (! $item) {
            return;
        }

        if ($item->quantity <= 1) {
            $item->delete();
        } else {
            $item->quantity -= 1;
            $item->total = $item->quantity * (float) $item->price;
            $item->save();
        }

        $recalc = $this->recalculateCart($cart->fresh());
        $this->notifyCouponStateAfterCartChange($recalc);
        $this->dispatch('cart-updated', count: current_cart_items_count());
    }

    public function removeItem(int $itemId): void
    {
        $cart = $this->resolveCart(false);
        if (! $cart) {
            return;
        }

        CartItem::query()
            ->where('cart_id', $cart->id)
            ->whereKey($itemId)
            ->delete();

        $recalc = $this->recalculateCart($cart->fresh());
        $this->notifyCouponStateAfterCartChange($recalc);

        $this->dispatch('toast-show', [
            'message' => 'Item removed from cart.',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->dispatch('cart-updated', count: current_cart_items_count());
    }

    public function applyCoupon(): void
    {
        $this->validate([
            'couponCode' => ['required', 'string', 'max:50'],
        ]);

        $cart = $this->resolveCart(false);
        if (! $cart || ! $cart->items()->exists()) {
            session()->flash('coupon_message', 'Cart is empty.');
            session()->flash('coupon_message_type', 'warning');
            return;
        }

        $normalizedCode = trim($this->couponCode);

        $coupon = Coupon::query()
            ->whereRaw('LOWER(code) = ?', [strtolower($normalizedCode)])
            ->first();

        if (! $coupon || ! $coupon->is_active) {
            session()->flash('coupon_message', 'Invalid or inactive coupon code.');
            session()->flash('coupon_message_type', 'warning');
            return;
        }

        $subtotal = (float) $cart->items()->sum('total');
        if ($subtotal < (float) $coupon->min_amount) {
            $remainingAmount = max((float) $coupon->min_amount - $subtotal, 0);
            session()->flash('coupon_message', 'Add Rs ' . number_format($remainingAmount, 2) . ' more to apply this coupon. Minimum order amount is Rs ' . number_format((float) $coupon->min_amount, 2) . '.');
            session()->flash('coupon_message_type', 'warning');
            return;
        }

        $cart->update([
            'coupon_id' => $coupon->id,
        ]);

        $this->couponCode = (string) $coupon->code;

        $this->recalculateCart($cart->fresh());

        session()->flash('coupon_message', 'Coupon applied successfully.');
        session()->flash('coupon_message_type', 'success');
    }

    public function removeCoupon(): void
    {
        $cart = $this->resolveCart(false);
        if (! $cart) {
            return;
        }

        $cart->update([
            'coupon_id' => null,
        ]);

        $this->couponCode = '';
        $this->recalculateCart($cart->fresh());
    }

    public function useSuggestedCoupon(string $code): void
    {
        $this->couponCode = trim($code);
        $this->applyCoupon();
    }

    protected function resolveCart(bool $create = true): ?Cart
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

                if (! $create) {
                    return null;
                }

                return Cart::query()->create([
                    'user_id' => $userId,
                    'session_id' => $sessionId,
                ]);
            }

            return $cart;
        }

        if (! $create) {
            return Cart::query()
                ->whereNull('user_id')
                ->where('session_id', $sessionId)
                ->latest('id')
                ->first();
        }

        return Cart::query()->firstOrCreate([
            'user_id' => null,
            'session_id' => $sessionId,
        ]);
    }

    protected function recalculateCart(Cart $cart): array
    {
        $cart->loadMissing('items', 'coupon');

        $subtotal = (float) $cart->items->sum('total');
        $discount = 0.0;
        $couponRemoved = false;
        $removedCouponCode = null;
        $requiredAmount = 0.0;

        $coupon = $cart->coupon;
        if ($coupon) {
            $removedCouponCode = (string) $coupon->code;

            if (! $coupon->is_active) {
                $cart->coupon_id = null;
                $couponRemoved = true;
            } elseif ($subtotal < (float) $coupon->min_amount) {
                $requiredAmount = max((float) $coupon->min_amount - $subtotal, 0);
                $cart->coupon_id = null;
                $couponRemoved = true;
            } else {
                if ($coupon->type === 'percentage') {
                    $discount = ($subtotal * (float) $coupon->value) / 100;
                } else {
                    $discount = min((float) $coupon->value, $subtotal);
                }
            }
        }

        if ($subtotal <= 0) {
            if ($cart->coupon_id !== null) {
                $couponRemoved = true;
            }
            $cart->coupon_id = null;
            $discount = 0;
        }

        $cart->update([
            'coupon_id' => $cart->coupon_id,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => max($subtotal - $discount, 0),
        ]);

        return [
            'couponRemoved' => $couponRemoved,
            'couponCode' => $removedCouponCode,
            'requiredAmount' => $requiredAmount,
        ];
    }

    protected function notifyCouponStateAfterCartChange(array $recalc): void
    {
        if (! ($recalc['couponRemoved'] ?? false)) {
            return;
        }

        $couponCode = (string) ($recalc['couponCode'] ?? '');
        $requiredAmount = (float) ($recalc['requiredAmount'] ?? 0);

        if ($requiredAmount > 0) {
            session()->flash('coupon_message', 'Coupon ' . $couponCode . ' removed. Add Rs ' . number_format($requiredAmount, 2) . ' more to apply this coupon again.');
            session()->flash('coupon_message_type', 'warning');
            return;
        }

        session()->flash(
            'coupon_message',
            $couponCode
                ? 'Coupon ' . $couponCode . ' removed as it is no longer valid.'
                : 'Coupon removed as it is no longer valid.'
        );
        session()->flash('coupon_message_type', 'warning');
    }

    protected function calculateShipping(float $cartTotal): float
    {
        $deliveryFee = (float) app_setting('delivery_fee', 0);
        $freeDeliveryAmount = (float) app_setting('free_delivery_amount', 0);

        if ($freeDeliveryAmount > 0 && $cartTotal >= $freeDeliveryAmount) {
            return 0;
        }

        return max($deliveryFee, 0);
    }

    public function render()
    {
        $cart = $this->resolveCart(false);
        $subtotal = 0.0;

        if ($cart) {
            $this->recalculateCart($cart);
            $cart->load(['items.product.images', 'coupon']);
            $subtotal = (float) ($cart->subtotal ?? 0);

            if ($cart->coupon) {
                $this->couponCode = $cart->coupon->code;
            } else {
                $this->couponCode = '';
            }
        }

        $suggestedCoupons = Coupon::query()
            ->where('is_active', true)
            ->orderBy('min_amount')
            ->limit(6)
            ->get()
            ->map(function (Coupon $coupon) use ($subtotal) {
                $minAmount = (float) $coupon->min_amount;
                $remainingAmount = max($minAmount - $subtotal, 0);

                return [
                    'code' => (string) $coupon->code,
                    'type' => (string) $coupon->type,
                    'value' => (float) $coupon->value,
                    'min_amount' => $minAmount,
                    'remaining_amount' => $remainingAmount,
                    'is_applicable' => $remainingAmount <= 0,
                ];
            })
            ->sortBy([
                ['is_applicable', 'desc'],
                ['remaining_amount', 'asc'],
                ['min_amount', 'asc'],
            ])
            ->take(4)
            ->values();

        $shippingAmount = $cart ? $this->calculateShipping((float) $cart->total) : 0;
        $grandTotal = ($cart?->total ?? 0) + $shippingAmount;

        return view('pages.cart.cart', [
            'cart' => $cart,
            'items' => $cart?->items ?? collect(),
            'shippingAmount' => $shippingAmount,
            'grandTotal' => $grandTotal,
            'suggestedCoupons' => $suggestedCoupons,
        ]);
    }
};
