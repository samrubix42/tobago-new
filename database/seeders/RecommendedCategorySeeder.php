<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\RecommendedCategory;
use Illuminate\Database\Seeder;

class RecommendedCategorySeeder extends Seeder
{
    /**
     * Seed the application's recommended category mappings.
     */
    public function run(): void
    {
        $categoryMap = Category::query()->get()->keyBy('slug');

        $recommendationMap = [
            'tobac-go-hookah' => [
                ['slug' => 'hookah-accessories', 'title' => 'Recommended Hookah Accessories'],
                ['slug' => 'hookah-chillum', 'title' => 'Best Chillums For Your Hookah'],
                ['slug' => 'lighters', 'title' => 'Lighters You May Also Need'],
            ],
            'vg-france-foggit-hookah' => [
                ['slug' => 'hookah-accessories', 'title' => 'Pair It With Accessories'],
                ['slug' => 'pipe-and-handle', 'title' => 'Premium Pipe And Handle Picks'],
            ],
            'acrylic-bongs' => [
                ['slug' => 'crusher', 'title' => 'Recommended Crushers'],
                ['slug' => 'cleaning-brush', 'title' => 'Cleaning Tools For Your Bong'],
                ['slug' => 'ashtray', 'title' => 'Ashtrays To Complete The Setup'],
            ],
            'glass-percolator-bongs' => [
                ['slug' => 'cleaning-brush', 'title' => 'Keep It Clean'],
                ['slug' => 'glass-bong-shooter', 'title' => 'More Glass Picks'],
            ],
            'wooden-smoking-pipe' => [
                ['slug' => 'filter-screens', 'title' => 'Useful Filter Screens'],
                ['slug' => 'crusher', 'title' => 'Customer Favorite Crushers'],
                ['slug' => 'ashtray', 'title' => 'Ashtray Recommendations'],
            ],
            'smoking-glass-pipe' => [
                ['slug' => 'cleaning-brush', 'title' => 'Glass Pipe Care Essentials'],
                ['slug' => 'filter-screens', 'title' => 'Recommended Filter Screens'],
            ],
            'combos' => [
                ['slug' => 'lighters', 'title' => 'Popular Add-Ons'],
                ['slug' => 'hookah-accessories', 'title' => 'Accessories For Combo Buyers'],
            ],
        ];

        foreach ($recommendationMap as $baseSlug => $recommendations) {
            $baseCategory = $categoryMap->get($baseSlug);

            if (! $baseCategory) {
                continue;
            }

            foreach ($recommendations as $recommendation) {
                $recommendedCategory = $categoryMap->get($recommendation['slug']);

                if (! $recommendedCategory) {
                    continue;
                }

                RecommendedCategory::query()->updateOrCreate(
                    [
                        'category_id' => $baseCategory->id,
                        'recommended_category_id' => $recommendedCategory->id,
                    ],
                    [
                        'title' => $recommendation['title'],
                    ]
                );
            }
        }
    }
}
