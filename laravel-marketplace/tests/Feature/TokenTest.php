<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TokenTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TokenTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_get_token_balance()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        // Give user some tokens
        TokenTransaction::create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => 5,
            'description' => 'Test tokens',
            'expires_at' => now()->addMonth(),
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/tokens/balance');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'data' => [
                        'balance' => 5
                    ]
                ]);
    }

    public function test_user_can_purchase_tokens()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/tokens/purchase', [
            'package' => 5,
            'payment_method' => 'card',
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Tokens purchased successfully'
                ]);

        $this->assertDatabaseHas('token_transactions', [
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => 5,
            'description' => 'Purchased 5 token(s) for €20'
        ]);
    }

    public function test_user_can_view_token_transactions()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        // Create some transactions
        TokenTransaction::create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => 5,
            'description' => 'Test credit',
            'expires_at' => now()->addMonth(),
        ]);

        TokenTransaction::create([
            'user_id' => $user->id,
            'type' => 'debit',
            'amount' => 1,
            'description' => 'Test debit',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/tokens/transactions');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true
                ])
                ->assertJsonStructure([
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'type',
                                'amount',
                                'description',
                                'created_at'
                            ]
                        ]
                    ]
                ]);
    }

    public function test_user_can_get_token_packages()
    {
        $response = $this->getJson('/api/tokens/packages');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true
                ])
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'tokens',
                            'price',
                            'price_per_token',
                            'description'
                        ]
                    ]
                ]);
    }

    public function test_token_consumption_for_listing()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        // Give user tokens
        TokenTransaction::create([
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => 5,
            'description' => 'Test tokens',
            'expires_at' => now()->addMonth(),
        ]);

        // Create a listing
        $listing = \App\Models\Listing::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/tokens/consume', [
            'listing_id' => $listing->id,
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Token consumed successfully'
                ]);

        // Check that token was consumed
        $this->assertDatabaseHas('token_transactions', [
            'user_id' => $user->id,
            'type' => 'debit',
            'amount' => 1,
            'description' => 'Listing creation'
        ]);
    }

    public function test_user_cannot_consume_tokens_without_balance()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        // Create a listing
        $listing = \App\Models\Listing::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending'
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/tokens/consume', [
            'listing_id' => $listing->id,
        ]);

        $response->assertStatus(400)
                ->assertJson([
                    'success' => false,
                    'message' => 'Insufficient tokens. Please purchase more tokens.'
                ]);
    }
}
