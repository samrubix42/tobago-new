<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($image) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($image->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image);
            }
        });
    }

    protected $fillable = [
        'product_id',
        'image',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
