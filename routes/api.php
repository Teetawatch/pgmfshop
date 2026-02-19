<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SocialAuthController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Social OAuth
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback']);

// Products (public)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

// Categories (public)
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);

// Reviews (public read)
Route::get('/products/{product}/reviews', [ReviewController::class, 'index']);

// Coupon validation (public)
Route::post('/coupons/validate', [CouponController::class, 'validateCoupon']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Customer + Admin)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Orders (customer sees own, admin sees all)
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{orderNumber}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);

    // Reviews (authenticated)
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    // Dashboard & Reports
    Route::get('/dashboard', [ReportController::class, 'dashboard']);
    Route::get('/reports/sales', [ReportController::class, 'salesReport']);

    // Products CRUD
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    Route::patch('/products/{product}/stock', [ProductController::class, 'updateStock']);

    // Categories CRUD
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    // Orders management
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);

    // Coupons CRUD
    Route::get('/coupons', [CouponController::class, 'index']);
    Route::post('/coupons', [CouponController::class, 'store']);
    Route::put('/coupons/{coupon}', [CouponController::class, 'update']);
    Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy']);

    // Customers
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customers/{user}', [CustomerController::class, 'show']);
});
