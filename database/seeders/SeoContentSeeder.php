<?php

namespace Database\Seeders;

use App\Models\SeoContent;
use Illuminate\Database\Seeder;

class SeoContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'name' => 'Home Page',
                'meta_title' => 'Tobac-Go | Premium Hookah Store India',
                'meta_description' => 'Buy Hookah Online in India at Tobac-Go and explore premium hookahs at best prices. Find modern designs, smooth performance, and fast delivery across India.',
                'meta_keywords' => 'premium hookah india, buy hookah online india, Tobac-Go, luxury hookah, premium hookah store',
                'page_slug' => '/',
            ],
            [
                'name' => 'Shop / All Products',
                'meta_title' => 'Shop Premium Hookahs & Accessories Online - Tobac-Go',
                'meta_description' => 'Explore our wide collection of premium hookahs, shisha flavors, charcoals, and premium accessories at Tobac-Go. Safe shipping and best prices in India.',
                'meta_keywords' => 'buy hookah online, hookah accessories india, shisha flavors online, premium hookah store',
                'page_slug' => 'shop',
            ],
            [
                'name' => 'Categories Catalog',
                'meta_title' => 'Browse Hookah Categories - Tobac-Go Hookah Store',
                'meta_description' => 'Discover premium hookahs organized by category. From Russian hookahs to acrylic, glass, and brass hookah setups, find the perfect shisha for your sessions.',
                'meta_keywords' => 'hookah types, russian hookah india, glass hookahs, acrylic shisha, brass hookahs',
                'page_slug' => 'categories',
            ],
            [
                'name' => 'Blogs & Guides',
                'meta_title' => 'Hookah Guides, Reviews & Shisha News - Tobac-Go Blog',
                'meta_description' => 'Read the latest shisha guides, expert hookah setup tips, flavor mix recipes, and premium shisha reviews on the official Tobac-Go blog.',
                'meta_keywords' => 'hookah blog, shisha guides, how to setup hookah, hookah flavor mix, hookah reviews',
                'page_slug' => 'blogs',
            ],
            [
                'name' => 'About Us',
                'meta_title' => 'About Tobac-Go - India\'s Ultimate Premium Hookah Store',
                'meta_description' => 'Learn about Tobac-Go, our mission to bring premium hookahs and absolute convenience to hookah enthusiasts across India with unmatched customer service.',
                'meta_keywords' => 'about tobac-go, premium hookah brand, shisha shop history, hookah store india',
                'page_slug' => 'about',
            ],
            [
                'name' => 'Contact Us',
                'meta_title' => 'Contact Us | Tobac-Go Hookah Store Noida',
                'meta_description' => 'Contact Tobac-Go hookah store in Noida. Get in touch with us for premium hookahs, accessories, bongs, flavours, and online support. Call or WhatsApp +91 78384 49604.',
                'meta_keywords' => 'contact tobac-go, hookah shop noida, buy hookah online, contact hookah store, whatsapp support hookah, tobac-go phone number',
                'page_slug' => 'contact',
                'content' => '<h2>Premium Hookah & Accessories Store Noida – Contact Tobac-Go</h2><p>At Tobac-Go, we are committed to providing the ultimate hookah and shisha experience for enthusiasts across Noida and India. Whether you are looking for premium hookahs, organic coconut charcoal, durable clay/silicone chillums, or the latest hookah accessories, our Noida store is fully stocked with 100% genuine products.</p><p>If you have any questions about hookah setups, replacement parts, or delivery across India, feel free to reach out to us. Our store in Sector 76, Noida (located at Amarpali Silicon City) is open daily from 11:00 AM to 11:00 PM for in-person shopping, consultation, and product demos. You can also contact us via phone or WhatsApp at +91 78384 49604 for instant guidance and ordering.</p>',
            ],
            [
                'name' => 'Return & Refund Policy',
                'meta_title' => 'Easy Returns & Refund Policy - Tobac-Go Hookah Store',
                'meta_description' => 'Read our customer-friendly return and refund policy. Learn how to return products or claim refunds for damaged shisha accessories.',
                'meta_keywords' => 'hookah return policy, refund policy, shisha store returns, order cancellation',
                'page_slug' => 'return-refund',
            ],
            [
                'name' => 'Shipping Policy',
                'meta_title' => 'Fast & Secure Hookah Delivery Across India - Shipping Policy',
                'meta_description' => 'Read the Tobac-Go shipping policy. We provide express shipping, secure packaging for fragile glass bases, and real-time tracking.',
                'meta_keywords' => 'hookah shipping india, shisha home delivery, cash on delivery hookah, hookah shipping time',
                'page_slug' => 'shipping-policy',
            ],
            [
                'name' => 'Terms & Conditions',
                'meta_title' => 'Terms of Service & Conditions - Tobac-Go Hookah Store',
                'meta_description' => 'Read the terms and conditions for using the Tobac-Go website and purchasing our premium hookah products.',
                'meta_keywords' => 'terms of service, user agreement, tobac-go terms, online shopping rules',
                'page_slug' => 'terms-conditions',
            ],
            [
                'name' => 'Privacy Policy',
                'meta_title' => 'Your Privacy & Data Security - Privacy Policy | Tobac-Go',
                'meta_description' => 'Read the privacy policy of Tobac-Go. Learn how we collect, use, and protect your personal information when you shop for hookahs with us.',
                'meta_keywords' => 'privacy policy, user data protection, secure payments, cookies policy',
                'page_slug' => 'privacy-policy',
            ],
            [
                'name' => 'Hookah Shop in Noida',
                'meta_title' => 'Premium Hookah Shop in Noida - Best Shisha & Accessories',
                'meta_description' => 'Looking for the best hookah shop in Noida? Buy premium hookahs, shisha flavors, organic coconut charcoal, and shisha accessories with super fast delivery in Noida.',
                'meta_keywords' => 'hookah shop in noida, shisha store noida, buy shisha noida, hookah delivery noida',
                'page_slug' => 'hookah-shop-in-noida',
            ],
            [
                'name' => 'Hookah under 3000',
                'meta_title' => 'Hookah under 3000 - Buy Best Hookahs in Budget',
                'meta_description' => 'Explore the best hookahs under ₹3000 at Tobac-Go. Shop premium and affordable hookahs online in India.',
                'meta_keywords' => 'hookah under 3000, cheap hookah, affordable hookah india, buy hookah online',
                'page_slug' => 'hookah-under-3000',
                'content' => '<p>Find premium quality hookahs that fit your budget.</p>',
            ],
            [
                'name' => 'Hookah under 5000',
                'meta_title' => 'Hookah under 5000 - Premium Hookahs at Best Prices',
                'meta_description' => 'Shop the best premium hookahs under ₹5000 at Tobac-Go. Enjoy top-quality shisha sessions without breaking the bank.',
                'meta_keywords' => 'hookah under 5000, premium hookah india, best hookah under 5k',
                'page_slug' => 'hookah-under-5000',
                'content' => '<p>Upgrade your shisha experience with our top picks under 5K.</p>',
            ],
            [
                'name' => 'Hookah Above ₹7000',
                'meta_title' => 'Luxury Hookah Above ₹7000 - Exclusive Premium Collection',
                'meta_description' => 'Discover luxury and exclusive hookahs above ₹7000 at Tobac-Go. Shop high-end Russian, glass, and brass hookahs.',
                'meta_keywords' => 'luxury hookah, premium hookah above 7000, expensive hookah, high-end shisha',
                'page_slug' => 'hookah-above-7000',
                'content' => '<p>Experience the ultimate luxury with our exclusive high-end hookah collection.</p>',
            ],
        ];

        foreach ($pages as $page) {
            SeoContent::updateOrCreate(
                ['page_slug' => $page['page_slug']],
                [
                    'name' => $page['name'],
                    'meta_title' => $page['meta_title'],
                    'meta_description' => $page['meta_description'],
                    'meta_keywords' => $page['meta_keywords'],
                    'content' => $page['content'] ?? null,
                ]
            );
        }
    }
}
