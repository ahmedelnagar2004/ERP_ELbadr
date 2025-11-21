<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ApiUserController;
use App\Http\Controllers\api\ApiClientController;


// Remove the 'api' prefix since Laravel already adds it
Route::group(['middleware' => 'api'], function () {
    Route::get('clients', [ApiClientController::class, 'index']);
    Route::post('clients/register', [ApiClientController::class, 'register']);
    Route::post('clients/login', [ApiClientController::class, 'login']);
    Route::get('clients/{id}', [ApiClientController::class, 'profile']);
    Route::put('clients/{id}', [ApiClientController::class, 'update']);
    Route::delete('clients/{id}', [ApiClientController::class, 'destroy']);
});
