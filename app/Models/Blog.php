<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Blog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'author_id',
        'category_id',
        'slug',
        'content',
        'tags',
        'featured_image',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'bool',
    ];

    protected $appends = [
        'tag_list',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getTagListAttribute(): array
    {
        return $this->parseTags($this->tags);
    }

    public static function parseTags(?string $tags): array
    {
        return collect(explode(',', (string) $tags))
            ->map(fn (string $tag) => trim($tag))
            ->filter()
            ->unique(fn (string $tag) => Str::slug($tag))
            ->values()
            ->all();
    }

    public static function normalizeTags(?string $tags): ?string
    {
        $parsed = static::parseTags($tags);

        return empty($parsed) ? null : implode(', ', $parsed);
    }

    public function tagItems(): Collection
    {
        return collect($this->tag_list)->map(function (string $tag): array {
            return [
                'label' => $tag,
                'slug' => Str::slug($tag),
            ];
        });
    }
}
