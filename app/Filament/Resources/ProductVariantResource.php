<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductVariantResource\Pages\CreateProductVariant;
use App\Filament\Resources\ProductVariantResource\Pages\EditProductVariant;
use App\Filament\Resources\ProductVariantResource\Pages\ListProductVariants;
use App\Filament\Resources\ProductVariantResource\RelationManagers\OffersRelationManager;
use App\Filament\Resources\ProductVariantResource\RelationManagers\ReviewsRelationManager;
use App\Filament\Resources\ProductVariantResource\RelationManagers\SpecsRelationManager;
use App\Models\ProductVariant;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ProductVariantResource extends Resource
{
    protected static ?string $model = ProductVariant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog;

    protected static ?string $recordTitleAttribute = 'sku';

    protected static string|UnitEnum|null $navigationGroup = 'Catalogo';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                Section::make(heading: 'Variante do Produto')
                    ->schema(components: [
                        Select::make(name: 'product_id')
                            ->label(label: 'Produto')
                            ->relationship(name: 'product', titleAttribute: 'name')
                            ->required()
                            ->searchable()
                            ->native(false),
                        TextInput::make(name: 'sku')
                            ->label(label: 'SKU')
                            ->required()
                            ->maxLength(length: 255)
                            ->unique(ignoreRecord: true),
                        TextInput::make(name: 'slug')
                            ->label(label: 'Slug')
                            ->helperText(text: 'URL-friendly identifier. Ex: xiaomi-redmi-128gb')
                            ->required()
                            ->maxLength(length: 255)
                            ->unique(ignoreRecord: true),
                        TextInput::make(name: 'variant_label')
                            ->label(label: 'Label da Variante')
                            ->helperText(text: 'Ex: 128GB • 8GB RAM ou Azul • Pequeno')
                            ->required()
                            ->maxLength(length: 255),
                        TextInput::make(name: 'price')
                            ->label(label: 'Preço')
                            ->required()
                            ->numeric()
                            ->minValue(value: 0)
                            ->step(interval: 0.01)
                            ->prefix(label: 'R$'),
                        TextInput::make(name: 'stock_quantity')
                            ->label(label: 'Quantidade em estoque')
                            ->numeric()
                            ->integer()
                            ->minValue(value: 0)
                            ->default(state: 0)
                            ->required(),
                    ])
                    ->columns(columns: 2),
            ])->columns(columns: 1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(components: [
                TextColumn::make(name: 'product.name')
                    ->label(label: 'Produto')
                    ->searchable()
                    ->sortable(),
                TextColumn::make(name: 'variant_label')
                    ->label(label: 'Label')
                    ->searchable()
                    ->sortable(),
                TextColumn::make(name: 'slug')
                    ->label(label: 'Slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make(name: 'sku')
                    ->label(label: 'SKU')
                    ->searchable()
                    ->sortable(),
                TextColumn::make(name: 'price')
                    ->label(label: 'Preço')
                    ->money(currency: 'BRL')
                    ->sortable(),
                TextColumn::make(name: 'stock_quantity')
                    ->label(label: 'Estoque')
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
            'index' => ListProductVariants::route(path: '/'),
            'create' => CreateProductVariant::route(path: '/create'),
            'edit' => EditProductVariant::route(path: '/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            OffersRelationManager::class,
            SpecsRelationManager::class,
            ReviewsRelationManager::class,
        ];
    }
}
