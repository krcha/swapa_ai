<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free Plan',
                'slug' => 'free',
                'price' => 0.00,
                'listing_limit' => 1,
                'features' => ['Basic listing', '30-day duration', 'Community support'],
                'is_active' => true,
                'trial_days' => 0,
                'listing_duration_days' => 30,
                'description' => 'Perfect for trying out our marketplace',
            ],
            [
                'name' => 'Tier 1',
                'slug' => 'tier-1',
                'price' => 5.00,
                'listing_limit' => 5,
                'features' => ['5 listings per month', '60-day duration', 'Priority support', 'Featured listings'],
                'is_active' => true,
                'trial_days' => 7,
                'listing_duration_days' => 60,
                'description' => 'Great for casual sellers',
            ],
            [
                'name' => 'Tier 2',
                'slug' => 'tier-2',
                'price' => 15.00,
                'listing_limit' => 30,
                'features' => ['30 listings per month', '90-day duration', 'Priority support', 'Featured listings', 'Analytics dashboard'],
                'is_active' => true,
                'trial_days' => 14,
                'listing_duration_days' => 90,
                'description' => 'Perfect for regular sellers',
            ],
            [
                'name' => 'Tier 3',
                'slug' => 'tier-3',
                'price' => 50.00,
                'listing_limit' => -1, // -1 for unlimited
                'features' => ['Unlimited listings', '120-day duration', 'Premium support', 'Featured listings', 'Analytics dashboard', 'Custom branding'],
                'is_active' => true,
                'trial_days' => 30,
                'listing_duration_days' => 120,
                'description' => 'For power sellers and businesses',
            ],
        ];

        foreach ($plans as $planData) {
            Plan::updateOrCreate(
                ['slug' => $planData['slug']],
                $planData
            );
        }

        $this->command->info('Subscription plans seeded successfully!');
    }
}