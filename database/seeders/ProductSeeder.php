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
        $catalog = $this->catalog();
        $categoryMap = Category::query()
            ->whereIn('slug', array_keys($catalog))
            ->get()
            ->keyBy('slug');

        if ($categoryMap->isEmpty()) {
            return;
        }

        $statuses = ['active', 'active', 'active', 'inactive', 'draft'];
        $productIndex = 1;

        foreach ($catalog as $categorySlug => $products) {
            $category = $categoryMap->get($categorySlug);

            if (! $category) {
                continue;
            }

            foreach ($products as $name) {
                $slug = $this->uniqueProductSlug($name, $productIndex);
                $sku = $this->uniqueSku($name, $productIndex);

                $sellingPrice = fake()->randomFloat(2, 3000, 35000);
                $costPrice = round($sellingPrice * fake()->randomFloat(2, 0.62, 0.84), 2);
                $comparePrice = fake()->boolean(75)
                    ? round($sellingPrice + fake()->randomFloat(2, 600, 5000), 2)
                    : null;
                $stock = fake()->numberBetween(0, 240);

                Product::query()->updateOrCreate(
                    ['slug' => $slug],
                    [
                        'category_id' => $category->id,
                        'name' => $name,
                        'sku' => $sku,
                        'short_description' => $this->shortDescriptionFor($name, $category->title),
                        'feature_and_specifications' => $this->generateFeatureSpecHtml($name, $category->title),
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

                $productIndex++;
            }
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

    private function generateFeatureSpecHtml(string $name, string $categoryTitle): string
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
            . '<p><strong>Category:</strong> ' . e($categoryTitle) . '</p>';
    }

    private function shortDescriptionFor(string $name, string $categoryTitle): string
    {
        return $name . ' in our ' . $categoryTitle . ' range, selected for quality, finish, and daily use.';
    }

    private function catalog(): array
    {
        $baseCatalog = [
            'tobac-go-hookah' => [
                'Tobac Go Hookah Classic Silver',
                'Tobac Go Hookah Matte Black',
                'Tobac Go Hookah Premium Gold',
                'Tobac Go Hookah Travel Edition',
            ],
            'vg-france-foggit-hookah' => [
                'VG France Foggit Hookah Rose Gold',
                'VG France Foggit Hookah Carbon Finish',
                'VG France Foggit Hookah Crystal Stem',
            ],
            'thugs-hookah' => [
                'Thugs Hookah Street Edition',
                'Thugs Hookah Chrome Series',
                'Thugs Hookah Mini Beast',
            ],
            'mya-hookah' => [
                'Mya Hookah Compact Edition',
                'Mya Hookah Glass Base Set',
                'Mya Hookah Lounge Series',
            ],
            'whiskey-bottle-hookah' => [
                'Whiskey Bottle Hookah Oak Barrel',
                'Whiskey Bottle Hookah Premium Decanter',
                'Whiskey Bottle Hookah Signature Glass',
            ],
            'enrolando-hookah' => [
                'Enrolando Hookah Urban Black',
                'Enrolando Hookah Frosted Base',
                'Enrolando Hookah Gold Stem',
            ],
            'dum-hookahs' => [
                'DUM Hookah Heavy Stem',
                'DUM Hookah Party Edition',
                'DUM Hookah Designer Cut',
            ],
            'digital-smoke-hookah' => [
                'Digital Smoke Hookah Neon Series',
                'Digital Smoke Hookah LED Base',
                'Digital Smoke Hookah Smart Pull',
            ],
            'deja-vu-hookah' => [
                'Deja Vu Hookah Velvet Black',
                'Deja Vu Hookah Mirror Silver',
                'Deja Vu Hookah Limited Edition',
            ],
            'cocoyaya-hookah' => [
                'Cocoyaya Hookah Aero X1',
                'Cocoyaya Hookah Thunder Stem',
                'Cocoyaya Hookah Glass Craft',
                'Cocoyaya Hookah Premium Combo',
            ],
            'tobac-go-car-hookah' => [
                'Tobac Go Car Hookah Compact Drive',
                'Tobac Go Car Hookah Dashboard Edition',
                'Tobac Go Car Hookah Travel Kit',
            ],
            'alshan-hookah' => [
                'Alshan Hookah Traditional Brass',
                'Alshan Hookah Luxe Finish',
                'Alshan Hookah Premium Lounge',
            ],
            'al-akbar-hookah' => [
                'Al Akbar Hookah Heritage Series',
                'Al Akbar Hookah Royal Stem',
                'Al Akbar Hookah Premium Base',
            ],
            'acrylic-bongs' => [
                'Acrylic Bong Ice Catcher Blue',
                'Acrylic Bong Tall Chamber Green',
                'Acrylic Bong Compact Travel Red',
                'Acrylic Bong Wide Base Black',
            ],
            'ashtray' => [
                'Metal Ashtray Windproof Round',
                'Glass Ashtray Heavy Base',
                'Portable Pocket Ashtray',
                'Ceramic Ashtray Matte Finish',
            ],
            'hookah-accessories' => [
                'Hookah Accessory Starter Set',
                'Hookah Accessory Premium Care Kit',
                'Hookah Accessory Replacement Bundle',
            ],
            'hookah-chillum' => [
                'Hookah Chillum Clay Bowl',
                'Hookah Chillum Silicone Bowl',
                'Hookah Chillum Heat Core Bowl',
            ],
            'pipe-and-handle' => [
                'Pipe and Handle Wooden Grip Set',
                'Pipe and Handle Brass Finish',
                'Pipe and Handle Long Reach Kit',
            ],
            'wooden-smoking-pipe' => [
                'Wooden Smoking Pipe Classic Walnut',
                'Wooden Smoking Pipe Curved Stem',
                'Wooden Smoking Pipe Handcrafted Finish',
            ],
            'smoking-glass-pipe' => [
                'Smoking Glass Pipe Clear Tube',
                'Smoking Glass Pipe Color Swirl',
                'Smoking Glass Pipe Thick Glass Mini',
            ],
            'metal-shooter' => [
                'Metal Shooter Pocket Edition',
                'Metal Shooter Silver Finish',
                'Metal Shooter Durable Grip',
            ],
            'glass-bong-shooter' => [
                'Glass Bong Shooter Clear Shot',
                'Glass Bong Shooter Ice Pinch',
                'Glass Bong Shooter Thick Wall',
            ],
            'filter-screens' => [
                'Filter Screens Fine Mesh Pack',
                'Filter Screens Stainless Steel Set',
                'Filter Screens Brass Round Pack',
            ],
            'crusher' => [
                'Crusher 4 Part Alloy Grinder',
                'Crusher Magnetic Top Grinder',
                'Crusher Compact Pocket Mill',
            ],
            'cleaning-brush' => [
                'Cleaning Brush Multi Size Set',
                'Cleaning Brush Long Stem Pack',
                'Cleaning Brush Soft Tip Cleaner',
            ],
            'baba-chillum' => [
                'Baba Chillum Classic Stone Finish',
                'Baba Chillum Handcrafted Edition',
                'Baba Chillum Compact Smoke Piece',
            ],
            'lighters' => [
                'Jet Flame Lighter Matte Black',
                'Windproof Lighter Metal Body',
                'Refillable Torch Lighter',
                'Pocket Lighter Triple Flame',
            ],
            'combos' => [
                'Hookah Combo Party Starter Kit',
                'Smoking Combo Gift Box',
                'Lounge Combo Premium Pack',
                'Travel Combo Compact Set',
            ],
            'hookah-shop-in-noida' => [
                'Noida Hookah Special Premium Kit',
                'Noida Hookah Lounge Starter Pack',
                'Noida Hookah Accessories Bundle',
            ],
        ];

        return $this->ensureMinimumProducts($baseCatalog, 20);
    }

    private function ensureMinimumProducts(array $catalog, int $minimum): array
    {
        $editionWords = [
            'Prime',
            'Elite',
            'Signature',
            'Pro',
            'Ultra',
            'Classic',
            'Select',
            'Max',
            'Nova',
            'X',
        ];

        foreach ($catalog as $slug => $products) {
            $products = array_values(array_unique($products));
            $seedName = $products[0] ?? Str::headline(str_replace('-', ' ', $slug));
            $counter = 1;

            while (count($products) < $minimum) {
                $edition = $editionWords[($counter - 1) % count($editionWords)];
                $candidate = "{$seedName} {$edition} " . str_pad((string) $counter, 2, '0', STR_PAD_LEFT);

                if (! in_array($candidate, $products, true)) {
                    $products[] = $candidate;
                }

                $counter++;
            }

            $catalog[$slug] = $products;
        }

        return $catalog;
    }
}
