<?php

namespace App\Providers;

use App\Models\TokenTransaction;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class TokenServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Listen for user registration to give free tokens
        Event::listen('user.registered', function (User $user) {
            try {
                TokenTransaction::giveFreeTokens($user, 1);
                Log::info('Free tokens given to new user', [
                    'user_id' => $user->id,
                    'amount' => 1
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to give free tokens to new user', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        });

        // Listen for user verification to give additional tokens
        Event::listen('user.verified', function (User $user) {
            try {
                // Give bonus token for completing verification
                TokenTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'credit',
                    'amount' => 1,
                    'description' => 'Verification bonus token',
                    'expires_at' => now()->addMonth(),
                ]);
                
                Log::info('Verification bonus token given', [
                    'user_id' => $user->id,
                    'amount' => 1
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to give verification bonus token', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        });

        // Listen for listing creation to consume token
        Event::listen('listing.created', function ($listing) {
            try {
                $user = $listing->user;
                
                if ($user->token_balance > 0) {
                    TokenTransaction::consumeToken($user, $listing, 'Listing creation');
                    Log::info('Token consumed for listing creation', [
                        'user_id' => $user->id,
                        'listing_id' => $listing->id
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to consume token for listing', [
                    'listing_id' => $listing->id ?? null,
                    'error' => $e->getMessage()
                ]);
            }
        });

        // Listen for listing rejection to refund token
        Event::listen('listing.rejected', function ($listing) {
            try {
                $user = $listing->user;
                TokenTransaction::refundToken($user, $listing, 'Listing rejected by admin');
                
                Log::info('Token refunded for rejected listing', [
                    'user_id' => $user->id,
                    'listing_id' => $listing->id
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to refund token for rejected listing', [
                    'listing_id' => $listing->id ?? null,
                    'error' => $e->getMessage()
                ]);
            }
        });
    }
}
