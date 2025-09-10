<?php

namespace App\Http\Controllers;

use App\Models\TokenTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TokenController extends Controller
{
    /**
     * Get user token balance
     */
    public function balance(Request $request)
    {
        try {
            $user = $request->user();
            
            $balance = $user->token_balance;
            $expiringSoon = $this->getExpiringTokens($user);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'balance' => $balance,
                    'expiring_soon' => $expiringSoon,
                    'user' => $user->makeHidden(['jmbg_hash', 'password'])
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Token balance fetch failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch token balance'
            ], 500);
        }
    }

    /**
     * Get token transaction history
     */
    public function transactions(Request $request)
    {
        try {
            $user = $request->user();
            
            $perPage = $request->get('per_page', 20);
            $type = $request->get('type'); // credit, debit, or null for all
            
            $query = $user->tokenTransactions()->orderBy('created_at', 'desc');
            
            if ($type && in_array($type, ['credit', 'debit'])) {
                $query->where('type', $type);
            }
            
            $transactions = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $transactions
            ]);

        } catch (\Exception $e) {
            Log::error('Token transactions fetch failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch token transactions'
            ], 500);
        }
    }

    /**
     * Purchase tokens
     */
    public function purchase(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'package' => 'required|in:1,5,10,20',
                'payment_method' => 'required|string|in:card,bank_transfer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();
            $package = $request->package;
            $amount = $package;

            // Calculate price (example pricing)
            $prices = [
                1 => 5.00,   // 5 EUR per token
                5 => 20.00,  // 4 EUR per token
                10 => 35.00, // 3.5 EUR per token
                20 => 60.00  // 3 EUR per token
            ];

            $price = $prices[$package];

            // Start database transaction
            DB::beginTransaction();

            try {
                // In a real implementation, this would integrate with a payment gateway
                // For now, we'll simulate a successful payment
                $paymentReference = 'PAY_' . time() . '_' . $user->id;
                $paymentStatus = 'completed'; // Simulated

                if ($paymentStatus !== 'completed') {
                    throw new \Exception('Payment failed');
                }

                // Create token transaction
                $transaction = TokenTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'credit',
                    'amount' => $amount,
                    'description' => "Purchased {$package} token(s) for €{$price}",
                    'expires_at' => now()->addYear(), // Purchased tokens don't expire
                ]);

                // Log transaction
                Log::info('Tokens purchased', [
                    'user_id' => $user->id,
                    'package' => $package,
                    'amount' => $amount,
                    'price' => $price,
                    'payment_reference' => $paymentReference
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Tokens purchased successfully',
                    'data' => [
                        'transaction' => $transaction,
                        'new_balance' => $user->fresh()->token_balance,
                        'payment_reference' => $paymentReference,
                        'package' => $package,
                        'amount' => $amount,
                        'price' => $price
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Token purchase failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null,
                'package' => $request->package ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Token purchase failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Consume token for listing creation
     */
    public function consumeForListing(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'listing_id' => 'required|exists:listings,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = $request->user();
            $listing = \App\Models\Listing::findOrFail($request->listing_id);

            // Check if user owns the listing
            if ($listing->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Check if user has available tokens
            if ($user->token_balance <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient tokens. Please purchase more tokens.'
                ], 400);
            }

            // Consume token
            $transaction = TokenTransaction::consumeToken($user, $listing, 'Listing creation');

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to consume token'
                ], 400);
            }

            Log::info('Token consumed for listing', [
                'user_id' => $user->id,
                'listing_id' => $listing->id,
                'transaction_id' => $transaction->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Token consumed successfully',
                'data' => [
                    'transaction' => $transaction,
                    'new_balance' => $user->fresh()->token_balance
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Token consumption failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? null,
                'listing_id' => $request->listing_id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Token consumption failed'
            ], 500);
        }
    }

    /**
     * Get token packages and pricing
     */
    public function packages()
    {
        try {
            $packages = [
                [
                    'id' => 1,
                    'tokens' => 1,
                    'price' => 5.00,
                    'price_per_token' => 5.00,
                    'description' => 'Single token for one listing'
                ],
                [
                    'id' => 5,
                    'tokens' => 5,
                    'price' => 20.00,
                    'price_per_token' => 4.00,
                    'description' => '5 tokens - 20% savings',
                    'popular' => true
                ],
                [
                    'id' => 10,
                    'tokens' => 10,
                    'price' => 35.00,
                    'price_per_token' => 3.50,
                    'description' => '10 tokens - 30% savings'
                ],
                [
                    'id' => 20,
                    'tokens' => 20,
                    'price' => 60.00,
                    'price_per_token' => 3.00,
                    'description' => '20 tokens - 40% savings',
                    'best_value' => true
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $packages
            ]);

        } catch (\Exception $e) {
            Log::error('Token packages fetch failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch token packages'
            ], 500);
        }
    }

    /**
     * Distribute monthly free tokens (admin/cron job)
     */
    public function distributeMonthlyTokens()
    {
        try {
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

            Log::info('Monthly tokens distributed', [
                'distributed_to' => $distributed,
                'total_verified_users' => $users->count()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Distributed monthly tokens to {$distributed} users.",
                'data' => [
                    'distributed_to' => $distributed,
                    'total_verified_users' => $users->count()
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Monthly token distribution failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Monthly token distribution failed'
            ], 500);
        }
    }

    /**
     * Get tokens expiring soon
     */
    private function getExpiringTokens(User $user)
    {
        return $user->tokenTransactions()
            ->credits()
            ->where('expires_at', '>', now())
            ->where('expires_at', '<=', now()->addDays(7))
            ->sum('amount');
    }
}