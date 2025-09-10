<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\TokenTransaction;
use Illuminate\Console\Command;

class DistributeMonthlyTokens extends Command
{
    protected $signature = 'tokens:distribute-monthly';
    protected $description = 'Distribute monthly free tokens to verified users';

    public function handle()
    {
        $users = User::where('is_verified', true)->get();
        $distributed = 0;

        foreach ($users as $user) {
            // Check if user already received tokens this month
            $hasReceivedThisMonth = $user->tokenTransactions()
                ->where('description', 'Monthly free tokens')
                ->where('created_at', '>=', now()->startOfMonth())
                ->exists();

            if (!$hasReceivedThisMonth) {
                TokenTransaction::giveFreeTokens($user, 1);
                $distributed++;
            }
        }

        $this->info("Distributed monthly tokens to {$distributed} users.");
        
        return Command::SUCCESS;
    }
}
