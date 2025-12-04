<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Picture;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $locations = ['Jakarta', 'Bandung', 'Surabaya', 'Bali', 'Yogyakarta', 'Medan', 'Semarang'];
        
        // Create 60 users
        for ($i = 1; $i <= 60; $i++) {
            $user = User::create([
                'name' => 'User ' . $i,
                'age' => rand(20, 35),
                'location' => $locations[array_rand($locations)],
                'email' => 'user' . $i . '@example.com',
            ]);
            
            // Add picture for each user
            Picture::create([
                'user_id' => $user->id,
                'image_url' => 'https://i.pravatar.cc/300?img=' . $i,
            ]);
        }
    }
}
