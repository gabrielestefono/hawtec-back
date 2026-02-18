<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::create([
            'name' => 'HawTec Pro X1 - Headphone Bluetooth Premium',
            'slug' => 'hawtec-pro-x1-premium',
            'description' => 'Headphone over-ear com cancelamento de ruído ativo',
            'long_description' => 'O HawTec Pro X1 redefine a experiência sonora com sua tecnologia de cancelamento de ruído ativo de última geração. Equipado com drivers de 40mm de neodímio puro, oferece um som cristalino com graves profundos e agudos nítidos. A bateria de longa duração garante até 30 horas de uso contínuo, enquanto o design ergonômico permite conforto o dia todo.',
            'brand' => 'HawTec',
            'sku' => 'HAW-PRX1-PREMIUM',
            'price' => 1299.90,
            'badge' => 'desconto',
            'stock_quantity' => 23,
            'colors' => [
                ['name' => 'Preto', 'value' => '#1a1a1a', 'available' => true],
                ['name' => 'Prata', 'value' => '#c0c0c0', 'available' => true],
                ['name' => 'Ouro', 'value' => '#ffd700', 'available' => false],
            ],
            'specs' => [
                ['label' => 'Driver', 'value' => '40mm Neodímio'],
                ['label' => 'Impedância', 'value' => '32Ω'],
                ['label' => 'Frequência', 'value' => '20Hz - 20kHz'],
                ['label' => 'Peso', 'value' => '250g'],
                ['label' => 'Bateria', 'value' => '30 horas'],
                ['label' => 'Conectividade', 'value' => 'Bluetooth 5.0, 3.5mm Jack'],
            ],
        ]);

        // Create images
        Image::create([
            'imageable_type' => Product::class,
            'imageable_id' => $product->id,
            'path' => '/images/product-headphone-1.jpg',
            'alt' => 'HawTec Pro X1 - Vista frontal',
            'sort' => 1,
            'is_primary' => true,
        ]);

        Image::create([
            'imageable_type' => Product::class,
            'imageable_id' => $product->id,
            'path' => '/images/product-headphone-2.jpg',
            'alt' => 'HawTec Pro X1 - Vista lateral',
            'sort' => 2,
            'is_primary' => false,
        ]);

        Image::create([
            'imageable_type' => Product::class,
            'imageable_id' => $product->id,
            'path' => '/images/product-headphone-3.jpg',
            'alt' => 'HawTec Pro X1 - Detalhe dos controles',
            'sort' => 3,
            'is_primary' => false,
        ]);

        // Create reviews with different users
        $reviews = [
            [
                'rating' => 5,
                'title' => 'Melhor headphone que já tive',
                'content' => 'Som absurdo, conforto excepcional e a bateria dura muito mesmo. Já tenho há 3 meses e recomendo muito!',
                'verified' => true,
            ],
            [
                'rating' => 5,
                'title' => 'Excelente qualidade de som',
                'content' => 'O cancelamento de ruído funciona muito bem, ideal para trabalhar em home office ou durante viagens.',
                'verified' => true,
            ],
            [
                'rating' => 4,
                'title' => 'Bom custo-benefício',
                'content' => 'Realmente é um produto de qualidade. O único ponto negativo é que poderia ter mais cores disponíveis.',
                'verified' => true,
            ],
            [
                'rating' => 5,
                'title' => 'Superou as expectativas',
                'content' => 'Comprei depois de ver vários reviews e não me arrependo. Produto premium com preço justo.',
                'verified' => true,
            ],
            [
                'rating' => 4,
                'title' => 'Muito bom',
                'content' => 'Chegou rápido, bem embalado e em perfeito estado. Já estou usando todos os dias.',
                'verified' => false,
            ],
        ];

        foreach ($reviews as $review) {
            $user = User::factory()->create();
            ProductReview::create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'rating' => $review['rating'],
                'title' => $review['title'],
                'comment' => $review['content'],
                'verified' => $review['verified'],
            ]);
        }

        $this->command->info("Premium product created: {$product->name} (slug: {$product->slug})");
    }
}
