<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+381' . fake()->numerify('########'),
            'jmbg_hash' => hash('sha256', fake()->numerify('#############') . config('app.key')),
            'is_verified' => fake()->boolean(80),
            'is_sms_verified' => fake()->boolean(70),
            'is_email_verified' => fake()->boolean(75),
            'is_age_verified' => true,
            'is_admin' => false,
            'email_verified_at' => fake()->optional(0.7)->dateTimeBetween('-1 year', 'now'),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
            'is_email_verified' => false,
        ]);
    }

    /**
     * Indicate that the model should be fully verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => true,
            'is_sms_verified' => true,
            'is_email_verified' => true,
            'is_age_verified' => true,
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Indicate that the model should be an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_admin' => true,
            'is_verified' => true,
            'is_sms_verified' => true,
            'is_email_verified' => true,
            'is_age_verified' => true,
        ]);
    }
}
