<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TokenTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_can_register_with_valid_data()
    {
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+381601234567',
            'jmbg' => '1234567890123', // Valid JMBG format
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms_accepted' => true,
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'User registered successfully. Please verify your email and phone.'
                ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'phone' => '+381601234567',
            'is_age_verified' => true,
        ]);

        // Check if free token was given
        $this->assertDatabaseHas('token_transactions', [
            'user_id' => User::where('email', 'john@example.com')->first()->id,
            'type' => 'credit',
            'amount' => 1,
            'description' => 'Monthly free tokens'
        ]);
    }

    public function test_user_cannot_register_with_invalid_jmbg()
    {
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+381601234567',
            'jmbg' => '1234567890124', // Invalid JMBG checksum
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms_accepted' => true,
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
                ->assertJson([
                    'success' => false,
                    'message' => 'Invalid JMBG format or checksum'
                ]);
    }

    public function test_user_cannot_register_under_18()
    {
        $userData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+381601234567',
            'jmbg' => '1234567890123', // JMBG that would make user under 18
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms_accepted' => true,
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(422)
                ->assertJson([
                    'success' => false,
                    'message' => 'You must be at least 18 years old to register'
                ]);
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Login successful'
                ])
                ->assertJsonStructure([
                    'data' => [
                        'user',
                        'token',
                        'token_type'
                    ]
                ]);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                ->assertJson([
                    'success' => false,
                    'message' => 'Invalid credentials'
                ]);
    }

    public function test_authenticated_user_can_get_profile()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/profile');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true
                ])
                ->assertJsonStructure([
                    'data' => [
                        'user',
                        'token_balance',
                        'verification_status'
                    ]
                ]);
    }

    public function test_unauthenticated_user_cannot_access_protected_routes()
    {
        $response = $this->getJson('/api/profile');

        $response->assertStatus(401);
    }
}
