<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\GoogleController;

Route::livewire('/', 'pages::home')->name('home');
Route::livewire('/product/{id}', 'pages::product.product-view')->name('product');

Route::livewire('/login', 'auth::login')->middleware('guest')->name('login');
Route::livewire('/register', 'auth::register')->middleware('guest')->name('register');
Route::livewire('/admin/login', 'auth::admin-login')->middleware('guest')->name('admin.login');

// Google OAuth
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');



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

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::livewire('/dashboard', 'admin::dashboard')->name('admin.dashboard');
    Route::livewire('/categories', 'admin::category-list')->name('admin.categories');

    Route::view('/brands', 'welcome')->name('admin.brands');
    Route::livewire('/products', 'admin::product.product-list')->name('admin.products.index');
    Route::livewire('/products/add', 'admin::product.add-product')->name('admin.products.add');
    Route::livewire('/products/edit/{id}', 'admin::product.update-product')->name('admin.products.edit');
});
