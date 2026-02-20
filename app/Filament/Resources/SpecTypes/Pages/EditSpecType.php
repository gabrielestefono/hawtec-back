<?php

namespace App\Filament\Resources\SpecTypes\Pages;

use App\Filament\Resources\SpecTypes\SpecTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSpecType extends EditRecord
{
    protected static string $resource = SpecTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
