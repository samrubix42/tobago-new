<?php

use App\Models\Product;
use App\Models\Category;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('sitemap index returns valid xml', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/xml; charset=utf-8');
    $response->assertSee('<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', false);
    $response->assertSee('/sitemap-pages.xml');
    $response->assertSee('/sitemap-products.xml');
    $response->assertSee('/sitemap-categories.xml');
    $response->assertSee('/sitemap-blog.xml');
});

test('sitemap pages returns static pages xml', function () {
    $response = $this->get('/sitemap-pages.xml');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/xml; charset=utf-8');
    $response->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', false);
    $response->assertSee('/about');
    $response->assertSee('/privacy-policy');
    $response->assertSee('/return-refund');
});

test('sitemap products returns active products xml', function () {
    Product::create([
        'name' => 'Test Hookah',
        'slug' => 'test-hookah',
        'sku' => 'HOOKAH-001',
        'cost_price' => 1000,
        'selling_price' => 1500,
        'stock' => 10,
        'status' => 'active',
    ]);

    $response = $this->get('/sitemap-products.xml');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/xml; charset=utf-8');
    $response->assertSee('/product/test-hookah');
});

test('sitemap categories returns active categories xml', function () {
    $parent = Category::create([
        'title' => 'Hookah Category',
        'slug' => 'hookah-category',
        'is_active' => true,
        'order' => 1,
    ]);

    $sub = Category::create([
        'title' => 'Acrylic Hookah',
        'slug' => 'acrylic-hookah',
        'parent_id' => $parent->id,
        'is_active' => true,
        'order' => 1,
    ]);

    $response = $this->get('/sitemap-categories.xml');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/xml; charset=utf-8');
    $response->assertSee('/shop/hookah-category');
    $response->assertSee('/shop/hookah-category/acrylic-hookah');
});

test('sitemap blog returns published blogs xml', function () {
    $user = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => 'secret123',
    ]);

    $blogCategory = BlogCategory::create([
        'title' => 'Hookah Guides',
        'slug' => 'hookah-guides',
        'is_active' => true,
    ]);

    Blog::create([
        'author_id' => $user->id,
        'category_id' => $blogCategory->id,
        'title' => 'Traditional vs Modern Hookah',
        'slug' => 'traditional-vs-modern-hookah',
        'content' => 'Sample content',
        'is_published' => true,
    ]);

    $response = $this->get('/sitemap-blog.xml');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/xml; charset=utf-8');
    $response->assertSee('/blog/traditional-vs-modern-hookah');

    $responseAlias = $this->get('/sitemap-blogs.xml');
    $responseAlias->assertStatus(200);
    $responseAlias->assertSee('/blog/traditional-vs-modern-hookah');
});
