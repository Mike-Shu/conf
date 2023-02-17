<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * @see https://github.com/spatie/laravel-settings#creating-settings-migrations
 */
class CreateTenantSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->inGroup('tenant', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('title');
            $blueprint->add('title_in_browser');
            $blueprint->add('logo');
            $blueprint->add('home_page');
            $blueprint->add('menu');
            $blueprint->add('metrika');
            $blueprint->add('allow_registration', false);
        });
    }
}
