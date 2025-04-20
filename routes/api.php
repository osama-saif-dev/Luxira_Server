<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\WhishlistesController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// User
Route::prefix('/')->group(function () {

    // Auth
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/resetPassword', [AuthController::class, 'resetPassword']);
    Route::post('/verifyForgetPassword', [VerifyController::class, 'verifyForgetPassword']);

    // Auth
    Route::prefix('/auth')->middleware('auth:sanctum')->group(function () {
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/sendCode', [VerifyController::class, 'sendCode']);
        Route::post('/checkCode', [VerifyController::class, 'checkCode']);
    });

    // Offers
    Route::prefix('/offer')->group(function () {
        Route::get('/', [OfferController::class, 'index']);
    });

    // Products
    Route::prefix('/product')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/filter', [ProductController::class, 'filter']);
        // هنا ممكن يبعت query 
        Route::get('/{id}', [ProductController::class, 'show']);
    });

    // Categories
    Route::prefix('/category')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
    });

    // Whishlistes
    Route::prefix('/whishlistes')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [WhishlistesController::class, 'index']);
        Route::get('/create/{id}', [WhishlistesController::class, 'create']);
        Route::delete('/delete/{id}', [WhishlistesController::class, 'delete']);
    });

    // Cart
    Route::prefix('/cart')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/create', [CartController::class, 'create']);
        Route::delete('/delete/{id}', [CartController::class, 'delete']);
    });

    // Order
    Route::prefix('/order')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/create', [OrderController::class, 'create']);
        Route::delete('/delete/{id}', [OrderController::class, 'delete']);
    });


});

// Admin