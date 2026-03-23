<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'index']);
Route::get('/create', [ProductController::class, 'createPage']);

Route::get('/add-review/{id}', [ProductController::class, 'addReviewPage']);
Route::get('/view-review/{id}', [ProductController::class, 'viewReviewPage']);

Route::post('/product', [ProductController::class, 'createProduct']);
Route::post('/review-ajax', [ProductController::class, 'addReviewAjax']);
Route::get('/reviews', [ProductController::class, 'getReviews']);
Route::get('/average', [ProductController::class, 'averageRating']);