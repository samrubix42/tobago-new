<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent_id',
        'title',
        'slug',
        'description',
        'image',
        'is_active',
        'order',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order');
    }

    public function recommendedCategories(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'recommended_categories',
            'category_id',
            'recommended_category_id'
        )->withPivot('title')->withTimestamps();
    }

    public function recommendedByCategories(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'recommended_categories',
            'recommended_category_id',
            'category_id'
        )->withPivot('title')->withTimestamps();
    }

    public function recommendationLinks(): HasMany
    {
        return $this->hasMany(RecommendedCategory::class, 'category_id');
    }
}
