<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShopProductResource\Pages;
use App\Filament\Resources\ShopProductResource\RelationManagers;
use App\Models\ShopProduct;
use App\Settings\ShopSettings;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShopProductResource extends Resource
{
    protected static ?string $model = ShopProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 0;

    /**
     * @return string
     */
    public static function getNavigationGroup(): string
    {
        return __('Shop');
    }

    /**
     * @return string
     */
    public static function getNavigationLabel(): string
    {
        return __('Products');
    }

    /**
     * @return string
     */
    public static function getModelLabel(): string
    {
        return __('Product');
    }

    /**
     * @return string
     */
    public static function getPluralModelLabel(): string
    {
        return __('Products');
    }

    /**
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShopProducts::route('/'),
            'create' => Pages\CreateShopProduct::route('/create'),
            'edit' => Pages\EditShopProduct::route('/{record}/edit'),
        ];
    }

    /**
     * @return Builder
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    /**
     * @return string|null
     */
    protected static function getNavigationBadge(): ?string
    {
        return self::$model::active()->count();
    }

    /**
     * @return string[]
     */
    public static function getRelations(): array
    {
        return [
            RelationManagers\PicturesRelationManager::class,
        ];
    }

    /**
     * @param Model $record
     * @return bool
     */
    public static function canEdit(Model $record): bool
    {
        return app(ShopSettings::class)->allow_shopping_cart === false;
    }
}
