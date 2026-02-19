<?php

namespace App\Filament\Resources\ProductBadgeResource\Pages;

use App\Filament\Resources\ProductBadgeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductBadges extends ListRecords
{
    protected static string $resource = ProductBadgeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
