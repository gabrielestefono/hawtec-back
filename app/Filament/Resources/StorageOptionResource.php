<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StorageOptionResource\Pages\CreateStorageOption;
use App\Filament\Resources\StorageOptionResource\Pages\EditStorageOption;
use App\Filament\Resources\StorageOptionResource\Pages\ListStorageOptions;
use App\Models\StorageOption;
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

class StorageOptionResource extends Resource
{
    protected static ?string $model = StorageOption::class;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Catalogo';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                Section::make(heading: 'Opção de Armazenamento')
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
            'index' => ListStorageOptions::route(path: '/'),
            'create' => CreateStorageOption::route(path: '/create'),
            'edit' => EditStorageOption::route(path: '/{record}/edit'),
        ];
    }
}
