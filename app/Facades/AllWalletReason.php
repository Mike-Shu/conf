<?php

namespace App\Facades;

use App\Services\AllWalletReasonService;
use Illuminate\Support\Facades\Facade;

/**
 * @package App\Facades
 * @method static array asSelectArray(array $onlyReasons = [])
 * @method static array getValues()
 * @method static string getDescription(?string $key)
 */
class AllWalletReason extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AllWalletReasonService::class;
    }
}
