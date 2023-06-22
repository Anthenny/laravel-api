<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use \App\Http\Controllers\Api\AuthController;
use \App\Http\Controllers\Api\ProductController;

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

Route::apiResource('products',\App\Http\Controllers\Api\ProductController::class);
