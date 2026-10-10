<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/search', SearchController::class)->name('search');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
