<?php

use App\Models\Product;
use App\Models\ProductRecommendation;

test('product detail page shows custom recommendations', function () {
    // Create base product
    $baseProduct = Product::create([
        'name' => 'Base Product',
        'slug' => 'base-product',
        'status' => 'active',
    ]);

    // Create recommended product
    $recommendedProduct = Product::create([
        'name' => 'Recommended Product 1',
        'slug' => 'recommended-product-1',
        'status' => 'active',
    ]);

    // Create inactive recommendation
    $inactiveRecommendedProduct = Product::create([
        'name' => 'Recommended Product 2',
        'slug' => 'recommended-product-2',
        'status' => 'inactive',
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
});
