<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                Select::make(name: 'user_id')
                    ->label(label: 'Cliente')
                    ->relationship(name: 'user', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make(name: 'rating')
                    ->label(label: 'Nota')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->minValue(value: 1)
                    ->maxValue(value: 5),
                Textarea::make(name: 'comment')
                    ->label(label: 'Comentario')
                    ->rows(rows: 3),
            ])->columns(columns: 1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute(attribute: 'id')
            ->columns(components: [
                TextColumn::make(name: 'user.name')
                    ->label(label: 'Cliente')
                    ->searchable(),
                TextColumn::make(name: 'rating')
                    ->label(label: 'Nota')
                    ->sortable(),
                TextColumn::make(name: 'comment')
                    ->label(label: 'Comentario')
                    ->limit(length: 50),
                TextColumn::make(name: 'updated_at')
                    ->label(label: 'Atualizado em')
                    ->since()
                    ->sortable(),
            ])
            ->headerActions(actions: [])
            ->recordActions(actions: [
                DeleteAction::make(),
            ])
            ->toolbarActions(actions: [
                BulkActionGroup::make(actions: [
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
