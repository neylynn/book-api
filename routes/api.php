<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\UserController;

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
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/{bookId}', [BookController::class, 'show']);
    Route::get('/books/search/{name}', [BookController::class, 'search']);
});


