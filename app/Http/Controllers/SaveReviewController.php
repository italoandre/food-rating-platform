<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SaveReviewController extends Controller
{
    public function __invoke(Request $request, string $restaurantId): JsonResponse
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
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

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('review-photos', 'public');
                
                $thumbnailPath = 'review-photos/thumb_' . basename($path);
                $manager = new ImageManager(new Driver());
                $thumbnail = $manager->read(Storage::disk('public')->path($path));
                $thumbnail->cover(300, 300);
                $thumbnail->save(Storage::disk('public')->path($thumbnailPath));
                
                $review->reviewPhotos()->create([
                    'external_id' => Str::uuid()->toString(),
                    'path' => $path,
                    'thumbnail_path' => $thumbnailPath,
                ]);
            }
        }

        return response()->json($review->toArray());
    }
}
