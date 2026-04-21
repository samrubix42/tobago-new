<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the application's categories.
     */
    public function run(): void
    {
        $categoryTree = [
            [
                'title' => 'Hookah Category',
                'slug' => 'premium-hookah',
                'children' => [
                    ['title' => 'Tobac Go Hookah', 'slug' => 'tobac-go-hookah'],
                    ['title' => 'VG France Foggit Hookah', 'slug' => 'vg-france-foggit-hookah'],
                    ['title' => 'Thugs Hookah', 'slug' => 'thugs-hookah'],
                    ['title' => 'Mya Hookah', 'slug' => 'mya-hookah'],
                    ['title' => 'Whiskey Bottle Hookah', 'slug' => 'whiskey-bottle-hookah'],
                    ['title' => 'Enrolando Hookah', 'slug' => 'enrolando-hookah'],
                    ['title' => 'DUM Hookahs', 'slug' => 'dum-hookahs'],
                    ['title' => 'Digital Smoke Hookah', 'slug' => 'digital-smoke-hookah'],
                    ['title' => 'Deja Vu Hookah', 'slug' => 'deja-vu-hookah'],
                    ['title' => 'Cocoyaya Hookah', 'slug' => 'cocoyaya-hookah'],
                    ['title' => 'Tobac Go Car Hookah', 'slug' => 'tobac-go-car-hookah'],
                    ['title' => 'Alshan Hookah', 'slug' => 'alshan-hookah'],
                    ['title' => 'Al Akbar Hookah', 'slug' => 'al-akbar-hookah'],
                ],
            ],
            [
                'title' => 'Bong Collection',
                'slug' => 'acrylic-bongs',
                'children' => [],
            ],
            [
                'title' => 'Ashtray Category',
                'slug' => 'ashtray',
                'children' => [],
            ],
            [
                'title' => 'Smoking Accessories',
                'slug' => 'smoking-accessories',
                'children' => [
                    ['title' => 'Hookah Accessories', 'slug' => 'hookah-accessories'],
                    ['title' => 'Hookah Chillum', 'slug' => 'hookah-chillum'],
                    ['title' => 'Pipe and Handle', 'slug' => 'pipe-and-handle'],
                    ['title' => 'Wooden Smoking Pipe', 'slug' => 'wooden-smoking-pipe'],
                    ['title' => 'Smoking Glass Pipe', 'slug' => 'smoking-glass-pipe'],
                    ['title' => 'Metal Shooter', 'slug' => 'metal-shooter'],
                    ['title' => 'Glass Bong Shooter', 'slug' => 'glass-bong-shooter'],
                    ['title' => 'Filter Screens', 'slug' => 'filter-screens'],
                    ['title' => 'Crusher', 'slug' => 'crusher'],
                    ['title' => 'Cleaning Brush', 'slug' => 'cleaning-brush'],
                    ['title' => 'Baba Chillum', 'slug' => 'baba-chillum'],
                ],
            ],
            [
                'title' => 'Lighters Category',
                'slug' => 'lighters',
                'children' => [],
            ],
            [
                'title' => 'Combos Category',
                'slug' => 'combos',
                'children' => [],
            ],
            [
                'title' => 'Hookah Shop in Noida',
                'slug' => 'hookah-shop-in-noida',
                'children' => [],
            ],
        ];

        $order = 1;

        foreach ($categoryTree as $parentCategory) {
            $parent = Category::updateOrCreate(
                ['slug' => $parentCategory['slug']],
                [
                    'parent_id' => null,
                    'title' => $parentCategory['title'],
                    'description' => $this->descriptionFor($parentCategory['title']),
                    'is_active' => true,
                    'order' => $order++,
                    'meta_title' => $parentCategory['title'] . ' | Tobac-Go',
                    'meta_description' => $this->metaDescriptionFor($parentCategory['title']),
                    'meta_keywords' => $this->metaKeywordsFor($parentCategory['title']),
                ]
            );

            $childOrder = 1;
            foreach ($parentCategory['children'] as $childCategory) {
                Category::updateOrCreate(
                    ['slug' => $childCategory['slug']],
                    [
                        'parent_id' => $parent->id,
                        'title' => $childCategory['title'],
                        'description' => $this->descriptionFor($childCategory['title']),
                        'is_active' => true,
                        'order' => $childOrder++,
                        'meta_title' => $childCategory['title'] . ' | Tobac-Go',
                        'meta_description' => $this->metaDescriptionFor($childCategory['title']),
                        'meta_keywords' => $this->metaKeywordsFor($childCategory['title']),
                    ]
                );
            }
        }
    }

    protected function descriptionFor(string $title): string
    {
        return 'Browse ' . $title . ' at Tobac-Go with reliable quality, smooth shopping, and quick delivery.';
    }

    protected function metaDescriptionFor(string $title): string
    {
        return 'Shop ' . $title . ' online at Tobac-Go for premium smoking products and accessories.';
    }

    protected function metaKeywordsFor(string $title): string
    {
        return strtolower($title) . ', tobac-go, smoking accessories, hookah shop';
    }
}
