<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductSpec>
 */
class ProductSpecFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $keys = ['Processor', 'RAM', 'Storage', 'Display', 'Battery', 'Weight', 'Warranty'];

        return [
            'product_id' => Product::factory(),
            'key' => $this->faker->randomElement($keys),
            'value' => $this->faker->word(),
        ];
    }
}
