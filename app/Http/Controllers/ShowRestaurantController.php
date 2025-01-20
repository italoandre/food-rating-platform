<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;

class ShowRestaurantController extends Controller
{
    public function __invoke(string $restaurantId): JsonResponse
    {
        $restaurant = Restaurant::with(['reviews' => function ($query) {
            $query->latest()
                ->with(['user:id,name', 'reviewPhotos']);
        }])->where('external_id', $restaurantId)
            ->firstOrFail();

        return response()->json($restaurant);
    }
}