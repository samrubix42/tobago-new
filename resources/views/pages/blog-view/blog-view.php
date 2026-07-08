<?php

use App\Models\Blog;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component
{
    public $slug;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    #[Computed]
    public function blog()
    {
        return Blog::with(['category', 'author'])
            ->where('slug', $this->slug)
            ->where('is_published', true)
            ->firstOrFail();
    }

    #[Computed]
    public function relatedPosts()
    {
        return Blog::query()
            ->with(['category'])
            ->where('is_published', true)
            ->where('id', '!=', $this->blog->id)
            ->where('category_id', $this->blog->category_id)
            ->latest()
            ->take(3)
            ->get();
    }

    #[Computed]
    public function recentPosts()
    {
        $sameCategory = Blog::query()
            ->with(['category'])
            ->where('is_published', true)
            ->where('id', '!=', $this->blog->id)
            ->where('category_id', $this->blog->category_id)
            ->latest()
            ->take(4)
            ->get();

        if ($sameCategory->count() >= 4) {
            return $sameCategory;
        }

        $more = Blog::query()
            ->with(['category'])
            ->where('is_published', true)
            ->where('id', '!=', $this->blog->id)
            ->whereNotIn('id', $sameCategory->pluck('id'))
            ->latest()
            ->take(4 - $sameCategory->count())
            ->get();

        return $sameCategory->concat($more)->values();
    }

    public function readingTime(?string $content): string
    {
        $words = str_word_count(strip_tags((string) $content));
        $minutes = max(1, (int) ceil($words / 200));

        return $minutes . ' min read';
    }

    public function schemaJson(): string
    {
        $domain = 'https://www.tobacgo.in';
        $blog = $this->blog;
        $canonicalUrl = $domain . '/blog/' . $blog->slug;

        $metaDescription = $blog->meta_description ?? \Illuminate\Support\Str::limit(strip_tags((string) $blog->content), 155);

        $featuredImageUrl = $blog->featured_image
            ? $domain . '/storage/' . ltrim($blog->featured_image, '/')
            : $domain . '/images/hero.png';

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            '@id' => $canonicalUrl . '#blogposting',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $canonicalUrl,
            ],
            'headline' => $blog->title,
            'description' => $metaDescription,
            'image' => [
                '@type' => 'ImageObject',
                'url' => $featuredImageUrl,
            ],
            'author' => [
                '@type' => 'Organization',
                'name' => $blog->author?->name ?? 'Tobac-Go Team',
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'Tobac-Go',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $domain . '/logo.webp',
                ],
            ],
            'datePublished' => $blog->created_at?->toIso8601String() ?? $blog->created_at?->toIso8601String(),
            'dateModified' => $blog->updated_at?->toIso8601String() ?? $blog->created_at?->toIso8601String(),
            'url' => $canonicalUrl,
            'inLanguage' => 'en',
        ];

        if ($blog->category) {
            $schema['articleSection'] = $blog->category->title;
        }

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
};
