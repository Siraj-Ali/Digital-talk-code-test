<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create or update a user
        $user = User::updateOrCreate(
            ['email' => 'testuser@gmail.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        // Add more users as needed
        User::updateOrCreate(
            ['email' => 'admin12@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin1245'),
            ]
        );
    }
} 