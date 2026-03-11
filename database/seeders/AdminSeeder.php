<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('10203040'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Optional: Create a test regular user
        User::create([
            'name' => 'Regular User',
            'email' => 'user@medinear.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }
}
