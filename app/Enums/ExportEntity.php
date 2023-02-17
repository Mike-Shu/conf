<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class ExportEntity extends Enum implements LocalizedEnum
{
    public const USERS = 'users';
    public const SHOP_STOCK = 'shop_stock';
    public const SHOP_ORDERS = 'shop_orders';
}
