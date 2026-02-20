<?php

namespace App\Filament\Resources\ProductVariantResource\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SpecsRelationManager extends RelationManager
{
    protected static string $relationship = 'specs';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('spec_type_id')
                    ->label('Tipo de Especificação')
                    ->relationship('specType', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->native(false),
                TextInput::make('value')
                    ->label('Valor')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('value')
            ->columns([
                TextColumn::make('specType.name')
                    ->label('Tipo')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('value')
                    ->label('Valor')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Adicionar Especificação')
                    ->color('success'),
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
