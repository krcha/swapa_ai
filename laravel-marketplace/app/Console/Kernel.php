<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Monthly token distribution - runs on the 1st of every month at 9:00 AM
        $schedule->command('tokens:distribute-monthly')
                 ->monthlyOn(1, '09:00')
                 ->timezone('Europe/Belgrade');

        // Clean up expired tokens - runs daily at 2:00 AM
        $schedule->call(function () {
            \App\Models\TokenTransaction::where('expires_at', '<', now())
                ->where('type', 'credit')
                ->delete();
        })->dailyAt('02:00')->timezone('Europe/Belgrade');

        // Clean up expired listings - runs daily at 3:00 AM
        $schedule->call(function () {
            \App\Models\Listing::where('expires_at', '<', now())
                ->where('status', 'active')
                ->update(['status' => 'expired']);
        })->dailyAt('03:00')->timezone('Europe/Belgrade');

        // Send listing expiration reminders - runs daily at 10:00 AM
        $schedule->call(function () {
            $expiringListings = \App\Models\Listing::where('expires_at', '<=', now()->addDays(3))
                ->where('expires_at', '>', now())
                ->where('status', 'active')
                ->with('user')
                ->get();

            foreach ($expiringListings as $listing) {
                // Send notification to user about expiring listing
                // This would typically send an email or push notification
                \Log::info('Listing expiring soon', [
                    'listing_id' => $listing->id,
                    'user_id' => $listing->user_id,
                    'expires_at' => $listing->expires_at
                ]);
            }
        })->dailyAt('10:00')->timezone('Europe/Belgrade');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
