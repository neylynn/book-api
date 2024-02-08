<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\WishlistController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UserController::class)->group(function(){
    Route::post('/users/register', 'register');
    Route::post('/users/login', 'login');
});

Route::middleware(['auth:sanctum'])->group( function () {

    Route::middleware(['user'])->group(function () {
        Route::get('/books', [BookController::class, 'index']);
        Route::get('/books/{bookId}', [BookController::class, 'show']);
        Route::get('/books/search/{name}', [BookController::class, 'search']);

        Route::post('/cart/add', [CartController::class, 'addToCart']);
        Route::get('/cart/{userId}', [CartController::class, 'getCart']);
        Route::put('/cart/update', [CartController::class, 'updateCart']);
        Route::delete('/cart/remove', [CartController::class, 'removeFromCart']);

        Route::post('/orders/checkout', [OrderController::class, 'checkout']);

        Route::get('/users/{userId}', [UserController::class, 'getUserProfile']);

        Route::get('/wishlist/{userId}', [WishlistController::class, 'getWishlist']);
        Route::post('/wishlist/{userId}', [WishlistController::class, 'addToWishlist']);
        Route::delete('/wishlist/{userId}/{bookId}', [WishlistController::class, 'removeFromWishlist']);
    });

    Route::middleware(['admin'])->group(function () {
        //
    });
});


