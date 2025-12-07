<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\V1\ClientAuthController;
use App\Http\Controllers\api\V1\ItemController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\OrderController;

Route::group(['middleware' => 'api'], function () {

Route::prefix('v1')->group(function () {
    Route::post('clients/register', [ClientAuthController::class, 'register']);
    Route::post('clients/login', [ClientAuthController::class, 'login']);
    Route::apiResource('items', ItemController::class);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('profile', [ClientAuthController::class, 'get_profile_client']);
        Route::post('update-profile', [ClientAuthController::class, 'updateProfile']);

        Route::get('cart', [CartController::class, 'index']);
        Route::post('cart/add', [CartController::class, 'addToCart']);
        Route::post('cart/update/{id}', [CartController::class, 'updateCart']);
        Route::delete('cart/delete/{id}', [CartController::class, 'deleteFromCart']);

        // Order Routes
        Route::post('checkout', [OrderController::class, 'checkout']);
        Route::get('orders', [OrderController::class, 'index']);
        Route::get('orders/{id}', [OrderController::class, 'show']);
    });
});

});