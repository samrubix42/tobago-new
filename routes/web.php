<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::livewire('/', 'pages::home')->name('home');
Route::livewire('/product/{id}', 'pages::product.product-view')->name('product');

Route::livewire('/login', 'auth::login')->middleware('guest')->name('login');
Route::livewire('/register', 'auth::register')->middleware('guest')->name('register');
Route::livewire('/admin/login', 'auth::admin-login')->middleware('guest')->name('admin.login');



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
    Route::view('/products', 'welcome')->name('admin.products.index');
});
