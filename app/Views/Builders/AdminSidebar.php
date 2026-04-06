<?php

namespace App\View\Builders;

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
                'icon' => 'ti ti-home',
                'url' => route('admin.dashboard'),
                'hasSubmenu' => false,
                'submenu' => [],
            ],
            (object)[
                'title' => 'Categories',
                'icon' => 'ti ti-list-details',
                'url' => route('admin.categories'),
                'hasSubmenu' => false,
                'submenu' => [],
            ],
            (object)[
                'title' => 'Brands',
                'icon' => 'ti ti-tags',
                'url' => route('admin.brands'),
                'hasSubmenu' => false,
                'submenu' => [],
            ],
            (object)[
                'title' => 'Products',
                'icon' => 'ti ti-package',
                'url' => route('admin.products.index'),
                'hasSubmenu' => false,
                'submenu' => [],
            ],
           
        ]);
        return $menu;
    }
}
