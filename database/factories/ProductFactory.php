<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(nb: 3, asText: true);

        return [
            'name' => $name,
            'slug' => fake()->slug(),
            'description' => fake()->optional()->sentence(nbWords: 6),
            'long_description' => fake()->optional()->paragraphs(nb: 2, asText: true),
            'brand' => fake()->optional()->word(),
            'sku' => fake()->optional()->bothify(string: 'SKU-########'),
            'price' => fake()->randomFloat(nbMaxDecimals: 2, min: 99, max: 9999),
            'badge' => fake()->optional()->randomElement(array: ['novo', 'desconto', 'destaque']),
            'stock_quantity' => fake()->numberBetween(int1: 0, int2: 200),
            'colors' => [
                ['name' => 'Preto', 'value' => '#1a1a1a', 'available' => true],
                ['name' => 'Branco', 'value' => '#ffffff', 'available' => fake()->boolean()],
            ],
            'specs' => [
                ['label' => 'Peso', 'value' => fake()->numberBetween(100, 5000).' g'],
                ['label' => 'Garantia', 'value' => fake()->numberBetween(1, 5).' anos'],
            ],
        ];
    }
}
