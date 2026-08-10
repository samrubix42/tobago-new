<?php

use App\Models\Product;
use App\Models\ProductRecommendation;

test('product detail page shows custom recommendations', function () {
    // Create base product
    $baseProduct = Product::create([
        'name' => 'Base Product',
        'slug' => 'base-product',
        'status' => 'active',
        'stock' => 10,
    ]);

    // Create recommended product
    $recommendedProduct = Product::create([
        'name' => 'Recommended Product 1',
        'slug' => 'recommended-product-1',
        'status' => 'active',
        'stock' => 10,
    ]);

    // Create inactive recommendation
    $inactiveRecommendedProduct = Product::create([
        'name' => 'Recommended Product 2',
        'slug' => 'recommended-product-2',
        'status' => 'inactive',
        'stock' => 10,
    ]);

    // Save recommendations
    ProductRecommendation::create([
        'product_id' => $baseProduct->id,
        'recommended_product_id' => $recommendedProduct->id,
    ]);

    ProductRecommendation::create([
        'product_id' => $baseProduct->id,
        'recommended_product_id' => $inactiveRecommendedProduct->id,
    ]);

    // Assert database contents
    $this->assertDatabaseHas('product_recommendations', [
        'product_id' => $baseProduct->id,
        'recommended_product_id' => $recommendedProduct->id,
    ]);

    $this->assertDatabaseHas('product_recommendations', [
        'product_id' => $baseProduct->id,
        'recommended_product_id' => $inactiveRecommendedProduct->id,
    ]);

    // Navigate to product-view page
    $response = $this->get('/product/base-product');
    $response->assertStatus(200);

    // Verify recommendations
    $response->assertSee('Recommended Product 1');
    $response->assertDontSee('Recommended Product 2');

    // Verify MerchantReturnPolicy schema elements
    $response->assertSee('hasMerchantReturnPolicy', false);
    $response->assertSee('"merchantReturnDays": 2', false);
    $response->assertSee('https://schema.org/ReturnByMail', false);
    $response->assertSee('https://schema.org/FreeReturn', false);
});

test('price SEO pages show random tobac-go recommendations', function () {
    // Create Tobac-Go category
    $category = \App\Models\Category::create([
        'title' => 'Tobac Go Hookah',
        'slug' => 'tobac-go-hookah',
        'is_active' => true,
        'order' => 1,
    ]);

    // Create an active product in that category
    $tobacGoProduct = Product::create([
        'category_id' => $category->id,
        'name' => 'Exclusive TobacGo Hookah Pro',
        'slug' => 'exclusive-tobacgo-hookah-pro',
        'status' => 'active',
        'stock' => 5,
        'selling_price' => 2500.00,
    ]);

    // Navigate to a price page
    $response = $this->get('/hookah-under-3000');
    $response->assertStatus(200);

    // Verify recommendations
    $response->assertSee('Tobac-Go Exclusive Hookah');
    $response->assertSee('Exclusive TobacGo Hookah Pro');
});

test('product detail page does not show related products section when recommendations are empty', function () {
    // Create base product
    $baseProduct = Product::create([
        'name' => 'Lonely Hookah',
        'slug' => 'lonely-hookah',
        'status' => 'active',
        'stock' => 10,
    ]);

    // Navigate to product-view page
    $response = $this->get('/product/lonely-hookah');
    $response->assertStatus(200);

    // Verify related products section is NOT shown
    $response->assertDontSee('Related Products');
});

test('product detail page shows out of stock recommendations when no in-stock ones are available', function () {
    // Create base product
    $baseProduct = Product::create([
        'name' => 'Base Product OutOfStock Test',
        'slug' => 'base-product-outofstock-test',
        'status' => 'active',
        'stock' => 10,
    ]);

    // Create custom out of stock recommendation
    $outOfStockProduct = Product::create([
        'name' => 'Out of Stock Recommend',
        'slug' => 'out-of-stock-recommend',
        'status' => 'active',
        'is_out_of_stock' => true,
        'stock' => 0,
    ]);

    ProductRecommendation::create([
        'product_id' => $baseProduct->id,
        'recommended_product_id' => $outOfStockProduct->id,
    ]);

    // Navigate to product-view page
    $response = $this->get('/product/base-product-outofstock-test');
    $response->assertStatus(200);

    // Verify related products section is shown and contains the out of stock product
    $response->assertSee('Related Products');
    $response->assertSee('Out of Stock Recommend');
});
