<?php

namespace App\Filament\Resources\RamOptionResource\Pages;

use App\Filament\Resources\RamOptionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRamOption extends EditRecord
{
    protected static string $resource = RamOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
