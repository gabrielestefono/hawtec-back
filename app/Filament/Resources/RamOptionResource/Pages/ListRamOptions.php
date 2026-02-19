<?php

namespace App\Filament\Resources\RamOptionResource\Pages;

use App\Filament\Resources\RamOptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRamOptions extends ListRecords
{
    protected static string $resource = RamOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
