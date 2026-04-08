<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

if (! function_exists('app_setting')) {
    function app_setting(string $key, mixed $default = null): mixed
    {
        static $cache = null;

        if ($cache === null) {
            $cache = Setting::query()->pluck('value', 'key')->toArray();
        }

        if (array_key_exists($key, $cache)) {
            return $cache[$key];
        }

        if ($default !== null) {
            return $default;
        }

        $defaults = app_setting_defaults();

        return $defaults[$key] ?? null;
    }
}

if (! function_exists('app_setting_defaults')) {
    function app_setting_defaults(): array
    {
        return [
            'delivery_fee' => '0',
            'free_delivery_amount' => '0',
            'logo' => '',
            'project_name' => 'Tobac-Go',
            'phone_number' => '',
            'email' => '',
            'whatsapp_number' => '',
            'address' => '',
            'instagram_url' => '',
            'linkedin_url' => '',
            'facebook_url' => '',
            'x_url' => '',
            'application_name' => config('app.name', 'Laravel'),
            'footer_text' => '',
        ];
    }
}

if (! function_exists('merge_guest_cart_for_user')) {
    function merge_guest_cart_for_user(int $userId, ?string $sessionId = null): void
    {
        $sessionId = $sessionId ?: session()->getId();

        $guestCart = Cart::query()
            ->whereNull('user_id')
            ->where('session_id', $sessionId)
            ->latest('id')
            ->first();

        if (! $guestCart) {
            return;
        }

        $userCart = Cart::query()
            ->where('user_id', $userId)
            ->latest('id')
            ->first();

        if (! $userCart) {
            $guestCart->update([
                'user_id' => $userId,
                'session_id' => $sessionId,
            ]);
            return;
        }

        if ($userCart->items()->exists()) {
            return;
        }

        CartItem::query()
            ->where('cart_id', $guestCart->id)
            ->update(['cart_id' => $userCart->id]);

        if (! $userCart->coupon_id && $guestCart->coupon_id) {
            $userCart->coupon_id = $guestCart->coupon_id;
        }

        $subtotal = (float) $userCart->items()->sum('total');
        $discount = 0.0;
        $coupon = $userCart->coupon;

        if ($coupon && $coupon->is_active && $subtotal >= (float) $coupon->min_amount) {
            if ($coupon->type === 'percentage') {
                $discount = ($subtotal * (float) $coupon->value) / 100;
            } else {
                $discount = min((float) $coupon->value, $subtotal);
            }
        }

        $userCart->subtotal = $subtotal;
        $userCart->discount = $discount;
        $userCart->total = max($subtotal - $discount, 0);
        $userCart->save();

        $guestCart->delete();
    }
}

if (! function_exists('current_cart_items_count')) {
    function current_cart_items_count(): int
    {
        $query = Cart::query();

        if (Auth::check()) {
            $query->where('user_id', (int) Auth::id());
        } else {
            $query->whereNull('user_id')->where('session_id', session()->getId());
        }

        $cart = $query->latest('id')->first();
        if (! $cart) {
            return 0;
        }

        return (int) CartItem::query()
            ->where('cart_id', $cart->id)
            ->sum('quantity');
    }
}
