<?php

use App\Models\Category;

test('category pages routing behavior', function () {
    // Create parent category
    $parent = Category::create([
        'title' => 'Parent Cat',
        'slug' => 'parent-cat',
        'is_active' => true,
        'order' => 1,
    ]);

    // Create subcategory
    $sub = Category::create([
        'title' => 'Sub Cat',
        'slug' => 'sub-cat',
        'parent_id' => $parent->id,
        'is_active' => true,
        'order' => 1,
    ]);

    // 1. Parent category slug on single-tier URL should return 200
    $response = $this->get('/parent-cat');
    $response->assertStatus(200);

    // 2. Subcategory slug on double-tier URL should return 200
    $response = $this->get('/parent-cat/sub-cat');
    $response->assertStatus(200);

    // 3. Subcategory slug on single-tier URL should return 404
    $response = $this->get('/sub-cat');
    $response->assertStatus(404);
});
