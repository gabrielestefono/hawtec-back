<?php

namespace App\Actions\Landing;

use App\Models\Product;
use Illuminate\Support\Collection;

class BestsellerAction
{
    public function handle(): Collection
    {
        return Product::query()->whereRaw('1=0')->get();
    }
}
