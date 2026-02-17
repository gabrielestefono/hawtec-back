<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Models\ProductOffer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OffersRelationManager extends RelationManager
{
    protected static string $relationship = 'offers';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                TextInput::make(name: 'offer_price')
                    ->label(label: 'Preco de oferta')
                    ->required()
                    ->numeric()
                    ->minValue(value: 0)
                    ->step(interval: 0.01)
                    ->prefix(label: 'R$'),
                DateTimePicker::make(name: 'starts_at')
                    ->label(label: 'Inicio')
                    ->nullable(),
                DateTimePicker::make(name: 'ends_at')
                    ->label(label: 'Fim')
                    ->nullable()
                    ->after('starts_at')
                    ->rule(rule: 'required_without:quantity_limit'),
                TextInput::make(name: 'quantity_limit')
                    ->label(label: 'Quantidade da oferta')
                    ->nullable()
                    ->numeric()
                    ->integer()
                    ->minValue(value: 1)
                    ->rule(rule: 'required_without:ends_at'),
                TextInput::make(name: 'quantity_sold')
                    ->label(label: 'Quantidade vendida')
                    ->numeric()
                    ->integer()
                    ->minValue(value: 0)
                    ->default(state: 0)
                    ->required(),
            ])->columns(columns: 1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute(attribute: 'offer_price')
            ->columns(components: [
                TextColumn::make(name: 'offer_price')
                    ->label(label: 'Preco oferta')
                    ->money(currency: 'BRL')
                    ->sortable(),
                TextColumn::make(name: 'starts_at')
                    ->label(label: 'Inicio')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make(name: 'ends_at')
                    ->label(label: 'Fim')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make(name: 'quantity_limit')
                    ->label(label: 'Qtd. oferta')
                    ->sortable(),
                TextColumn::make(name: 'quantity_sold')
                    ->label(label: 'Qtd. vendida')
                    ->sortable(),
                TextColumn::make(name: 'status')
                    ->label(label: 'Status')
                    ->badge()
                    ->state(fn (ProductOffer $record): string => $record->isActive() ? 'Ativa' : 'Encerrada')
                    ->color(fn (string $state): string => $state === 'Ativa' ? 'success' : 'gray'),
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
