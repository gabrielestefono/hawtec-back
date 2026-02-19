<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductColor;
use App\Models\RamOption;
use App\Models\StorageOption;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'product_id' => Product::factory(),
            'sku' => fake()->unique()->bothify('SKU-########-####'),
            'color_id' => ProductColor::factory(),
            'storage_id' => StorageOption::factory(),
            'ram_id' => RamOption::factory(),
            'voltage' => fake()->optional(0.3)->randomElement(['110v', '220v', '110/220v']),
            'price' => fake()->randomFloat(nbMaxDecimals: 2, min: 99, max: 9999),
            'stock_quantity' => fake()->numberBetween(int1: 0, int2: 100),
        ];
    }

    public function outOfStock(): self
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => 0,
        ]);
    }

    public function inStock(int $quantity = 10): self
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => $quantity,
        ]);
    }
}
