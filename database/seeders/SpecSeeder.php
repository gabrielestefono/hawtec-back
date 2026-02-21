<?php

namespace Database\Seeders;

use App\Models\ProductVariant;
use App\Models\Spec;
use App\Models\SpecType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specTypes = SpecType::all();

        ProductVariant::all()->each(function (ProductVariant $variant) use ($specTypes): void {
            // Each variant gets 5 specs (all spec types)
            foreach ($specTypes as $specType) {
                $value = fake()->word();
                
                $spec = Spec::firstOrCreate([
                    'spec_type_id' => $specType->id,
                    'value' => $value,
                ]);

                if (!$variant->specs()->where('spec_id', $spec->id)->exists()) {
                    $variant->specs()->attach($spec->id);
                }
            }
        });
    }
}
