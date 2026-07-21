<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Blog;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Main Sitemap Index.
     */
    public function index(): Response
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        $sitemaps = [
            '/sitemap-pages.xml',
            '/sitemap-products.xml',
            '/sitemap-categories.xml',
            '/sitemap-blog.xml',
        ];

        foreach ($sitemaps as $sitemap) {
            $xml .= '  <sitemap>';
            $xml .= '    <loc>' . url($sitemap) . '</loc>';
            $xml .= '    <lastmod>' . now()->toDateString() . '</lastmod>';
            $xml .= '  </sitemap>';
        }

        $xml .= '</sitemapindex>';

        return response($xml)->header('Content-Type', 'text/xml');
    }

    /**
     * Static Pages Sitemap.
     */
    public function pages(): Response
    {
        $pages = [
            ['loc' => '/', 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => '/shop', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => '/categories', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => '/hookah-shop-in-noida', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => '/hookah-under-3000', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => '/hookah-under-5000', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => '/hookah-above-7000', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => '/blogs', 'changefreq' => 'weekly', 'priority' => '0.7'],
            ['loc' => '/about', 'changefreq' => 'yearly', 'priority' => '0.5'],
            ['loc' => '/contact', 'changefreq' => 'yearly', 'priority' => '0.5'],
            ['loc' => '/privacy-policy', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => '/terms-conditions', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => '/shipping-policy', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => '/return-refund', 'changefreq' => 'yearly', 'priority' => '0.3'],
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($pages as $page) {
            $xml .= '  <url>';
            $xml .= '    <loc>' . url($page['loc']) . '</loc>';
            $xml .= '    <lastmod>' . now()->toDateString() . '</lastmod>';
            $xml .= '    <changefreq>' . $page['changefreq'] . '</changefreq>';
            $xml .= '    <priority>' . $page['priority'] . '</priority>';
            $xml .= '  </url>';
        }

        $xml .= '</urlset>';

        return response($xml)->header('Content-Type', 'text/xml');
    }

    /**
     * Dynamic Products Sitemap.
     */
    public function products(): Response
    {
        $products = Product::where('status', 'active')->orderBy('updated_at', 'desc')->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($products as $product) {
            $lastmod = $product->updated_at ? $product->updated_at->toDateString() : now()->toDateString();
            $xml .= '  <url>';
            $xml .= '    <loc>' . url('/product/' . $product->slug) . '</loc>';
            $xml .= '    <lastmod>' . $lastmod . '</lastmod>';
            $xml .= '    <changefreq>weekly</changefreq>';
            $xml .= '    <priority>0.9</priority>';
            $xml .= '  </url>';
        }

        $xml .= '</urlset>';

        return response($xml)->header('Content-Type', 'text/xml');
    }

    /**
     * Dynamic Categories Sitemap.
     */
    public function categories(): Response
    {
        $categories = Category::where('is_active', true)->with('parent')->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($categories as $category) {
            $path = '/shop/' . $category->slug;
            if ($category->parent) {
                $path = '/shop/' . $category->parent->slug . '/' . $category->slug;
            }

            $lastmod = $category->updated_at ? $category->updated_at->toDateString() : now()->toDateString();

            $xml .= '  <url>';
            $xml .= '    <loc>' . url($path) . '</loc>';
            $xml .= '    <lastmod>' . $lastmod . '</lastmod>';
            $xml .= '    <changefreq>weekly</changefreq>';
            $xml .= '    <priority>0.9</priority>';
            $xml .= '  </url>';
        }

        $xml .= '</urlset>';

        return response($xml)->header('Content-Type', 'text/xml');
    }

    /**
     * Dynamic Blogs Sitemap.
     */
    public function blogs(): Response
    {
        $blogs = Blog::where('is_published', true)->orderBy('updated_at', 'desc')->get();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($blogs as $blog) {
            $lastmod = $blog->updated_at ? $blog->updated_at->toDateString() : now()->toDateString();

            $xml .= '  <url>';
            $xml .= '    <loc>' . url('/blog/' . $blog->slug) . '</loc>';
            $xml .= '    <lastmod>' . $lastmod . '</lastmod>';
            $xml .= '    <changefreq>monthly</changefreq>';
            $xml .= '    <priority>0.7</priority>';
            $xml .= '  </url>';
        }

        $xml .= '</urlset>';

        return response($xml)->header('Content-Type', 'text/xml');
    }
}
