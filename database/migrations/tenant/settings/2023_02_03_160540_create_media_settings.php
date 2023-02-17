<?php

use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * @see https://github.com/spatie/laravel-settings#creating-settings-migrations
 */
class CreateMediaSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->inGroup('media', function (SettingsBlueprint $blueprint): void {
            $blueprint->add('main_media_likeable', false);
            $blueprint->add('main_media_unlikeable', false);

            $blueprint->add('form_header');
            $blueprint->add('form_comment');
            $blueprint->add('form_success_text');
            $blueprint->add('form_button_submit_text');
            $blueprint->add('form_panel_placeholder');
            $blueprint->add('form_accepted_file_types', ["image/*", "video/*"]);
            $blueprint->add('form_min_file_size', 10);
            $blueprint->add('form_max_file_size', 10240);
            $blueprint->add('form_allow_description', true);
            $blueprint->add('form_description_placeholder');
        });
    }
}
