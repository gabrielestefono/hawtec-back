<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BadgesRelationManager extends RelationManager
{
    protected static string $relationship = 'badges';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                Select::make(name: 'badge_type')
                    ->label(label: 'Tipo de Badge')
                    ->options(options: [
                        'new' => 'Novo',
                        'promotion' => 'Promoção',
                        'popular' => 'Popular',
                        'bestseller' => 'Bestseller',
                        'limited' => 'Edição Limitada',
                        'exclusive' => 'Exclusivo',
                    ])
                    ->required()
                    ->native(false),
                DateTimePicker::make(name: 'valid_from')
                    ->label(label: 'Válido de')
                    ->nullable(),
                DateTimePicker::make(name: 'valid_until')
                    ->label(label: 'Válido até')
                    ->nullable(),
            ])->columns(columns: 1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute(attribute: 'badge_type')
            ->columns(components: [
                TextColumn::make(name: 'badge_type')
                    ->label(label: 'Tipo')
                    ->formatStateUsing(callback: function (string $state) {
                        return match ($state) {
                            'new' => 'Novo',
                            'promotion' => 'Promoção',
                            'popular' => 'Popular',
                            'bestseller' => 'Bestseller',
                            'limited' => 'Edição Limitada',
                            'exclusive' => 'Exclusivo',
                            default => $state,
                        };
                    })
                    ->sortable(),
                TextColumn::make(name: 'valid_from')
                    ->label(label: 'Válido de')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make(name: 'valid_until')
                    ->label(label: 'Válido até')
                    ->dateTime('d/m/Y H:i')
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
