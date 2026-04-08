<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'session_id',
        'coupon_id',
        'coupon_code',
        'coupon_type',
        'coupon_value',
        'subtotal',
        'discount',
        'shipping_amount',
        'total',
        'payment_method',
        'payment_status',
        'status',
        'delivery_type',
        'delivery_partner',
        'delivery_boy_name',
        'delivery_boy_phone',
        'awb_number',
        'tracking_url',
        'customer_name',
        'customer_phone',
        'customer_email',
        'address_line1',
        'address_line2',
        'landmark',
        'city',
        'state',
        'country',
        'pincode',
        'customer_note',
        'placed_at',
        'estimated_delivery_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'coupon_value' => 'decimal:2',
        'placed_at' => 'datetime',
        'estimated_delivery_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(OrderStatusLog::class)->orderBy('logged_at');
    }
}
