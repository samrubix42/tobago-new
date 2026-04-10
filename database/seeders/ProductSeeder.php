<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Seed the application's products.
     */
    public function run(): void
    {
        $categoryIds = Category::query()->pluck('id')->all();

        if (empty($categoryIds)) {
            return;
        }

        $adjectives = ['Royal', 'Classic', 'Premium', 'Smooth', 'Bold', 'Frozen', 'Gold', 'Elite'];
        $flavors = ['Mint', 'Blueberry', 'Grape', 'Peach', 'Mango', 'Cola', 'Watermelon', 'Lemon'];
        $types = ['Hookah Mix', 'Shisha', 'Coal Pack', 'Starter Kit', 'Bowl Set', 'Flavor Pack'];
        $statuses = ['active', 'active', 'active', 'inactive', 'draft'];

        for ($i = 1; $i <= 220; $i++) {
            $name = sprintf(
                '%s %s %s %03d',
                fake()->randomElement($adjectives),
                fake()->randomElement($flavors),
                fake()->randomElement($types),
                $i
            );

            $slug = $this->uniqueProductSlug($name, $i);
            $sku = $this->uniqueSku($name, $i);

            $costPrice = fake()->randomFloat(2, 4, 80);
            $sellingPrice = $costPrice + fake()->randomFloat(2, 2, 35);
            $comparePrice = fake()->boolean(55) ? $sellingPrice + fake()->randomFloat(2, 1, 25) : null;
            $stock = fake()->numberBetween(0, 240);

            Product::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => fake()->randomElement($categoryIds),
                    'name' => $name,
                    'sku' => $sku,
                    'short_description' => fake()->sentence(16),
                    'feature_and_specifications' => $this->generateFeatureSpecHtml($name),
                    'cost_price' => $costPrice,
                    'selling_price' => $sellingPrice,
                    'compare_price' => $comparePrice,
                    'stock' => $stock,
                    'hurry_stock' => fake()->numberBetween(3, 20),
                    'is_out_of_stock' => $stock === 0,
                    'status' => fake()->randomElement($statuses),
                    'is_featured' => fake()->boolean(18),
                    'is_trending' => fake()->boolean(22),
                ]
            );
        }
    }

    private function uniqueProductSlug(string $name, int $index): string
    {
        return Str::slug($name) . '-' . $index;
    }

    private function uniqueSku(string $name, int $index): string
    {
        $base = strtoupper(Str::limit(Str::slug($name, '-'), 18, ''));

        return $base . '-' . str_pad((string) $index, 4, '0', STR_PAD_LEFT);
    }

    private function generateFeatureSpecHtml(string $name): string
    {
        return '<h3>Features</h3>'
            . '<ul>'
            . '<li>Premium-grade material finish for long-term durability.</li>'
            . '<li>Smooth airflow with balanced flavor output.</li>'
            . '<li>Easy to clean and maintain after daily usage.</li>'
            . '</ul>'
            . '<h3>Specification</h3>'
            . '<p><strong>Product:</strong> ' . e($name) . '</p>'
            . '<p><strong>Origin:</strong> Imported</p>'
            . '<p><strong>Category:</strong> Lifestyle Tobacco Accessory</p>';
    }
}
