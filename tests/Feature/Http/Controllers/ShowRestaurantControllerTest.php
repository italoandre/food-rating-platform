<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShowRestaurantControllerTest extends TestCase
{
    use DatabaseTransactions;

    private User $user;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->token = auth()->login($this->user);
    }

    #[Test]
    public function it_can_show_restaurant_details()
    {
        $restaurant = Restaurant::factory()->create();
        $user = User::factory()->create();
        
        Review::factory(2)->create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson("/api/restaurants/{$restaurant->external_id}");

        $response->assertStatus(200)
            ->assertJson([
                'external_id' => $restaurant->external_id,
                'name' => $restaurant->name,
                'slug' => $restaurant->slug,
                'address' => $restaurant->address,
            ])
            ->assertJsonStructure([
                'external_id',
                'name',
                'slug',
                'address',
                'reviews' => [
                    '*' => [
                        'external_id',
                        'rating',
                        'title',
                        'description',
                        'user' => [
                            'id',
                            'name'
                        ],
                        'review_photos'
                    ]
                ]
            ]);
    }

    #[Test]
    public function it_returns_404_when_restaurant_not_found()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/restaurants/non-existent-id');

        $response->assertStatus(404);
    }

    #[Test]
    public function it_cannot_show_restaurant_without_authentication()
    {
        auth()->logout();
        auth()->forgetUser();
        $this->app->get('auth')->forgetGuards();

        $restaurant = Restaurant::factory()->create();
        
        $response = $this->getJson("/api/restaurants/{$restaurant->external_id}");

        $response->assertStatus(401);
    }
} 