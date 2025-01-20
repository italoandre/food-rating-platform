<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;

class ListRestaurantsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $restaurants = Restaurant::query()
            ->select(['external_id', 'name', 'slug', 'address'])
            ->orderBy('name')
            ->get();

        return response()->json($restaurants);
    }
}