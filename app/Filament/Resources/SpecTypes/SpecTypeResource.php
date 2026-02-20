<?php

namespace App\Filament\Resources\SpecTypes;

use App\Filament\Resources\SpecTypes\Pages\CreateSpecType;
use App\Filament\Resources\SpecTypes\Pages\EditSpecType;
use App\Filament\Resources\SpecTypes\Pages\ListSpecTypes;
use App\Filament\Resources\SpecTypes\Schemas\SpecTypeForm;
use App\Filament\Resources\SpecTypes\Tables\SpecTypesTable;
use App\Models\SpecType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SpecTypeResource extends Resource
{
    protected static ?string $model = SpecType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Tipos de Especificações';

    protected static ?string $modelLabel = 'Tipo de Especificação';

    protected static ?string $pluralModelLabel = 'Tipos de Especificações';

    protected static string|UnitEnum|null $navigationGroup = 'Catalogo';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return SpecTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SpecTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSpecTypes::route('/'),
            'create' => CreateSpecType::route('/create'),
            'edit' => EditSpecType::route('/{record}/edit'),
        ];
    }
}
