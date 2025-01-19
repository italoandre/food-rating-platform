<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\SaveReviewController;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SaveReviewTest extends TestCase
{
    use DatabaseTransactions;
    private array $validRating = [1, 2, 3, 4, 5];
    private array $invalidRating = [-1, 0, 6, 10, 'abc'];
    private User $validUser;

    private function getRandomRating(array $ratings): int
    {
        return $ratings[array_rand($ratings)];
    }

    #[Test] public function review_is_saved_if_valid_data_without_photos_is_submitted()
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
}
