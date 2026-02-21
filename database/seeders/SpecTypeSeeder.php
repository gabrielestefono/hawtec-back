<?php

namespace Database\Seeders;

use App\Models\SpecType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specTypes = [
            ['name' => 'Cor', 'is_selectable' => true, 'display_order' => 1],
            ['name' => 'Tamanho', 'is_selectable' => true, 'display_order' => 2],
            ['name' => 'Material', 'is_selectable' => false, 'display_order' => 3],
            ['name' => 'Peso', 'is_selectable' => false, 'display_order' => 4],
            ['name' => 'Garantia', 'is_selectable' => false, 'display_order' => 5],
        ];

        foreach ($specTypes as $specType) {
            SpecType::create($specType);
        }
    }
}
