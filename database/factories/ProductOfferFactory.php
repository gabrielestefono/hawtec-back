<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductOffer>
 */
class ProductOfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $limit = fake()->optional()->numberBetween(int1: 10, int2: 200);
        $sold = $limit === null ? fake()->numberBetween(int1: 0, int2: 100) : fake()->numberBetween(int1: 0, int2: $limit);

        return [
            'product_id' => Product::factory(),
            'offer_price' => fake()->randomFloat(nbMaxDecimals: 2, min: 20, max: 5000),
            'starts_at' => fake()->optional()->dateTimeBetween(startDate: '-7 days', endDate: '+7 days'),
            'ends_at' => fake()->optional()->dateTimeBetween(startDate: '+8 days', endDate: '+30 days'),
            'quantity_limit' => $limit,
            'quantity_sold' => $sold,
        ];
    }
}
