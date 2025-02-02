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

        $user = auth()->user();

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

        try {
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $this->savePhoto($photo, $review);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error processing photos',
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json($review->toArray());
    }

    private function savePhoto($photo, $review): void
    {
        $path = $photo->store('review-photos', 'public');
        if (!$path) {
            throw new \RuntimeException('Failed to store photo');
        }

        try {
            $thumbnailPath = $this->createThumbnail($path);
            
            $review->reviewPhotos()->create([
                'external_id' => Str::uuid()->toString(),
                'path' => $path,
                'thumbnail_path' => $thumbnailPath,
            ]);
        } catch (\Exception $e) {
            Storage::disk('public')->delete($path);
            throw $e;
        }
    }

    private function createThumbnail(string $originalPath): string
    {
        $thumbnailPath = 'review-photos/thumb_' . basename($originalPath);
        $manager = new ImageManager(new Driver());
        $thumbnail = $manager->read(Storage::disk('public')->path($originalPath));
        $thumbnail->cover(300, 300);
        
        if (!$thumbnail->save(Storage::disk('public')->path($thumbnailPath))) {
            throw new \RuntimeException('Failed to save thumbnail');
        }

        return $thumbnailPath;
    }
}
