<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class RecommendedCategory extends Model
{
    protected $fillable = [
        'category_id',
        'recommended_category_id',
        'title',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function recommendedCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'recommended_category_id');
    }
}
