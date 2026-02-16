<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                Section::make(heading: 'Dados do produto')
                    ->schema(components: [
                        TextInput::make(name: 'name')
                            ->label(label: 'Nome')
                            ->required()
                            ->maxLength(length: 255),
                        Textarea::make(name: 'description')
                            ->label(label: 'Descricao')
                            ->rows(rows: 3),
                        TextInput::make(name: 'price')
                            ->label(label: 'Preco')
                            ->required()
                            ->numeric()
                            ->minValue(value: 0)
                            ->step(interval: 0.01)
                            ->prefix(label: 'R$'),
                        Select::make(name: 'badge')
                            ->label(label: 'Badge')
                            ->options(options: [
                                'new' => 'new',
                                'sale' => 'sale',
                                'hot' => 'hot',
                            ])
                            ->searchable()
                            ->nullable(),
                        TextInput::make(name: 'stock_quantity')
                            ->label(label: 'Quantidade em estoque')
                            ->numeric()
                            ->integer()
                            ->minValue(value: 0)
                            ->default(state: 0)
                            ->required(),
                    ])
                    ->columns(columns: 1),
            ])->columns(columns: 1);
    }
}
