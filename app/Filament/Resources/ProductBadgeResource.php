<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductBadgeResource\Pages\CreateProductBadge;
use App\Filament\Resources\ProductBadgeResource\Pages\EditProductBadge;
use App\Filament\Resources\ProductBadgeResource\Pages\ListProductBadges;
use App\Models\ProductBadge;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ProductBadgeResource extends Resource
{
    protected static ?string $model = ProductBadge::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $recordTitleAttribute = 'badge_type';

    protected static string|UnitEnum|null $navigationGroup = 'Catalogo';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components(components: [
                Section::make(heading: 'Badge do Produto')
                    ->schema(components: [
                        Select::make(name: 'product_id')
                            ->label(label: 'Produto')
                            ->relationship(name: 'product', titleAttribute: 'name')
                            ->required()
                            ->searchable()
                            ->native(false),
                        Select::make(name: 'badge_type')
                            ->label(label: 'Tipo de Badge')
                            ->options(options: [
                                'new' => 'Novo',
                                'promotion' => 'Promoção',
                                'popular' => 'Popular',
                                'bestseller' => 'Bestseller',
                                'limited' => 'Edição Limitada',
                                'exclusive' => 'Exclusivo',
                            ])
                            ->required()
                            ->native(false),
                        DateTimePicker::make(name: 'valid_from')
                            ->label(label: 'Válido de')
                            ->nullable(),
                        DateTimePicker::make(name: 'valid_until')
                            ->label(label: 'Válido até')
                            ->nullable(),
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
                TextColumn::make(name: 'badge_type')
                    ->label(label: 'Tipo')
                    ->formatStateUsing(callback: function (string $state) {
                        return match ($state) {
                            'new' => 'Novo',
                            'promotion' => 'Promoção',
                            'popular' => 'Popular',
                            'bestseller' => 'Bestseller',
                            'limited' => 'Edição Limitada',
                            'exclusive' => 'Exclusivo',
                            default => $state,
                        };
                    })
                    ->sortable(),
                TextColumn::make(name: 'valid_from')
                    ->label(label: 'Válido de')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make(name: 'valid_until')
                    ->label(label: 'Válido até')
                    ->dateTime('d/m/Y H:i')
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
            'index' => ListProductBadges::route(path: '/'),
            'create' => CreateProductBadge::route(path: '/create'),
            'edit' => EditProductBadge::route(path: '/{record}/edit'),
        ];
    }
}
