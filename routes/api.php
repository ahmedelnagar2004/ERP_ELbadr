<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\V1\ClientAuthController;
use App\Http\Controllers\api\V1\ItemController;

Route::group(['middleware' => 'api'], function () {
// Add v1 in route as perfix
Route::prefix('v1')->group(function () {
    Route::post('clients/register', [ClientAuthController::class, 'register']);
    Route::post('clients/login', [ClientAuthController::class, 'login']);
    Route::apiResource('items', ItemController::class); 
});

});