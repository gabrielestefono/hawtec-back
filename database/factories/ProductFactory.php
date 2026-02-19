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
            'product_category_id' => 1,
        ];
    }

    public function withVariants(int $count = 3): self
    {
        return $this->afterCreating(function ($product) use ($count) {
            $product->variants()->createMany(
                ProductVariantFactory::new()->count($count)->make()->toArray()
            );
        });
    }

    public function withBadges(int $count = 1): self
    {
        return $this->afterCreating(function ($product) use ($count) {
            $product->badges()->createMany(
                ProductBadgeFactory::new()->count($count)->make()->toArray()
            );
        });
    }
}
