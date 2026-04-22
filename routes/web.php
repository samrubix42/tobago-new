<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Payment\PhonePeCallbackController;
use Illuminate\Support\Facades\Artisan;

Route::livewire('/', 'pages::home')->name('home');
Route::livewire('/shop', 'pages::product.product')->name('products');
Route::livewire('/product/{slug}', 'pages::product.product-view')->name('product');
Route::livewire('/cart', 'pages::cart')->name('cart');
Route::livewire('/checkout', 'pages::order.checkout')->name('order.checkout');
Route::livewire('/categories', 'pages::category')->name('category');
Route::livewire('/blogs', 'pages::blog')->name('blogs');
Route::livewire('/blog/{slug}', 'pages::blog-view')->name('blog.view');
Route::livewire('/login', 'auth::login')->middleware('guest')->name('login');
Route::livewire('/register', 'auth::register')->middleware('guest')->name('register');
Route::livewire('/admin/login', 'auth::admin-login')->middleware('guest')->name('admin.login');

// Google OAuth
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::get('/payment/phonepe/return/{orderNumber}', [PhonePeCallbackController::class, 'handleReturn'])
    ->name('payment.phonepe.return');
Route::post('/payment/phonepe/callback', [PhonePeCallbackController::class, 'handleCallback'])
    ->name('payment.phonepe.callback');

// User account
Route::middleware('auth')->group(function () {
    Route::livewire('/account/profile', 'pages::user.profile')->name('user.profile');
    Route::livewire('/account/addresses', 'pages::user.address')->name('user.address');
    Route::livewire('/account/orders', 'pages::order.my-order')->name('user.orders');
    Route::livewire('/account/orders/{orderId}', 'pages::order.order-info')->name('user.orders.info');
});



Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout');

Route::post('/admin/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('admin.login');
})->middleware('auth')->name('admin.logout');

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::livewire('/dashboard', 'admin::dashboard')->name('dashboard');
    Route::livewire('/categories', 'admin::category-list')->name('categories');


    //product
    Route::livewire('/products', 'admin::product.product-list')->name('products.index');
    Route::livewire('/products/add', 'admin::product.add-product')->name('products.add');
    Route::livewire('/products/edit/{id}', 'admin::product.update-product')->name('products.edit');

    //inventory
    Route::livewire('/inventory', 'admin::inventory')->name('inventory');

    //orders
    Route::livewire('/orders', 'admin.order.order-list')->name('orders');
    Route::livewire('/orders/{id}', 'admin.order.order-management')->name('orders.manage');

    //testimonial

    Route::livewire('/testimonials','admin::testimonial-list')->name('testimonials');
    Route::livewire('/users','admin::user.user-list')->name('users');

    //blog
    Route::livewire('/blogs', 'admin::blog.blog-list')->name('blogs');
    Route::livewire('/blogs/add', 'admin::blog.add-blog')->name('blogs.add');
    Route::livewire('/blogs/edit/{id}', 'admin::blog.update-blog')->name('blogs.edit');
    Route::livewire('/blogs/categories', 'admin::blog.blog-category-list')->name('blogs.categories');

    //coupons
    Route::livewire('/coupons', 'admin::coupon-list')->name('coupons');

    //settings
    Route::livewire('/settings', 'admin::setting.setting-list')->name('settings');
});

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return "Cache cleared!";
})->name('clear.cache');

Route::livewire('shop/{category}', 'pages::product.product')
    ->where('category', '^(?!admin$|login$|register$|cart$|checkout$|product$|products$|clear-cache$|demo$|account$|auth$)[a-z0-9-]+$')
    ->name('products.category');

Route::livewire('shop/{category}/{subcategory}', 'pages::product.product')
    ->where('category', '^(?!admin$|login$|register$|cart$|checkout$|product$|products$|clear-cache$|demo$|account$|auth$)[a-z0-9-]+$')
    ->where('subcategory', '^[a-z0-9-]+$')
    ->name('products.category.subcategory');
