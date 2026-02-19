<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Models\ProductColor;
use App\Models\RamOption;
use App\Models\StorageOption;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                TextInput::make(name: 'sku')
                    ->label(label: 'SKU')
                    ->required()
                    ->maxLength(length: 255)
                    ->unique(ignoreRecord: true),
                Select::make(name: 'color_id')
                    ->label(label: 'Cor')
                    ->options(
                        options: ProductColor::query()
                            ->pluck(column: 'name', key: 'id')
                            ->toArray()
                    )
                    ->required()
                    ->searchable()
                    ->native(false),
                Select::make(name: 'storage_id')
                    ->label(label: 'Armazenamento')
                    ->options(
                        options: StorageOption::query()
                            ->pluck(column: 'name', key: 'id')
                            ->toArray()
                    )
                    ->searchable()
                    ->native(false),
                Select::make(name: 'ram_id')
                    ->label(label: 'Memória RAM')
                    ->options(
                        options: RamOption::query()
                            ->pluck(column: 'name', key: 'id')
                            ->toArray()
                    )
                    ->searchable()
                    ->native(false),
                Select::make(name: 'voltage')
                    ->label(label: 'Voltagem')
                    ->options(options: [
                        '110v' => '110V',
                        '220v' => '220V',
                        '110/220v' => '110V/220V',
                    ])
                    ->required()
                    ->native(false),
                TextInput::make(name: 'price')
                    ->label(label: 'Preço')
                    ->required()
                    ->numeric()
                    ->minValue(value: 0)
                    ->step(interval: 0.01)
                    ->prefix(label: 'R$'),
                TextInput::make(name: 'stock_quantity')
                    ->label(label: 'Quantidade em estoque')
                    ->numeric()
                    ->integer()
                    ->minValue(value: 0)
                    ->default(state: 0)
                    ->required(),
            ])->columns(columns: 2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute(attribute: 'sku')
            ->columns(components: [
                TextColumn::make(name: 'sku')
                    ->label(label: 'SKU')
                    ->searchable()
                    ->sortable(),
                TextColumn::make(name: 'colorOption.name')
                    ->label(label: 'Cor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make(name: 'storageOption.name')
                    ->label(label: 'Armazenamento')
                    ->searchable()
                    ->sortable(),
                TextColumn::make(name: 'ramOption.name')
                    ->label(label: 'RAM')
                    ->searchable()
                    ->sortable(),
                TextColumn::make(name: 'voltage')
                    ->label(label: 'Voltagem')
                    ->sortable(),
                TextColumn::make(name: 'price')
                    ->label(label: 'Preço')
                    ->money(currency: 'BRL')
                    ->sortable(),
                TextColumn::make(name: 'stock_quantity')
                    ->label(label: 'Estoque')
                    ->sortable(),
            ])
            ->headerActions(actions: [
                CreateAction::make(),
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
}
