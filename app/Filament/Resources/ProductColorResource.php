<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductColorResource\Pages\CreateProductColor;
use App\Filament\Resources\ProductColorResource\Pages\EditProductColor;
use App\Filament\Resources\ProductColorResource\Pages\ListProductColors;
use App\Models\ProductColor;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ProductColorResource extends Resource
{
    protected static ?string $model = ProductColor::class;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedPaintBrush;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Catalogo';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                Section::make(heading: 'Cor')
                    ->schema(components: [
                        TextInput::make(name: 'name')
                            ->label(label: 'Nome')
                            ->required()
                            ->maxLength(length: 255)
                            ->unique(ignoreRecord: true),
                        ColorPicker::make(name: 'hex_code')
                            ->label(label: 'Código de cor')
                            ->required(),
                    ])
                    ->columns(columns: 1),
            ])->columns(columns: 1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(components: [
                TextColumn::make(name: 'name')
                    ->label(label: 'Nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make(name: 'hex_code')
                    ->label(label: 'Cor')
                    ->sortable(),
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

    public static function getPages(): array
    {
        return [
            'index' => ListProductColors::route(path: '/'),
            'create' => CreateProductColor::route(path: '/create'),
            'edit' => EditProductColor::route(path: '/{record}/edit'),
        ];
    }
}
