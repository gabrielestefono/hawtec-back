<?php

namespace App\Actions\Landing;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Collection;

class CategoryAction
{
    public function handle(): Collection
    {
        return ProductCategory::query()
            ->withCount(relations: 'products')
            ->orderBy(column: 'name')
            ->get();
    }
}
