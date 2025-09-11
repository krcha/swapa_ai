<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@laravel-marketplace.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@laravel-marketplace.com',
                'password' => Hash::make('admin123'),
                'phone' => '+381601234567',
                'is_email_verified' => true,
                'is_sms_verified' => true,
                'is_age_verified' => true,
                'user_type' => 'personal',
                'listing_limit' => 1000,
                'is_banned' => false,
                'has_priority_listing' => true,
            ]
        );

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@laravel-marketplace.com');
        $this->command->info('Password: admin123');
    }
}