<?php

namespace App\Filament\Resources\StorageOptionResource\Pages;

use App\Filament\Resources\StorageOptionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStorageOption extends EditRecord
{
    protected static string $resource = StorageOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
