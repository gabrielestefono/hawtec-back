<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RamOptionResource\Pages\CreateRamOption;
use App\Filament\Resources\RamOptionResource\Pages\EditRamOption;
use App\Filament\Resources\RamOptionResource\Pages\ListRamOptions;
use App\Models\RamOption;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class RamOptionResource extends Resource
{
    protected static ?string $model = RamOption::class;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedCircleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Catalogo';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                Section::make(heading: 'Opção de Memória RAM')
                    ->schema(components: [
                        TextInput::make(name: 'name')
                            ->label(label: 'Nome')
                            ->required()
                            ->maxLength(length: 255)
                            ->unique(ignoreRecord: true),
                        TextInput::make(name: 'value_gb')
                            ->label(label: 'Valor em GB')
                            ->required()
                            ->numeric()
                            ->minValue(value: 1),
                    ])
                    ->columns(columns: 1),
            ])->columns(columns: 1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(components: [
                TextColumn::make(name: 'name')
                    ->label(label: 'Nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make(name: 'value_gb')
                    ->label(label: 'GB')
                    ->sortable(),
            ])
            ->recordActions(actions: [
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions(actions: [
                BulkActionGroup::make(actions: [
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRamOptions::route(path: '/'),
            'create' => CreateRamOption::route(path: '/create'),
            'edit' => EditRamOption::route(path: '/{record}/edit'),
        ];
    }
}
