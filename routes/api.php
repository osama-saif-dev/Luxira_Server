<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\WhishlistesController;
use Illuminate\Support\Facades\Route;



Route::prefix('/')->middleware('check.lang')->group(function () {

    // Auth And Verify
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/resetPassword', [AuthController::class, 'resetPassword']);
    Route::post('/verifyForgetPassword', [VerifyController::class, 'verifyForgetPassword']);

    Route::prefix('/auth')->middleware('auth:sanctum')->group(function () {
        Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
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

    // Admin
    Route::prefix('/admin')->middleware(['auth:sanctum', 'check.is.admin'])->group(function() {
        
        // Users
        Route::prefix('/users')->group(function() {
            Route::get('/', [AdminController::class, 'getUsers']);
            Route::post('/store', [AdminController::class, 'storeUser']);
            Route::post('/change_role/{id}', [AdminController::class, 'changeRole']);
            Route::delete('/delete/{id}', [AdminController::class, 'deleteUser']);
        });
        
        // Brands
        Route::prefix('/brands')->group(function() {
            Route::get('/', [AdminController::class, 'getBrands']);
            Route::post('/store', [AdminController::class, 'storeBrand']);
            Route::post('/update/{id}', [AdminController::class, 'updateBrand']);
            Route::delete('/delete/{id}', [AdminController::class, 'deleteBrand']);
        });

        // Categories 
        Route::prefix('/categories')->group(function() {
            Route::get('/', [AdminController::class, 'getCategories']);
            Route::post('/store', [AdminController::class, 'storeCategory']);
            Route::post('/update/{id}', [AdminController::class, 'updateCategory']);
            Route::delete('/delete/{id}', [AdminController::class, 'deleteCategory']);
        });        

        // Products
        Route::prefix('/products')->group(function() {
            Route::get('/', [AdminController::class, 'getProducts']);
            Route::post('/store', [AdminController::class, 'storeProduct']);
            Route::post('/update/{id}', [AdminController::class, 'updateProduct']);
            Route::post('/change_product_status/{id}', [AdminController::class, 'changeProductStatus']);
        });
        
    });

});
