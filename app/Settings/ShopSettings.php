<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ShopSettings extends Settings
{
    public bool $allow_shopping_cart;

    /**
     * @return string
     */
    public static function group(): string
    {
        return 'shop';
    }
}
