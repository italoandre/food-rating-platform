<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Support\Str;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);

        $restaurants = [
            [
                'external_id' => Str::uuid()->toString(),
                'name' => 'The Gourmet Kitchen',
                'address' => '123 Main Street, Downtown',
                'slug' => 'the-gourmet-kitchen',
            ],
            [
                'external_id' => Str::uuid()->toString(),
                'name' => 'Bella Italia',
                'address' => '456 Food Avenue, Midtown',
                'slug' => 'bella-italia',
            ],
            [
                'external_id' => Str::uuid()->toString(),
                'name' => 'Sakura Sushi',
                'address' => '789 Ocean Drive, Seaside',
                'slug' => 'sakura-sushi',
            ],
        ];

        foreach ($restaurants as $restaurant) {
            Restaurant::create($restaurant);
        }
    }
}
