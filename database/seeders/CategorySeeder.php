<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Seed the application's categories.
     */
    public function run(): void
    {
        $categoryTree = [
            'Hookah' => ['Hookah Pipes', 'Hookah Bowls', 'Hookah Hoses'],
            'Shisha Tobacco' => ['Mint Blends', 'Fruit Blends', 'Dessert Blends'],
            'Charcoal' => ['Coconut Charcoal', 'Quick Light Charcoal'],
            'Accessories' => ['Tongs', 'Heat Management', 'Cleaning Tools'],
            'Vapes' => ['Disposable Vapes', 'Pod Systems'],
        ];

        $order = 1;

        foreach ($categoryTree as $parentTitle => $children) {
            $parentSlug = Str::slug($parentTitle);
            $parent = Category::updateOrCreate(
                ['slug' => $parentSlug],
                [
                    'parent_id' => null,
                    'title' => $parentTitle,
                    'description' => fake()->sentence(14),
                    'is_active' => true,
                    'order' => $order++,
                    'meta_title' => $parentTitle . ' | Tobac-Go',
                    'meta_description' => fake()->sentence(18),
                    'meta_keywords' => strtolower($parentTitle) . ', tobacco, hookah',
                ]
            );

            $childOrder = 1;
            foreach ($children as $childTitle) {
                Category::updateOrCreate(
                    ['slug' => Str::slug($parentTitle . ' ' . $childTitle)],
                    [
                        'parent_id' => $parent->id,
                        'title' => $childTitle,
                        'description' => fake()->sentence(12),
                        'is_active' => true,
                        'order' => $childOrder++,
                        'meta_title' => $childTitle . ' | Tobac-Go',
                        'meta_description' => fake()->sentence(16),
                        'meta_keywords' => strtolower($childTitle) . ', shop, tobacco',
                    ]
                );
            }
        }
    }
}
