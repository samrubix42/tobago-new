<?php

use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function featuredProducts(): Collection
    { 
        $featured = Product::query()
            ->with(['images'])
            ->where('status', 'active')
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        if ($featured->isNotEmpty()) {
            return $featured;
        }

        return Product::query()
            ->with(['images'])
            ->where('status', 'active')
            ->latest()
            ->take(8)
            ->get();
    }

    #[Computed]
    public function latestProducts(): Collection
    {
        return Product::query()
            ->with(['images'])
            ->where('status', 'active')
            ->latest()
            ->take(4)
            ->get();
    }

    #[Computed]
    public function categories(): Collection
    {
        return Category::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->take(8)
            ->get();
    }

    #[Computed]
    public function latestBlogs(): Collection
    {
        return Blog::query()
            ->with('category')
            ->where('is_published', true)
            ->latest()
            ->take(3)
            ->get();
    }

    #[Computed]
    public function testimonials(): Collection
    {
        return Testimonial::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->take(9)
            ->get();
    }

    #[Computed]
    public function productCount(): int
    {
        return Product::query()->where('status', 'active')->count();
    }

    #[Computed]
    public function categoryCount(): int
    {
        return Category::query()->where('is_active', true)->count();
    }

    #[Computed]
    public function categoryCards(): array
    {
        $cards = $this->categories->map(function (Category $category): array {
            $image = $category->image
                ? asset('storage/' . ltrim($category->image, '/'))
                : asset('images/hero.png');

            return [
                'title' => $category->title,
                'image' => $image,
                'slug' => $category->slug,
            ];
        })->values()->all();

        if (!empty($cards)) {
            return $cards;
        }

        return [
            ['title' => 'Hookahs', 'image' => asset('images/hero.png'), 'slug' => 'hookahs'],
            ['title' => 'Charcoal', 'image' => asset('images/hero.png'), 'slug' => 'charcoal'],
            ['title' => 'Accessories', 'image' => asset('images/hero.png'), 'slug' => 'accessories'],
            ['title' => 'Vapes', 'image' => asset('images/hero.png'), 'slug' => 'vapes'],
        ];
    }

    #[Computed]
    public function testimonialItems(): array
    {
        $fromDb = $this->testimonials->map(function (Testimonial $testimonial): array {
            return [
                'name' => $testimonial->name,
                'city' => $testimonial->city ?: 'Verified Buyer',
                'review' => $testimonial->review,
            ];
        })->values()->all();

        if (!empty($fromDb)) {
            return $fromDb;
        }

        return [
            ['name' => 'Rahul Sharma', 'city' => 'Verified Buyer', 'review' => 'Amazing quality hookah! Smooth airflow and premium design.'],
            ['name' => 'Aman Verma', 'city' => 'Delhi', 'review' => 'Fast delivery and excellent packaging.'],
            ['name' => 'Sahil Khan', 'city' => 'Mumbai', 'review' => 'Best hookah store online. Great variety.'],
            ['name' => 'Rohit Singh', 'city' => 'Patna', 'review' => 'Affordable and stylish products.'],
            ['name' => 'Ali Khan', 'city' => 'Lucknow', 'review' => 'Very smooth experience. Loved it!'],
            ['name' => 'Vikas Kumar', 'city' => 'Kolkata', 'review' => 'Top-notch quality and fast shipping.'],
        ];
    }

    public function price(?string $amount): string
    {
        return number_format((float) $amount, 2);
    }

    public function shortText(?string $text, int $limit = 80): string
    {
        return Str::limit(trim(strip_tags((string) $text)), $limit);
    }
};