<?php

namespace Database\Factories;

use App\Models\Listing;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $conditions = ['like_new', 'excellent', 'good', 'fair'];
        $storages = ['32GB', '64GB', '128GB', '256GB', '512GB', '1TB'];
        $colors = ['Black', 'White', 'Silver', 'Gold', 'Blue', 'Red', 'Green'];
        $carriers = ['Unlocked', 'Telenor', 'MTS', 'Vip', 'A1'];
        $contactPreferences = ['phone', 'email', 'both'];

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(3),
            'price' => fake()->randomFloat(2, 50, 2000),
            'condition' => fake()->randomElement($conditions),
            'storage' => fake()->randomElement($storages),
            'color' => fake()->randomElement($colors),
            'battery_health' => fake()->numberBetween(60, 100),
            'screen_condition' => fake()->randomElement(['Perfect', 'Good', 'Minor scratches', 'Cracked']),
            'body_condition' => fake()->randomElement(['Perfect', 'Good', 'Minor wear', 'Damaged']),
            'carrier' => fake()->randomElement($carriers),
            'contact_preference' => fake()->randomElement($contactPreferences),
            'status' => fake()->randomElement(['pending', 'active', 'sold', 'expired']),
            'expires_at' => fake()->dateTimeBetween('now', '+30 days'),
            'view_count' => fake()->numberBetween(0, 100),
        ];
    }

    /**
     * Indicate that the listing should be active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'expires_at' => now()->addDays(30),
        ]);
    }

    /**
     * Indicate that the listing should be pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the listing should be expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'expired',
            'expires_at' => now()->subDays(1),
        ]);
    }
}
