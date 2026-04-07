<?php

namespace App\Views\Builders;

use Illuminate\Support\Collection;

class AdminSidebar
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public static function menu($user): self
    {
        return new self($user);
    }

    public function get(): Collection
    {
        $menu = collect([
            (object)[
                'title' => 'Dashboard',
                'icon' => 'ri-home-5-line',
                'url' => route('admin.dashboard'),
                'hasSubmenu' => false,
                'submenu' => [],
            ],
            (object)[
                'title' => 'Categories',
                'icon' => 'ri-folder-3-line',
                'url' => route('admin.categories'),
                'hasSubmenu' => false,
                'submenu' => [],
            ],
            (object)[
                'title' => 'Users',
                'icon' => 'ri-user-line',
                'url' => route('admin.users'),
                'hasSubmenu' => false,
                'submenu' => [],
            ],
                (object)[
                    'title' => 'Testimonials',
                    'icon' => 'ri-chat-3-line',
                    'url' => route('admin.testimonials'),
                    'hasSubmenu' => false,
                    'submenu' => [],
                ],
                (object)[
                    'title' => 'Orders',
                    'icon' => 'ri-shopping-cart-2-line',
                    'url' => '#', // Placeholder, implement orders later
                    'hasSubmenu' => false,
                    'submenu' => [],
                ],
            (object)[
                'title' => 'Products',
                'icon' => 'ri-shopping-bag-3-line',
                'url' => route('admin.products.index'),
                'hasSubmenu' => false,
                'submenu' => [],
            ],
            (object)[
                'title' => 'Coupons',
                'icon' => 'ri-coupon-3-line',
                'url' => route('admin.coupons'),
                'hasSubmenu' => false,
                'submenu' => [],
            ],
            (object)[
                'title' => 'Blog',
                'icon' => 'ri-newspaper-line',
                'url' => '#',
                'hasSubmenu' => true,
                'submenu' => [
                    (object)[
                        'title' => 'All Posts',
                        'url' => route('admin.blogs'), // Placeholder, implement blog posts later
                    ],
                    (object)[
                        'title' => 'Categories',
                        'url' => route('admin.blogs.categories'), // Placeholder, implement blog categories later
                    ],
                ],
            ],
           
        ]);
        return $menu;
    }
}
