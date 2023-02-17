<?php

namespace App\Filament\Pages;

use App\Settings\ShopSettings;
use Filament\Forms\Components;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;

/**
 * @see https://github.com/spatie/laravel-settings#typing-properties
 */
class ManageShopSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = ShopSettings::class;

    protected static ?int $navigationSort = 2;

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
        return __('Shop settings');
    }

    /**
     * @return string|Htmlable
     */
    protected function getHeading(): string|Htmlable
    {
        return __('Shop settings');
    }

    /**
     * @return array
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->makeForm()
                ->schema($this->getFormSchema())
                ->statePath('data')
                ->columns(4)
                ->inlineLabel(config('filament.layout.forms.have_inline_labels')),
        ];
    }

    /**
     * @return array
     */
    protected function getFormSchema(): array
    {
        return [
            Components\Group::make()
                ->schema([
                    Components\Card::make()
                        ->schema([
                            Components\Toggle::make('allow_shopping_cart')
                                ->label(__('Allow shopping cart')),
                        ]),
                ])
                ->columnSpan(['lg' => 3]),

            Components\Group::make()
                ->schema([
                    //
                ])
                ->columnSpan(['lg' => 1]),
        ];
    }
}
