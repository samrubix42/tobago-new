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

        $item->quantity += 1;
        $item->total = $item->quantity * (float) $item->price;
        $item->save();

        $this->recalculateCart($cart->fresh());
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

        $this->recalculateCart($cart->fresh());
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

        $this->recalculateCart($cart->fresh());

        $this->dispatch('toast-show', [
            'message' => 'Item removed from cart.',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    public function applyCoupon(): void
    {
        $this->validate([
            'couponCode' => ['required', 'string', 'max:50'],
        ]);

        $cart = $this->resolveCart(false);
        if (! $cart || ! $cart->items()->exists()) {
            $this->dispatch('toast-show', [
                'message' => 'Cart is empty.',
                'type' => 'warning',
                'position' => 'top-right',
            ]);
            return;
        }

        $coupon = Coupon::query()
            ->where('code', strtoupper(trim($this->couponCode)))
            ->first();

        if (! $coupon || ! $coupon->is_active) {
            $this->dispatch('toast-show', [
                'message' => 'Invalid or inactive coupon code.',
                'type' => 'warning',
                'position' => 'top-right',
            ]);
            return;
        }

        $subtotal = (float) $cart->items()->sum('total');
        if ($subtotal < (float) $coupon->min_amount) {
            $this->dispatch('toast-show', [
                'message' => 'Minimum amount for this coupon is Rs ' . number_format((float) $coupon->min_amount, 2),
                'type' => 'warning',
                'position' => 'top-right',
            ]);
            return;
        }

        $cart->update([
            'coupon_id' => $coupon->id,
        ]);

        $this->recalculateCart($cart->fresh());

        $this->dispatch('toast-show', [
            'message' => 'Coupon applied successfully.',
            'type' => 'success',
            'position' => 'top-right',
        ]);
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

        if ($subtotal <= 0) {
            $cart->coupon_id = null;
            $discount = 0;
        }

        $cart->update([
            'coupon_id' => $cart->coupon_id,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => max($subtotal - $discount, 0),
        ]);
    }

    public function render()
    {
        $cart = $this->resolveCart(false);

        if ($cart) {
            $this->recalculateCart($cart);
            $cart->load(['items.product.images', 'coupon']);

            if ($cart->coupon) {
                $this->couponCode = $cart->coupon->code;
            }
        }

        return view('pages.cart.cart', [
            'cart' => $cart,
            'items' => $cart?->items ?? collect(),
        ]);
    }
};