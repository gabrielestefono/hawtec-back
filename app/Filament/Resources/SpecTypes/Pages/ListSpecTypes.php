<?php

namespace App\Filament\Resources\SpecTypes\Pages;

use App\Filament\Resources\SpecTypes\SpecTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSpecTypes extends ListRecords
{
    protected static string $resource = SpecTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
