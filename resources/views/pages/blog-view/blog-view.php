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
};
