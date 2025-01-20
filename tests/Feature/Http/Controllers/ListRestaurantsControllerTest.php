<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListRestaurantsControllerTest extends TestCase
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
    public function it_can_list_all_restaurants()
    {
        $restaurants = Restaurant::factory(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/restaurants');

        $response->assertStatus(200)
            ->assertJsonCount(3)
            ->assertJsonStructure([
                '*' => [
                    'external_id',
                    'name',
                    'slug',
                    'address'
                ]
            ]);
    }

    #[Test]
    public function it_returns_empty_array_when_no_restaurants_exist()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token
        ])->getJson('/api/restaurants');

        $response->assertStatus(200)
            ->assertJson([]);
    }

    #[Test]
    public function it_cannot_list_restaurants_without_authentication()
    {
        auth()->logout();
        auth()->forgetUser();
        $this->app->get('auth')->forgetGuards();

        $response = $this->getJson('/api/restaurants');

        $response->assertStatus(401);
    }
} 