<?php

namespace App\Filament\Pages;

use App\Settings\MediaSettings;
use Closure;
use Filament\Forms\Components;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;

/**
 * @see https://github.com/spatie/laravel-settings#typing-properties
 */
class ManageMediaSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = MediaSettings::class;

    protected static ?int $navigationSort = 1;

    /**
     * @return string
     */
    public static function getNavigationGroup(): string
    {
        return __('Media');
    }

    /**
     * @return string
     */
    public static function getNavigationLabel(): string
    {
        return __('Media settings');
    }

    /**
     * @return string|Htmlable
     */
    protected function getHeading(): string|Htmlable
    {
        return __('Media settings');
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
            Components\Tabs::make('Heading')
                ->tabs([
                    Components\Tabs\Tab::make(__('Media settings main tab'))
                        ->schema([
                            Components\Toggle::make('main_media_likeable')
                                ->label(__('Items on the wall can be liked')),

                            Components\Toggle::make('main_media_unlikeable')
                                ->label(__('Items on the wall can be unliked')),
                        ]),

                    Components\Tabs\Tab::make(__('Media settings form tab'))
                        ->schema([
                            Components\TextInput::make('form_header')
                                ->label(__('Form header'))
                                ->columnSpan(['default' => 2, '2xl' => 1])
                                ->nullable(),

                            Components\RichEditor::make('form_comment')
                                ->label(__('Comment in the form'))
                                ->disableAllToolbarButtons()
                                ->enableToolbarButtons([
                                    'bold',
                                    'italic',
                                    'strike',
                                    'link',
                                    'redo',
                                    'undo',
                                ])
                                ->columnSpan(2)
                                ->nullable(),

                            Components\Group::make([
                                Components\TextInput::make('form_panel_placeholder')
                                    ->label(__('Placeholder for panel'))
                                    ->placeholder(__('Media upload form placeholder'))
                                    ->columnSpan(['default' => 2, '2xl' => 1])
                                    ->nullable(),
                            ])->columns()
                                ->columnSpan(2),

                            Components\TextInput::make('form_button_submit_text')
                                ->label(__('Text on the submit button'))
                                ->placeholder(__('Submit'))
                                ->columnSpan(['default' => 2, 'md' => 1])
                                ->nullable(),

                            Components\Group::make([
                                Components\TextInput::make('form_accepted_file_types')
                                    ->label(__('Accepted file types'))
                                    ->helperText(__('For example') . ": image/\*, video/\*")
                                    ->afterStateHydrated(function (Components\TextInput $component, $state) {
                                        if ($state) {
                                            $component->state(Arr::join($state, ", "));
                                        }
                                    })
                                    ->dehydrateStateUsing(function (Components\TextInput $component, $state) {
                                        if ($state) {
                                            $types = array_map("trim", explode(",", $state));
                                            $types = Arr::where($types, static function ($_item) {
                                                return !empty($_item);
                                            });

                                            if ($types) {
                                                $component->state(Arr::join($types, ", "));
                                                return $types;
                                            }

                                            $component->state(null);
                                        }

                                        return null;
                                    })
                                    ->nullable(),


                                Components\TextInput::make('form_min_file_size')
                                    ->label(__('Minimum file size'))
                                    ->nullable(),

                                Components\TextInput::make('form_max_file_size')
                                    ->label(__('Maximum file size'))
                                    ->nullable(),
                            ])->columns([
                                'md' => 2,
                                'xl' => 3,
                            ])
                                ->columnSpan(2),

                            Components\Toggle::make('form_allow_description')
                                ->label(__('Allow field with description'))
                                ->columnSpan(2)
                                ->reactive(),

                            Components\Group::make([
                                Components\TextInput::make('form_description_placeholder')
                                    ->label(__('Placeholder for description'))
                                    ->visible(fn(Closure $get): bool => $get('form_allow_description'))
                                    ->columnSpan(['default' => 2, '2xl' => 1])
                                    ->nullable(),
                            ])->columns()
                                ->columnSpan(2),

                            Components\Group::make([
                                Components\TextInput::make('form_success_text')
                                    ->label(__('Message after successful upload'))
                                    ->placeholder(__('File sent successfully'))
                                    ->columnSpan(['default' => 2, '2xl' => 1])
                                    ->nullable(),
                            ])->columns()
                                ->columnSpan(2),
                        ])->columns(['md' => 2]),
                ])->columnSpan([
                    'sm' => 4,
                    '2xl' => 3
                ]),
        ];
    }
}
