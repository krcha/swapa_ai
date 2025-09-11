<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeederV2 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'price' => 0.00,
                'listing_limit' => 2,
                'features' => [
                    'Phone verification required',
                    '2 listings per month',
                    '30-day listing duration',
                    'Standard support',
                    'Basic search and filters',
                    'Email notifications'
                ],
                'is_active' => true,
                'trial_days' => 0,
                'listing_duration_days' => 30,
                'description' => 'Perfect for occasional sellers who want to test the platform.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Tier 1',
                'slug' => 'tier-1',
                'price' => 5.00,
                'listing_limit' => 10,
                'features' => [
                    'Phone verification required',
                    '10 listings per month',
                    '60-day listing duration',
                    'Priority support',
                    'Advanced search and filters',
                    'Listing analytics',
                    'Email and SMS notifications',
                    'Featured listing placement',
                    'Bulk listing tools'
                ],
                'is_active' => true,
                'trial_days' => 30,
                'listing_duration_days' => 60,
                'description' => 'Great for regular sellers who want more visibility and features.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Tier 2',
                'slug' => 'tier-2',
                'price' => 15.00,
                'listing_limit' => 50,
                'features' => [
                    'Phone verification required',
                    '50 listings per month',
                    '90-day listing duration',
                    'Priority support',
                    'Advanced search and filters',
                    'Detailed analytics dashboard',
                    'Email and SMS notifications',
                    'Featured listing placement',
                    'Bulk listing tools',
                    'Advanced reporting',
                    'Priority listing approval'
                ],
                'is_active' => true,
                'trial_days' => 30,
                'listing_duration_days' => 90,
                'description' => 'Ideal for active sellers who need more listings and advanced features.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Tier 3',
                'slug' => 'tier-3',
                'price' => 30.00,
                'listing_limit' => -1, // Unlimited
                'features' => [
                    'Phone verification required',
                    'Unlimited listings',
                    '120-day listing duration',
                    'Premium support',
                    'Advanced search and filters',
                    'Comprehensive analytics dashboard',
                    'Email and SMS notifications',
                    'Featured listing placement',
                    'Bulk listing tools',
                    'API access',
                    'Custom branding',
                    'Priority listing approval',
                    'Advanced reporting',
                    'White-label options',
                    'Dedicated account manager'
                ],
                'is_active' => true,
                'trial_days' => 30,
                'listing_duration_days' => 120,
                'description' => 'Perfect for power sellers and businesses who need unlimited listings and premium features.',
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $planData) {
            Plan::updateOrCreate(
                ['slug' => $planData['slug']],
                $planData
            );
        }
    }
}
