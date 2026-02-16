<?php

namespace App\Filament\Resources\ProductCategories\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                Section::make(heading: 'Dados da categoria')
                    ->schema(components: [
                        TextInput::make(name: 'name')
                            ->label(label: 'Nome')
                            ->required()
                            ->maxLength(length: 255),
                        Textarea::make(name: 'description')
                            ->label(label: 'Descricao')
                            ->rows(rows: 3),
                        TextInput::make(name: 'icon')
                            ->label(label: 'Icone')
                            ->required()
                            ->maxLength(length: 120)
                            ->default(state: 'heroicon-o-tag')
                            ->placeholder(placeholder: 'heroicon-o-device-phone-mobile')
                            ->helperText(text: 'Use aliases Heroicon, por exemplo heroicon-o-device-phone-mobile.')
                            ->rule(rule: 'regex:/^heroicon-[a-z]-[a-z0-9-]+$/'),
                        TextInput::make(name: 'href')
                            ->label(label: 'Href interno')
                            ->required()
                            ->maxLength(length: 255)
                            ->placeholder(placeholder: '/celulares')
                            ->helperText(text: 'Somente caminhos internos iniciando com /.')
                            ->unique(ignoreRecord: true)
                            ->rule(rule: 'regex:/^\\/[a-z0-9\\/-]*$/'),
                    ])
                    ->columns(columns: 1),
            ])->columns(columns: 1);
    }
}
