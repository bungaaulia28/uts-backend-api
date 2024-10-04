<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group( function () {
    Route::post('auth/register', \App\Http\Controllers\Api\Auth\RegisterController::class);
    Route::post('auth/login', \App\Http\Controllers\Api\Auth\LoginController::class);

    Route::middleware('auth:sanctum')->group(function () {
        //Route untuk Logout user
       Route::post('auth/logout', \App\Http\Controllers\Api\Auth\LogoutController::class);
       Route::resource('categories', \App\Http\Controllers\Api\CategoryController::class)->except(['edit']);
       Route::resource('products', \App\Http\Controllers\Api\ProductController::class)->except(['edit']);
    });

    // Route::apiResource('/home', \App\Http\Controllers\Api\HomeController::class);

    Route::get('/home', [ProductController::class, 'index'])->name('product.index');


});
