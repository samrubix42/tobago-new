<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'cost_price',
        'selling_price',
        'compare_price',
        'stock',
        'hurry_stock',
        'is_out_of_stock',
        'status',
        'is_featured',
        'is_trending',
    ];

    protected $casts = [
        'is_out_of_stock' => 'boolean',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'compare_price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(ProductImage::class)->where('is_primary', true);
    }

    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }

    public static function generateSkuFromName(string $name, ?int $ignoreId = null): string
    {
        $base = strtoupper(Str::slug($name, '-'));
        $base = $base !== '' ? $base : 'SKU';
        $base = Str::limit($base, 22, '');

        $candidate = $base;
        $i = 0;

        while (static::query()
            ->where('sku', $candidate)
            ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
            ->exists()
        ) {
            $i++;
            $suffix = strtoupper(Str::random(4));
            $candidate = Str::limit($base, 18, '') . '-' . $suffix;

            if ($i > 25) {
                $candidate = 'SKU-' . strtoupper(Str::random(8));
                break;
            }
        }

        return $candidate;
    }
}
