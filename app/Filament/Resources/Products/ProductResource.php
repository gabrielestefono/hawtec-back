<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\RelationManagers\ImagesRelationManager;
use App\Filament\Resources\Products\RelationManagers\OffersRelationManager;
use App\Filament\Resources\Products\RelationManagers\ReviewsRelationManager;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Catalogo';

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure(schema: $schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure(table: $table);
    }

    public static function getRelations(): array
    {
        return [
            ImagesRelationManager::class,
            OffersRelationManager::class,
            ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route(path: '/'),
            'create' => CreateProduct::route(path: '/create'),
            'edit' => EditProduct::route(path: '/{record}/edit'),
        ];
    }
}
