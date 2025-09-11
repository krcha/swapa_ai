<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Plan;
use App\Models\QuotaTracking;
use Carbon\Carbon;

class QuotaTrackingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users and create quota tracking for current month
        $users = User::all();
        $currentDate = Carbon::now();
        $year = $currentDate->year;
        $month = $currentDate->month;
        
        foreach ($users as $user) {
            $plan = $user->currentPlan() ?? Plan::where('slug', 'free')->first();
            
            if ($plan) {
                QuotaTracking::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'year' => $year,
                        'month' => $month,
                    ],
                    [
                        'plan_id' => $plan->id,
                        'listings_used' => $user->listings()
                            ->whereYear('created_at', $year)
                            ->whereMonth('created_at', $month)
                            ->count(),
                        'listings_limit' => $plan->listing_limit,
                        'is_unlimited' => $plan->listing_limit === -1,
                        'reset_at' => $currentDate->copy()->startOfMonth(),
                    ]
                );
            }
        }
    }
}
