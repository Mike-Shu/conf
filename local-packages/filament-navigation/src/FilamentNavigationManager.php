<?php

namespace RyanChandler\FilamentNavigation;

use App\Models\Page;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use RyanChandler\FilamentNavigation\Models\Navigation;

class FilamentNavigationManager
{
    use Macroable;

    private array $itemTypes = [];

    /**
     * @param string $title
     * @param array|Closure $fields
     *
     * @return $this
     */
    public function addItemType(string $title, array|Closure $fields = []): static
    {
        $this->itemTypes[Str::slug($title)] = [
            'title' => $title,
            'fields' => $fields,
        ];

        return $this;
    }

    /**
     * @param string $handle
     *
     * @return Navigation|null
     */
    public function get(string $handle): ?Navigation
    {
        return static::getModel()::firstWhere('handle', $handle);
    }

    /**
     * @return array
     */
    public function getItemTypes(): array
    {
        return array_merge([
            'external-link' => [
                'title' => __('filament-navigation::filament-navigation.attributes.external-link'),
                'fields' => [
                    TextInput::make('url')
                        ->label(__('filament-navigation::filament-navigation.attributes.url'))
                        ->url()
                        ->required(),

                    Select::make('target')
                        ->label(__('filament-navigation::filament-navigation.attributes.target'))
                        ->disablePlaceholderSelection()
                        ->options([
                            '' => __('filament-navigation::filament-navigation.select-options.same-tab'),
                            '_blank' => __('filament-navigation::filament-navigation.select-options.new-tab'),
                        ])
                        ->default(''),
                ],
            ],
            'page' => [
                'title' => __('Page'),
                'fields' => [
                    Select::make('slug')
                        ->label(__('Page'))
                        ->placeholder("-")
                        ->options(Page::orderBy('title')->get()->pluck('title', 'slug'))
                        ->searchable()
                        ->required(),
                ],
            ]
        ], $this->itemTypes);
    }

    /**
     * @return string
     */
    public static function getModel(): string
    {
        return config('filament-navigation.navigation_model') ?? Navigation::class;
    }
}
