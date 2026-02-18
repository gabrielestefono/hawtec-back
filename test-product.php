<?php

use App\Models\Image;
use App\Models\Product;
use App\Models\ProductReview;

$product = Product::factory()
    ->has(ProductReview::factory()->count(5))
    ->has(Image::factory()->count(3))
    ->create();

echo 'Product created: '.$product->name.' (slug: '.$product->slug.")\n";
echo 'Reviews: '.$product->reviews()->count()."\n";
echo 'Images: '.$product->images()->count()."\n";
