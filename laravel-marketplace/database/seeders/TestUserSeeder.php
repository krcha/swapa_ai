<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create test user
        $testUser = User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone' => '+38160123456',
            'password' => Hash::make('password'),
            'is_email_verified' => true,
            'is_sms_verified' => true,
            'created_at' => now(),
        ]);

        // Assign Tier 2 subscription (30 listings per month)
        $tier2Plan = Plan::where('name', 'Tier 2')->first();
        
        if ($tier2Plan) {
            $subscription = Subscription::create([
                'user_id' => $testUser->id,
                'plan_id' => $tier2Plan->id,
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'status' => 'active',
            ]);

            $this->command->info("Test user created successfully!");
            $this->command->info("Email: test@example.com");
            $this->command->info("Password: password");
            $this->command->info("Plan: Tier 2 (30 listings/month)");
            $this->command->info("Subscription ID: " . $subscription->id);
        } else {
            $this->command->error("Tier 2 plan not found! Please run PlanSeeder first.");
        }
    }
}
