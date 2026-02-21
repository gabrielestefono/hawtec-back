<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $label = $this->faker->words(2, true);

        return [
            'sku' => strtoupper(Str::random(8)),
            'variant_label' => $label,
            'slug' => Str::slug($label),
            'price' => $this->faker->numberBetween(1000, 500000),
            'stock_quantity' => $this->faker->numberBetween(5, 200),
        ];
    }
}
