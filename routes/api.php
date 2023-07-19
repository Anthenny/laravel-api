<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use \App\Http\Controllers\Api\AuthController;
use \App\Http\Controllers\Api\ProductController;
use \App\Http\Controllers\Api\OrderController;
use \App\Http\Controllers\Api\UserController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

// Routes die toegankelijk zijn voor iedereen
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/products', [ProductController::class, 'index']);
Route::post('/checkout/sessions', [ProductController::class, 'checkout']);
Route::get('/checkout/success', [ProductController::class, 'success']);
Route::get('/checkout/cancel', [ProductController::class, 'cancel']);

// Routes die toegankelijk zijn voor normale gebruikers, dashboard, orders
Route::middleware(['auth'])->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/user/orders/{email}', [OrderController::class, 'showUserOrders']);
});

// Routes die alleen toegankelijk zijn voor admin dashboard, producten cud, order cud
Route::middleware(['auth', 'is_admin'])->group(function () {
    // Products
    Route::post('/dashboard/products', [ProductController::class, 'store']);
    Route::patch('/dashboard/products/{product}', [ProductController::class, 'update']);
    Route::delete('/dashboard/products/{product}', [ProductController::class, 'destroy']);

    // Users
    Route::delete('/dashboard/users/{user}', [UserController::class, 'destroy']);
    Route::patch('/dashboard/users/{user}', [UserController::class, 'update']);
    Route::get('/dashboard/users/{user}', [UserController::class, 'show']);
    Route::get('/dashboard/users', [UserController::class, 'index']);

    // Orders
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::patch('/orders/{order}', [OrderController::class, 'update']);
});
