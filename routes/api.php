<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaveReviewController;
use App\Http\Controllers\ListRestaurantsController;
use App\Http\Controllers\ShowRestaurantController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

Route::middleware('auth:api')->group(function () {
    Route::get('/restaurants', ListRestaurantsController::class);
    Route::get('/restaurants/{restaurantId}', ShowRestaurantController::class);
    Route::post('/restaurants/{restaurantId}/review', SaveReviewController::class);
});


