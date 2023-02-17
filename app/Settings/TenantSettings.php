<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class TenantSettings extends Settings
{
    public ?string $title;
    public ?string $title_in_browser;
    public ?string $logo;
    public ?string $home_page;
    public ?string $menu;
    public ?string $metrika;
    public bool $allow_registration;

    /**
     * @return string
     */
    public static function group(): string
    {
        return 'tenant';
    }
}
