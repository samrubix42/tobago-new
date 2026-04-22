<?php

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Component;

new class extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tobacgo';

    #[Url(as: 'cat')]
    public $category = '';

    #[Url(as: 'tag')]
    public $tag = '';

    public function selectCategory($slug)
    {
        $this->category = $slug;
        $this->tag = '';
        $this->resetPage();
    }

    public function selectTag($slug)
    {
        $this->tag = $slug;
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->category = '';
        $this->tag = '';
        $this->resetPage();
    }

    public function getBlogsProperty(): LengthAwarePaginator
    {
        $blogs = Blog::query()
            ->with(['category', 'author'])
            ->where('is_published', true)
            ->when($this->category, function ($query) {
                $query->whereHas('category', function ($q) {
                    $q->where('slug', $this->category);
                });
            })
            ->latest()
            ->get();

        if ($this->tag) {
            $blogs = $blogs->filter(function (Blog $blog): bool {
                return collect($blog->tagItems())
                    ->contains(fn (array $tag) => $tag['slug'] === $this->tag);
            })->values();
        }

        return new LengthAwarePaginator(
            items: $blogs->forPage($this->getPage(), 9)->values(),
            total: $blogs->count(),
            perPage: 9,
            currentPage: $this->getPage(),
            options: [
                'path' => request()->url(),
                'query' => request()->query(),
            ],
        );
    }

    public function getCategoriesProperty(): Collection
    {
        return BlogCategory::where('is_active', true)->get();
    }

    public function getPopularTagsProperty(): Collection
    {
        return Blog::query()
            ->where('is_published', true)
            ->pluck('tags')
            ->flatMap(fn (?string $tags) => Blog::parseTags($tags))
            ->map(fn (string $tag): array => [
                'label' => $tag,
                'slug' => Str::slug($tag),
            ])
            ->unique('slug')
            ->sortBy('label')
            ->values();
    }

    public function readingTime(?string $content): string
    {
        $words = str_word_count(strip_tags((string) $content));
        $minutes = max(1, (int) ceil($words / 200));

        return $minutes . ' min read';
    }

    public function paginationItems(): array
    {
        $current = $this->blogs->currentPage();
        $last = $this->blogs->lastPage();

        if ($last <= 1) {
            return [1];
        }

        $pages = collect([1, $current - 1, $current, $current + 1, $last])
            ->filter(fn (int $page) => $page >= 1 && $page <= $last)
            ->unique()
            ->sort()
            ->values();

        $items = [];
        $previous = null;

        foreach ($pages as $page) {
            if ($previous !== null && $page - $previous > 1) {
                $items[] = 'ellipsis';
            }

            $items[] = $page;
            $previous = $page;
        }

        return $items;
    }
};
