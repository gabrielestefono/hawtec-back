<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\ProductCategory;
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
                        TextInput::make(name: 'slug')
                            ->label(label: 'Slug')
                            ->required()
                            ->maxLength(length: 255)
                            ->placeholder(placeholder: 'notebook-dell-inspiron')
                            ->helperText(text: 'URL amigável em minúsculas sem espaços.')
                            ->unique(ignoreRecord: true),
                        TextInput::make(name: 'brand')
                            ->label(label: 'Marca')
                            ->maxLength(length: 255),
                        Textarea::make(name: 'description')
                            ->label(label: 'Descrição curta')
                            ->rows(rows: 3),
                        Textarea::make(name: 'long_description')
                            ->label(label: 'Descrição longa')
                            ->rows(rows: 5),
                        Select::make(name: 'product_category_id')
                            ->label(label: 'Categoria')
                            ->options(
                                options: ProductCategory::query()
                                    ->pluck(column: 'name', key: 'id')
                                    ->toArray()
                            )
                            ->searchable()
                            ->required()
                            ->native(false),
                    ])
                    ->columns(columns: 1),
            ])->columns(columns: 1);
    }
}
