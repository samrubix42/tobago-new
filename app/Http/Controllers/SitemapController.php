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
            '/sitemap-blogs.xml',
        ];

        foreach ($sitemaps as $sitemap) {
            $xml .= '  <sitemap>';
            $xml .= '    <loc>' . url($sitemap) . '</loc>';
            $xml .= '    <lastmod>' . now()->toAtomString() . '</lastmod>';
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
        $urls = [
            '/',
            '/shop',
            '/categories',
            '/about',
            '/blogs',
            '/terms-conditions',
            '/privacy-policy',
            '/shipping-policy',
            '/return-refund',
            '/hookah-shop-in-noida',
            '/hookah-under-3000',
            '/hookah-under-5000',
            '/hookah-above-7000',
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $url) {
            $xml .= '  <url>';
            $xml .= '    <loc>' . url($url) . '</loc>';
            $xml .= '    <lastmod>' . now()->toDateString() . '</lastmod>';
            $xml .= '    <changefreq>' . ($url === '/' || $url === '/shop' ? 'daily' : 'weekly') . '</changefreq>';
            $xml .= '    <priority>' . ($url === '/' ? '1.0' : ($url === '/shop' ? '0.8' : '0.5')) . '</priority>';
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
            $xml .= '  <url>';
            $xml .= '    <loc>' . url('/product/' . $product->slug) . '</loc>';
            $xml .= '    <lastmod>' . ($product->updated_at ? $product->updated_at->toDateString() : now()->toDateString()) . '</lastmod>';
            $xml .= '    <changefreq>daily</changefreq>';
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

            $xml .= '  <url>';
            $xml .= '    <loc>' . url($path) . '</loc>';
            $xml .= '    <lastmod>' . ($category->updated_at ? $category->updated_at->toDateString() : now()->toDateString()) . '</lastmod>';
            $xml .= '    <changefreq>weekly</changefreq>';
            $xml .= '    <priority>0.8</priority>';
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
            $xml .= '  <url>';
            $xml .= '    <loc>' . url('/blog/' . $blog->slug) . '</loc>';
            $xml .= '    <lastmod>' . ($blog->updated_at ? $blog->updated_at->toDateString() : now()->toDateString()) . '</lastmod>';
            $xml .= '    <changefreq>weekly</changefreq>';
            $xml .= '    <priority>0.6</priority>';
            $xml .= '  </url>';
        }

        $xml .= '</urlset>';

        return response($xml)->header('Content-Type', 'text/xml');
    }
}
