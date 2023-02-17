<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class MediaSettings extends Settings
{
    public bool $main_media_likeable;
    public bool $main_media_unlikeable;

    public ?string $form_header;
    public ?string $form_comment;
    public ?string $form_success_text;
    public ?string $form_button_submit_text;
    public ?string $form_panel_placeholder;
    public ?array $form_accepted_file_types;
    public ?int $form_min_file_size;
    public ?int $form_max_file_size;
    public bool $form_allow_description;
    public ?string $form_description_placeholder;

    /**
     * @return string
     */
    public static function group(): string
    {
        return 'media';
    }
}
