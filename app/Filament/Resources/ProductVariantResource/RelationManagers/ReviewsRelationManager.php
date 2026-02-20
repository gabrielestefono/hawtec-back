<?php

namespace App\Filament\Resources\ProductVariantResource\RelationManagers;

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
                TextInput::make(name: 'title')
                    ->label(label: 'Titulo')
                    ->maxLength(length: 255),
                Textarea::make(name: 'comment')
                    ->label(label: 'Comentario')
                    ->rows(rows: 3),
            ])->columns(columns: 1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute(attribute: 'title')
            ->columns(components: [
                TextColumn::make(name: 'user.name')
                    ->label(label: 'Cliente')
                    ->searchable(),
                TextColumn::make(name: 'title')
                    ->label(label: 'Titulo')
                    ->searchable(),
                TextColumn::make(name: 'rating')
                    ->label(label: 'Nota')
                    ->badge()
                    ->color(color: fn (int $state): string => match ($state) {
                        1, 2 => 'danger',
                        3 => 'warning',
                        4, 5 => 'success',
                    })
                    ->sortable(),
                TextColumn::make(name: 'verified')
                    ->label(label: 'Verificada')
                    ->badge()
                    ->color(color: fn (bool $state): string => $state ? 'success' : 'gray')
                    ->formatStateUsing(callback: fn (bool $state): string => $state ? 'Sim' : 'Nao')
                    ->sortable(),
                TextColumn::make(name: 'created_at')
                    ->label(label: 'Data')
                    ->dateTime(format: 'd/m/Y H:i')
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
