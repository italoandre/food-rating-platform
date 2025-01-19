<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SaveReviewController extends Controller
{
    public function __invoke(Request $request, string $restaurantId): JsonResponse
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photos' => 'nullable|array',
        ]);

        /**
         * Temporary solution, as authentication is not yet set.
         */
        $user = auth()->user() ?? User::whereNull('deleted_at')->first();

        $restaurant = Restaurant::findByExternalId($restaurantId);
        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        $review = $restaurant->reviews()->create([
            'external_id' => Str::uuid()->toString(),
            'rating' => $request->input('rating'),
            'user_id' => $user->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);

        return response()->json($review->toArray());
    }
}
