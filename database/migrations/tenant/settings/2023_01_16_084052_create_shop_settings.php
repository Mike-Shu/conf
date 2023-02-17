<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * @see https://github.com/spatie/laravel-settings#creating-settings-migrations
 */
class CreateShopSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->inGroup('shop', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('allow_shopping_cart', false);
        });
    }
}
