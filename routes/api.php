<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\WhishlistesController;
use Illuminate\Support\Facades\Route;

// Clean Up 
Route::get('/offer/clean_up', [OfferController::class, 'cleanUp']);
Route::get('/discount/clean_up', [DiscountController::class, 'cleanUp']);

Route::prefix('/')->middleware('check.lang')->group(function () {

    // Auth And Verify
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/resetPassword', [AuthController::class, 'resetPassword']);
    Route::post('/verifyForgetPassword', [VerifyController::class, 'verifyForgetPassword']);
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
    
    // With Token 
    Route::prefix('/auth')->middleware('auth:sanctum')->group(function () {
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/sendCode', [VerifyController::class, 'sendCode']);
        Route::post('/checkCode', [VerifyController::class, 'checkCode']);
    });

    // Offers
    Route::prefix('/offer')->group(function () {
        Route::get('/', [OfferController::class, 'index']);
    });

    // Brands
    Route::prefix('/brands')->group(function () {
        Route::get('/', [BrandController::class, 'index']);
    });
    
    // Categories
    Route::prefix('/categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
    });

    // Subcategories
    Route::prefix('/subcategories')->group(function () {
        Route::get('/', [SubcategoryController::class, 'index']);
    });
    
    // Products
    Route::prefix('/product')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/filter', [ProductController::class, 'filter']);
        // هنا ممكن يبعت query 
        Route::get('/{id}', [ProductController::class, 'show']);
    });
    
    // Whishlistes
    Route::prefix('/whishlistes')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [WhishlistesController::class, 'index']);
        Route::get('/store/{id}', [WhishlistesController::class, 'store']);
        Route::delete('/delete/{id}', [WhishlistesController::class, 'delete']);
    });
    
    // Cart
    Route::prefix('/cart')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/store', [CartController::class, 'store']);
        Route::delete('/delete_cart/{id}', [CartController::class, 'deleteCart']);
        Route::delete('/delete_product/{id}', [CartController::class, 'deleteProduct']);
    });

    // Reviews 
    

    // Order
    Route::prefix('/order')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/create', [OrderController::class, 'create']);
        Route::delete('/delete/{id}', [OrderController::class, 'delete']);
    });

    // Admin
    Route::prefix('/admin')->middleware(['auth:sanctum', 'check.is.admin'])->group(function() {
        
        Route::post('/change_role/{id}', [AdminController::class, 'changeRole']);
        
        // Users
        Route::prefix('/users')->group(function() {
            Route::get('/', [AdminController::class, 'getUsers']);
            Route::get('/show/{id}', [AdminController::class, 'showUser']); 
            Route::post('/store', [AdminController::class, 'storeUser']);
            Route::delete('/delete/{id}', [AdminController::class, 'deleteUser']);
        });

        // Brands
        Route::prefix('/brands')->group(function() {
            Route::get('/', [AdminController::class, 'getBrands']);
            Route::get('/show/{id}', [AdminController::class, 'showBrand']);
            Route::post('/store', [AdminController::class, 'storeBrand']);
            Route::post('/update/{id}', [AdminController::class, 'updateBrand']);
        });

        // Categories 
        Route::prefix('/categories')->group(function() {
            Route::get('/', [AdminController::class, 'getCategories']);
            Route::get('/show/{id}', [AdminController::class, 'showCategory']);
            Route::post('/store', [AdminController::class, 'storeCategory']);
            Route::post('/update/{id}', [AdminController::class, 'updateCategory']);
        });     

        // Subcategories 
        Route::prefix('/subcategories')->group(function() {
            Route::get('/', [AdminController::class, 'getSubcategories']);
            Route::get('/show/{id}', [AdminController::class, 'showSubcategory']);
            Route::post('/store', [AdminController::class, 'storeSubcategory']);
            Route::post('/update/{id}', [AdminController::class, 'updateSubcategory']);
        });    

        // Products
        Route::prefix('/products')->group(function() {
            Route::get('/', [AdminController::class, 'getProducts']);
            Route::get('/getDataToProduct', [AdminController::class, 'getDataToProduct']);
            Route::get('/show/{id}', [AdminController::class, 'showProduct']);
            Route::post('/store', [AdminController::class, 'storeProduct']);
            Route::post('/update/{id}', [AdminController::class, 'updateProduct']);
            Route::delete('/delete_image/{productId}/{imageId}', [AdminController::class, 'deleteImage']);
        });

        // Offer
        Route::prefix('/offers')->group(function() {
            Route::get('/', [AdminController::class, 'getOffers']);
            Route::get('/show/{id}', [AdminController::class, 'showOffer']);
            Route::post('/store', [AdminController::class, 'storeOffer']);
            Route::post('/update/{id}', [AdminController::class, 'updateOffer']);
            Route::delete('/delete/{id}', [AdminController::class, 'deleteOffer']);
        });

        // Size
        Route::prefix('/sizes')->group(function() {
            Route::get('/', [AdminController::class, 'getSizes']);
            Route::get('/show/{id}', [AdminController::class, 'showSize']);
            Route::post('/store', [AdminController::class, 'storeSize']);
            Route::delete('/delete/{id}', [AdminController::class, 'deleteSize']);
        });

        // Color
        Route::prefix('/colors')->group(function() {
            Route::get('/', [AdminController::class, 'getColors']);
            Route::get('/show/{id}', [AdminController::class, 'showColor']);
            Route::post('/store', [AdminController::class, 'storeColor']);
            Route::delete('/delete/{id}', [AdminController::class, 'deleteColor']);
        });

        // Discount
        Route::prefix('/discounts')->group(function() {
            Route::get('/', [AdminController::class, 'getDiscounts']);
            Route::get('/show/{id}', [AdminController::class, 'showDiscount']);
            Route::post('/store', [AdminController::class, 'storeDiscount']);
            Route::post('/update/{id}', [AdminController::class, 'updateDiscount']);
            Route::delete('/delete/{id}', [AdminController::class, 'deleteDiscount']);
        });

        // Shipping
        Route::prefix('/shippings')->group(function() {
            Route::get('/', [AdminController::class, 'getShippings']);
            Route::get('/show/{id}', [AdminController::class, 'showShipping']);
            Route::post('/store', [AdminController::class, 'storeShipping']);
            Route::post('/update/{id}', [AdminController::class, 'updateShipping']);
            Route::delete('/delete/{id}', [AdminController::class, 'deleteShipping']);
        });

    });

});
