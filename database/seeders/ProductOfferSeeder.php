<?php

namespace Database\Seeders;

use App\Models\ProductOffer;
use App\Models\ProductVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductVariant::all()->each(function (ProductVariant $variant): void {
            // Each variant gets 1 offer
            $variant->offers()->create(
                ProductOffer::factory()->make([
                    'offer_price' => (int) ($variant->price * 0.8), // 20% discount
                ])->toArray()
            );
        });
    }
}
