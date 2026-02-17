<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with('offers', 'category'))
            ->columns(components: [
                TextColumn::make(name: 'name')
                    ->label(label: 'Nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make(name: 'category.name')
                    ->label(label: 'Categoria')
                    ->searchable()
                    ->sortable(),
                TextColumn::make(name: 'price')
                    ->label(label: 'Preco base')
                    ->money(currency: 'BRL')
                    ->sortable(),
                TextColumn::make(name: 'current_price')
                    ->label(label: 'Preco atual')
                    ->money(currency: 'BRL'),
                TextColumn::make(name: 'badge')
                    ->label(label: 'Badge')
                    ->badge()
                    ->sortable(),
                TextColumn::make(name: 'stock_quantity')
                    ->label(label: 'Estoque')
                    ->sortable(),
                TextColumn::make(name: 'images_count')
                    ->label(label: 'Imagens')
                    ->counts(relationships: 'images'),
                TextColumn::make(name: 'reviews_count')
                    ->label(label: 'Avaliacoes')
                    ->counts(relationships: 'reviews'),
                TextColumn::make(name: 'offers_count')
                    ->label(label: 'Ofertas')
                    ->counts(relationships: 'offers'),
                TextColumn::make(name: 'updated_at')
                    ->label(label: 'Atualizado em')
                    ->since()
                    ->sortable(),
            ])
            ->filters(filters: [
                //
            ])
            ->recordActions(actions: [
                EditAction::make(),
            ])
            ->toolbarActions(actions: [
                BulkActionGroup::make(actions: [
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
