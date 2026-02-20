<?php

namespace App\Filament\Resources\SpecTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SpecTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tipo de Especificação')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                        Toggle::make('is_selectable')
                            ->label('Selecionável no frontend')
                            ->default(true)
                            ->helperText('Define se este tipo aparece como opção selecionável'),
                        TextInput::make('display_order')
                            ->label('Ordem de exibição')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                    ])
                    ->columns(2),
            ]);
    }
}
