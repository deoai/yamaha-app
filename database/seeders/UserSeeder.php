<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => User::ROLE_ADMIN,
            'no_hp' => '081234567890',
            'password' => Hash::make('Admin123'),
        ]);

        // Test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => User::ROLE_USER,
            'no_hp' => '081234567891',
            'password' => Hash::make('User123'),
        ]);

        // Create 5 random users (all will be regular users by default)
        User::factory(5)->create();
    }
}
