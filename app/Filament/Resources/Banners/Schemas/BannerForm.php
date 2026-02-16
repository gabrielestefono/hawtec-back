<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                Section::make(heading: 'Dados do banner')
                    ->schema(components: [
                        TextInput::make(name: 'title')
                            ->label(label: 'Titulo')
                            ->required()
                            ->maxLength(length: 255),
                        TextInput::make(name: 'subtitle')
                            ->label(label: 'Subtitulo')
                            ->maxLength(length: 255),
                        Textarea::make(name: 'description')
                            ->label(label: 'Descricao')
                            ->rows(rows: 3),
                        TextInput::make(name: 'button_label')
                            ->label(label: 'Texto do botao')
                            ->maxLength(length: 255),
                        TextInput::make(name: 'button_url')
                            ->label(label: 'URL do botao')
                            ->url()
                            ->maxLength(length: 255),
                        TextInput::make(name: 'sort')
                            ->label(label: 'Ordem')
                            ->numeric()
                            ->default(state: 0)
                            ->required(),
                        Toggle::make(name: 'is_active')
                            ->label(label: 'Ativo')
                            ->default(state: true)
                            ->required(),
                        DateTimePicker::make(name: 'starts_at')
                            ->label(label: 'Inicio de exibicao'),
                        DateTimePicker::make(name: 'ends_at')
                            ->label(label: 'Fim de exibicao'),
                    ])
                    ->columns(columns: 1),
            ])->columns(columns: 1);
    }
}
