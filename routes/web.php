<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home')->name('home');
Route::livewire('/product/{id}', 'pages::product.product-view')->name('product');
