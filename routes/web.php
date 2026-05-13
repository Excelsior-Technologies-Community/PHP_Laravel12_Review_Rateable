<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

// Product routes
Route::get('/reviews', [ProductController::class, 'index'])->name('products.index');
Route::get('/create-product', [ProductController::class, 'createPage'])->name('products.create');
Route::get('/create', [ProductController::class, 'createProductPage'])->name('products.create.alt');
Route::post('/product', [ProductController::class, 'createProduct']);
Route::get('/add-review/{id}', [ProductController::class, 'addReviewPage']);
Route::get('/view-review/{id}', [ProductController::class, 'viewReviewPage']);
Route::post('/review-ajax', [ProductController::class, 'addReviewAjax']);
Route::get('/reviews-data', [ProductController::class, 'getReviews']);
Route::get('/average', [ProductController::class, 'averageRating']);
Route::get('/products-data', [ProductController::class, 'getProductsData']);