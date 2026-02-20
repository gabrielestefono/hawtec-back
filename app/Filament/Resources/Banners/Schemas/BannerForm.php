<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(heading: 'Informações do Banner')
                    ->schema([
                        TextInput::make(name: 'title')
                            ->label(label: 'Título')
                            ->required()
                            ->maxLength(255),
                        TextInput::make(name: 'subtitle')
                            ->label(label: 'Subtítulo')
                            ->maxLength(255),
                        TextInput::make(name: 'description')
                            ->label(label: 'Descrição')
                            ->maxLength(1000),
                        TextInput::make(name: 'button_label')
                            ->label(label: 'Texto do Botão')
                            ->maxLength(255),
                        TextInput::make(name: 'button_url')
                            ->label(label: 'URL do Botão')
                            ->url()
                            ->maxLength(255),
                        Toggle::make(name: 'is_active')
                            ->label(label: 'Ativo')
                            ->default(true),
                        TextInput::make(name: 'sort')
                            ->label(label: 'Ordem')
                            ->numeric()
                            ->default(0),
                        DateTimePicker::make(name: 'starts_at')
                            ->label(label: 'Começa em')
                            ->nullable(),
                        DateTimePicker::make(name: 'ends_at')
                            ->label(label: 'Termina em')
                            ->nullable(),
                    ])
                    ->columns(columns: 2),
                Section::make(heading: 'Imagens do Banner')
                    ->description(description: 'Adicione uma ou mais imagens. Apenas a última marcada como principal será exibida na API. Se nenhuma estiver marcada, qualquer imagem será usada.')
                    ->schema([
                        Repeater::make(name: 'images')
                            ->label(label: 'Imagens')
                            ->relationship()
                            ->schema([
                                FileUpload::make(name: 'path')
                                    ->label(label: 'Imagem')
                                    ->required()
                                    ->disk(name: 'public')
                                    ->directory(directory: 'banners')
                                    ->image()
                                    ->columnSpan(2),
                                TextInput::make(name: 'alt')
                                    ->label(label: 'Texto Alternativo')
                                    ->maxLength(255)
                                    ->columnSpan(2),
                                TextInput::make(name: 'sort')
                                    ->label(label: 'Ordem')
                                    ->numeric()
                                    ->default(0)
                                    ->columnSpan(1),
                                Toggle::make(name: 'is_primary')
                                    ->label(label: 'Principal')
                                    ->columnSpan(1),
                            ])
                            ->columns(columns: 2),
                    ]),
            ])->columns(columns: 1);
    }
}
