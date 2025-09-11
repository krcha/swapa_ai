<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:user {--email=test@example.com} {--password=password} {--plan=tier-2}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test user account for marketplace testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $planSlug = $this->option('plan');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return Command::FAILURE;
        }

        // Generate unique phone number
        $phone = '+3816' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT);
        while (User::where('phone', $phone)->exists()) {
            $phone = '+3816' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT);
        }

        // Create test user
        $testUser = User::create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => $email,
            'phone' => $phone,
            'password' => Hash::make($password),
            'is_email_verified' => true,
            'is_sms_verified' => true,
        ]);

        // Assign subscription plan
        $plan = Plan::where('slug', $planSlug)->first();
        
        if ($plan) {
            $subscription = Subscription::create([
                'user_id' => $testUser->id,
                'plan_id' => $plan->id,
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
                'status' => 'active',
            ]);

            $this->info("✅ Test user created successfully!");
            $this->info("📧 Email: {$email}");
            $this->info("🔑 Password: {$password}");
            $this->info("📱 Phone: {$phone}");
            $this->info("💳 Plan: {$plan->name} ({$plan->listing_limit} listings/month)");
            $this->info("🆔 User ID: {$testUser->id}");
            $this->info("📋 Subscription ID: {$subscription->id}");
            
            return Command::SUCCESS;
        } else {
            $this->error("Plan '{$planSlug}' not found! Available plans:");
            Plan::all()->each(function($plan) {
                $this->line("  - {$plan->slug} ({$plan->name})");
            });
            return Command::FAILURE;
        }
    }
}