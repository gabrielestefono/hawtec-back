<?php

namespace App\Filament\Resources\ProductVariantResource\RelationManagers;

use App\Models\ProductOffer;
use Closure;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

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
                    ->rule(rule: 'required_without:quantity_limit')
                    ->rule(function ($get) {
                        return function (string $attribute, mixed $value, Closure $fail) use ($get): void {
                            $owner = $this->getOwnerRecord();

                            if ($owner === null) {
                                return;
                            }

                            $minDate = Carbon::create(1, 1, 1, 0, 0, 0);
                            $maxDate = Carbon::create(9999, 12, 31, 23, 59, 59);
                            $startsAt = $get('starts_at') ? Carbon::parse($get('starts_at')) : $minDate;
                            $endsAt = $value ? Carbon::parse($value) : $maxDate;

                            $overlapExists = ProductOffer::query()
                                ->where('product_variant_id', $owner->id)
                                ->when($this->getRecord()?->id, fn (Builder $query): Builder => $query->where('id', '!=', $this->getRecord()->id))
                                ->where(function (Builder $query): void {
                                    $query
                                        ->whereNull('quantity_limit')
                                        ->orWhereColumn('quantity_sold', '<', 'quantity_limit');
                                })
                                ->whereRaw(
                                    'COALESCE(starts_at, ?) <= ? AND COALESCE(ends_at, ?) >= ?',
                                    [$minDate, $endsAt, $maxDate, $startsAt]
                                )
                                ->exists();

                            if ($overlapExists) {
                                $fail('Já existe uma oferta ativa nesse período para esta variante.');
                            }
                        };
                    }),
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
