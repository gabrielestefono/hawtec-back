<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                FileUpload::make(name: 'path')
                    ->label(label: 'Arquivo')
                    ->required()
                    ->disk(name: 'public')
                    ->directory(directory: 'products')
                    ->image(),
                TextInput::make(name: 'alt')
                    ->label(label: 'Texto alternativo')
                    ->maxLength(length: 255),
                TextInput::make(name: 'sort')
                    ->label(label: 'Ordem')
                    ->numeric()
                    ->default(state: 0)
                    ->required(),
                Toggle::make(name: 'is_primary')
                    ->label(label: 'Principal')
                    ->default(state: false),
            ])->columns(columns: 1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute(attribute: 'path')
            ->columns(components: [
                TextColumn::make(name: 'path')
                    ->label(label: 'Arquivo')
                    ->searchable(),
                TextColumn::make(name: 'alt')
                    ->label(label: 'Alt')
                    ->searchable(),
                TextColumn::make(name: 'sort')
                    ->label(label: 'Ordem')
                    ->sortable(),
                IconColumn::make(name: 'is_primary')
                    ->label(label: 'Principal')
                    ->boolean(),
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
