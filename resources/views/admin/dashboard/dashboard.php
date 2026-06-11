<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Blog;
use App\Models\Coupon;
use App\Models\Testimonial;

new #[Layout('layouts::admin')] class extends Component
{
    #[Computed]
    public function stats()
    {
        return [
            'revenue' => Order::where('payment_status', 'paid')->sum('total'),
            'orders_count' => Order::count(),
            'products_count' => Product::count(),
            'customers_count' => User::where('is_admin', false)->count(),
            'categories_count' => Category::count(),
            'blogs_count' => Blog::count(),
            'coupons_count' => Coupon::count(),
            'testimonials_count' => Testimonial::count(),
        ];
    }

    #[Computed]
    public function orderStatusStats()
    {
        return [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::whereIn('status', ['processing', 'confirmed'])->count(),
            'shipped' => Order::whereIn('status', ['shipped', 'delivered'])->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];
    }

    #[Computed]
    public function recentOrders()
    {
        return Order::latest()->take(5)->get();
    }

    #[Computed]
    public function lowStockProducts()
    {
        return Product::where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();
    }

    #[Computed]
    public function recentBlogs()
    {
        return Blog::with('category')->latest()->take(4)->get();
    }
};
