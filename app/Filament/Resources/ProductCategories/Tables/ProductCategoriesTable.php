<?php

namespace App\Filament\Resources\ProductCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Descricao')
                    ->limit(50),
                IconColumn::make('icon')
                    ->label('Icone')
                    ->icon(fn (?string $state): string => $state ?: 'heroicon-o-tag'),
                TextColumn::make('href')
                    ->label('Href')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('products_count')
                    ->label('Produtos')
                    ->counts('products')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'success' : 'gray'),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (DeleteAction $action, Model $record) {
                        /** @var \App\Models\ProductCategory $record */
                        if ($record->products()->count() > 0) {
                            Notification::make()
                                ->danger()
                                ->title('Não é possível excluir')
                                ->body('Esta categoria possui produtos vinculados. Remova ou transfira os produtos primeiro.')
                                ->send();

                            $action->cancel();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (DeleteBulkAction $action, Collection $records) {
                            /** @var Collection<\App\Models\ProductCategory> $records */
                            $categoriesWithProducts = $records->filter(function (Model $record) {
                                /** @var \App\Models\ProductCategory $record */
                                return $record->products()->count() > 0;
                            });

                            if ($categoriesWithProducts->isNotEmpty()) {
                                Notification::make()
                                    ->danger()
                                    ->title('Não é possível excluir')
                                    ->body('Algumas categorias possuem produtos vinculados. Remova ou transfira os produtos primeiro.')
                                    ->send();

                                $action->cancel();
                            }
                        }),
                ]),
            ]);
    }
}
