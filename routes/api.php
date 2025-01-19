<?php

use App\Http\Controllers\SaveReviewController;
use Illuminate\Support\Facades\Route;

Route::post('/save-review/{restaurantId}', SaveReviewController::class);
