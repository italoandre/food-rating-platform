<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SaveReviewTest extends TestCase
{
    use DatabaseTransactions;
    private array $validRating = [1, 2, 3, 4, 5];
    private array $invalidRating = [-1, 0, 6, 10, 'abc'];
    private User $validUser;

    private function getRandomRating(array $ratings): int|string
    {
        return $ratings[array_rand($ratings)];
    }

    #[Test] public function review_is_saved_if_valid_data_and_no_photos_are_submitted()
    {
        $restaurant = Restaurant::factory()->createOne();
        $data = [
            'rating' => $this->getRandomRating($this->validRating),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
        $response = $this->postJson("/api/save-review/{$restaurant->external_id}", $data);
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('rating', $content);
        $this->assertGreaterThanOrEqual(1, $content['rating']);
        $this->assertLessThanOrEqual(5, $content['rating']);
        $this->assertArrayHasKey('title', $content);
        $this->assertArrayHasKey('description', $content);
        $this->assertArrayHasKey('photos', $content);
    }

    #[Test] public function review_is_not_saved_if_invalid_data_is_submitted()
    {
        $restaurant = Restaurant::factory()->createOne();
        $data = [
            'rating' => $this->getRandomRating($this->invalidRating),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
        $response = $this->postJson("/api/save-review/{$restaurant->external_id}", $data);

        $response->assertStatus(422);
    }

    #[Test]
    public function review_is_saved_if_valid_data_with_photos_are_submitted()
    {
        $restaurant = Restaurant::factory()->createOne();

        $tempFile1 = tempnam(sys_get_temp_dir(), 'test_');
        $tempFile2 = tempnam(sys_get_temp_dir(), 'test_');

        file_put_contents($tempFile1, file_get_contents(base_path('tests/fixtures/test-image.jpg')));
        file_put_contents($tempFile2, file_get_contents(base_path('tests/fixtures/test-image.jpg')));

        $data = [
            'rating' => $this->getRandomRating($this->validRating),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'photos' => [
                new UploadedFile(
                    $tempFile1,
                    'photo1.jpg',
                    'image/jpeg',
                    null,
                    true
                ),
                new UploadedFile(
                    $tempFile2,
                    'photo2.jpg',
                    'image/jpeg',
                    null,
                    true
                ),
            ],
        ];

        $response = $this->postJson("/api/save-review/{$restaurant->external_id}", $data);
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('rating', $content);
        $this->assertGreaterThanOrEqual(1, $content['rating']);
        $this->assertLessThanOrEqual(5, $content['rating']);
        $this->assertArrayHasKey('title', $content);
        $this->assertArrayHasKey('description', $content);
        $this->assertArrayHasKey('photos', $content);
        $this->assertCount(2, $content['photos']);

        foreach ($content['photos'] as $photo) {
            Storage::disk('public')->assertExists($photo['path']);
            Storage::disk('public')->assertExists($photo['thumbnail_path']);
            $this->assertStringStartsWith('review-photos/thumb_', $photo['thumbnail_path']);
        }

        @unlink($tempFile1);
        @unlink($tempFile2);
    }
}
