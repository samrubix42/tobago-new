<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('is_admin', true)->first();
        if (! $admin) {
            $admin = User::first();
        }

        if (! $admin) {
            return;
        }

        $categories = [
            ['title' => 'Hookah Guides', 'slug' => 'hookah-guides'],
            ['title' => 'Product Reviews', 'slug' => 'product-reviews'],
            ['title' => 'Maintenance Tips', 'slug' => 'maintenance-tips'],
            ['title' => 'Lifestyle', 'slug' => 'lifestyle'],
            ['title' => 'Buying Tips', 'slug' => 'buying-tips'],
            ['title' => 'Accessories', 'slug' => 'accessories'],
        ];

        $titleMap = [
            'hookah-guides' => [
                'How to Set Up Your Hookah for a Smoother First Session',
                'Beginner Hookah Mistakes That Ruin Flavor Fast',
                'How Much Water Should You Put in a Hookah Base',
                'Best Heat Management Habits for Long Sessions',
                'Simple Ways to Improve Smoke Density at Home',
                'A Quick Guide to Packing Bowls for Daily Use',
                'How to Choose the Right Hookah Height for Your Space',
                'What Actually Changes Between Traditional and Modern Hookahs',
            ],
            'product-reviews' => [
                'Premium Hookah Features That Are Worth Paying For',
                'What Makes a Hookah Feel Expensive in Real Use',
                'Our Favorite Daily-Use Hookah Setups for Small Rooms',
                'Which Type of Stem Feels Best for Smooth Airflow',
                'A Clean Look at the Most Practical Hookah Accessories',
                'Should You Buy a Full Hookah Kit or Build Your Own',
                'Glass vs Metal Hookah Parts for Everyday Sessions',
                'What to Look for Before Buying Your Next Premium Setup',
            ],
            'maintenance-tips' => [
                'How to Clean Your Hookah Base Without Damaging It',
                'The Fastest Way to Remove Ghost Flavor from a Hose',
                'How Often You Should Deep Clean a Hookah',
                'Easy Maintenance Habits That Keep Every Session Fresh',
                'Why Your Hookah Tastes Burnt Even After Cleaning',
                'Storage Tips That Keep Hookah Parts Looking New',
                'How to Dry and Store Accessories the Right Way',
                'A Weekly Cleaning Routine for Heavy Users',
            ],
            'lifestyle' => [
                'How to Build a Better Hookah Corner at Home',
                'Hosting a Relaxed Hookah Night Without Overdoing It',
                'The Details That Make a Session Feel Premium',
                'How Lighting and Music Change the Session Mood',
                'Small Table Setups That Look Clean and Feel Practical',
                'How to Keep Your Hookah Setup Guest Ready',
                'Evening Session Ideas for a More Relaxed Routine',
                'How to Make Your Lounge Setup Feel More Intentional',
            ],
            'buying-tips' => [
                'How to Choose Your First Premium Hookah Without Regret',
                'Buying a Hookah Online What Matters Most',
                'A Smart Budget Split for Hookah Setup and Accessories',
                'What to Check Before Ordering a New Bowl or Hose',
                'How to Compare Hookah Designs Beyond Looks',
                'Questions Worth Asking Before You Upgrade',
                'How to Spot Better Build Quality in Product Photos',
                'What Size Hookah Is Actually Right for You',
            ],
            'accessories' => [
                'The Accessories That Make a Bigger Difference Than You Expect',
                'How to Pick a Bowl That Matches Your Session Style',
                'Which Hookah Hose Style Is Easiest to Live With',
                'Must-Have Accessories for Cleaner Daily Use',
                'Simple Add-Ons That Upgrade Comfort Immediately',
                'How Tongs Trays and Heat Tools Change the Experience',
                'The Best Accessory Combos for Beginners',
                'How to Avoid Buying Accessories You Will Never Use',
            ],
        ];

        $tagMap = [
            'hookah-guides' => ['hookah setup', 'smooth smoke', 'beginner', 'session tips', 'packing'],
            'product-reviews' => ['premium hookah', 'review', 'product guide', 'buying', 'daily use'],
            'maintenance-tips' => ['cleaning', 'maintenance', 'care guide', 'fresh flavor', 'storage'],
            'lifestyle' => ['home lounge', 'session mood', 'hosting', 'setup ideas', 'premium feel'],
            'buying-tips' => ['buying tips', 'budget', 'comparison', 'online shopping', 'upgrade'],
            'accessories' => ['accessories', 'bowl', 'hose', 'heat management', 'must have'],
        ];

        foreach ($categories as $index => $cat) {
            $category = BlogCategory::updateOrCreate(['slug' => $cat['slug']], [
                'title' => $cat['title'],
                'is_active' => true,
            ]);

            foreach ($titleMap[$cat['slug']] as $postIndex => $title) {
                $slug = Str::slug($title);
                $paragraphs = [
                    fake()->paragraph(4),
                    fake()->paragraph(5),
                    fake()->paragraph(4),
                    fake()->paragraph(5),
                ];
                $tags = collect($tagMap[$cat['slug']])
                    ->shuffle()
                    ->take(3)
                    ->push($cat['title'])
                    ->unique()
                    ->implode(', ');

                Blog::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'title' => $title,
                        'content' => $this->buildContent($title, $paragraphs),
                        'author_id' => $admin->id,
                        'category_id' => $category->id,
                        'is_published' => true,
                        'tags' => $tags,
                        'featured_image' => null,
                        'created_at' => now()->subDays(($index * 8) + $postIndex),
                        'updated_at' => now()->subDays(($index * 8) + $postIndex),
                    ]
                );
            }
        }
    }

    protected function buildContent(string $title, array $paragraphs): string
    {
        $points = collect([
            'Keep airflow, heat balance, and cleaning routine consistent for better repeat sessions.',
            'Choose parts that are easy to maintain if you plan to use the setup multiple times a week.',
            'A more premium session usually comes from balance and comfort, not just expensive hardware.',
            'Small upgrades often create the biggest quality jump when the core setup is already solid.',
        ])->shuffle()->take(3);

        $html = '<h2>' . e($title) . '</h2>';
        $html .= '<p>' . e($paragraphs[0]) . '</p>';
        $html .= '<p>' . e($paragraphs[1]) . '</p>';
        $html .= '<blockquote>Consistency matters more than complexity when you want a clean and reliable hookah session.</blockquote>';
        $html .= '<h3>What to focus on</h3>';
        $html .= '<ul>';

        foreach ($points as $point) {
            $html .= '<li>' . e($point) . '</li>';
        }

        $html .= '</ul>';
        $html .= '<p>' . e($paragraphs[2]) . '</p>';
        $html .= '<p>' . e($paragraphs[3]) . '</p>';

        return $html;
    }
}
