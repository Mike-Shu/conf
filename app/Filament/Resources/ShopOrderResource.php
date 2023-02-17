<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShopOrderResource\Pages;
use App\Models\ShopOrder;
use Filament\Resources\Resource;

class ShopOrderResource extends Resource
{
    protected static ?string $model = ShopOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?int $navigationSort = 1;

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
        return __('Orders');
    }

    /**
     * @return string
     */
    public static function getPluralModelLabel(): string
    {
        return __('Orders');
    }

    /**
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShopOrders::route('/'),
        ];
    }

    /**
     * @return string|null
     */
    protected static function getNavigationBadge(): ?string
    {
        return self::$model::count();
    }
}
