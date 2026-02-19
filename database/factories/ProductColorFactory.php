<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductColor>
 */
class ProductColorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $colors = [
            ['name' => 'Preto', 'hex' => '#000000'],
            ['name' => 'Branco', 'hex' => '#FFFFFF'],
            ['name' => 'Vermelho', 'hex' => '#FF0000'],
            ['name' => 'Azul', 'hex' => '#0000FF'],
            ['name' => 'Cinza', 'hex' => '#808080'],
            ['name' => 'Prata', 'hex' => '#C0C0C0'],
            ['name' => 'Dourado', 'hex' => '#FFD700'],
            ['name' => 'Rosa', 'hex' => '#FFC0CB'],
        ];

        $color = fake()->randomElement($colors);

        return [
            'name' => $color['name'],
            'hex_code' => $color['hex'],
        ];
    }
}
