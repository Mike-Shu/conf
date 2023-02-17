<?php

namespace App\Filament\Pages;

use App\Models\Page;
use App\Settings\TenantSettings;
use App\UploadIO\UploadIO;
use Filament\Forms\Components;
use Filament\Pages\SettingsPage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\TemporaryUploadedFile;
use RyanChandler\FilamentNavigation\Models\Navigation;

/**
 * @see https://github.com/spatie/laravel-settings#typing-properties
 */
class ManageTenantSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $settings = TenantSettings::class;

    /**
     * @return string
     */
    public static function getNavigationLabel(): string
    {
        return __('Project Settings');
    }

    /**
     * @return string|Htmlable
     */
    protected function getHeading(): string|Htmlable
    {
        return __('Project Settings');
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
                            Components\Group::make([
                                Components\TextInput::make('title')
                                    ->label(__('Title'))
                                    ->maxLength(255)
                                    ->required(),

                                Components\TextInput::make('title_in_browser')
                                    ->label(__('Title in the browser'))
                                    ->maxLength(255)
                                    ->nullable(),

                                Components\Select::make('home_page')
                                    ->label(__('Home page'))
                                    ->options(Page::orderBy('title')->get()->pluck('title', 'id'))
                                    ->placeholder("-")
                                    ->nullable(),

                                Components\Select::make('menu')
                                    ->label(__('Menu'))
                                    ->options(Navigation::orderBy('id')->get()->pluck('title', 'handle'))
                                    ->placeholder("-")
                                    ->nullable(),

                                Components\TextInput::make('metrika')
                                    ->label(__('Metrika'))
                                    ->maxLength(255)
                                    ->nullable(),

                                Components\Toggle::make('allow_registration')
                                    ->label(__('Allow user registration')),
                            ]),

                            Components\Group::make([
                                Components\FileUpload::make('logo')
                                    ->label(__('Logo'))
                                    ->image()
                                    ->afterStateHydrated(function (Components\FileUpload $component, $state) {
                                        $component->state(blank($state)
                                            ? []
                                            : [((string)Str::uuid()) => $state]
                                        );
                                    })
                                    ->getUploadedFileUrlUsing(fn(string $file): ?string => $file)
                                    ->saveUploadedFileUsing(function (TemporaryUploadedFile $file) {
                                        return $this->saveUploadedFile($file);
                                    })
                                    ->nullable(),
                            ]),
                        ])->columns([
                            'xl' => 2,
                        ]),
                ])
                ->columnSpan(['lg' => 3]),

            Components\Group::make()
                ->schema([
                    Components\Card::make()
                        ->schema([
                            Components\Placeholder::make('created_at')
                                ->label(__('Created at'))
                                ->content(fn(): string => tenant()->created_at->diffForHumans()),
                        ]),
                ])
                ->columnSpan(['lg' => 1]),
        ];
    }

    /**
     * @param TemporaryUploadedFile $file
     *
     * @return string|null
     */
    private function saveUploadedFile(TemporaryUploadedFile $file): ?string
    {
        if (!$file->exists()) {
            return null;
        }

        if (config('filesystems.default') === "uploadio") {
            // Upload image to cloud.
            $cloud = app(UploadIO::class);

            try {
                $uploadedFileData = $cloud->upload($file->path());
                return $uploadedFileData['fileUrl'];
            } catch (FileNotFoundException | RequestException $e) {
                Log::error($e->getMessage());
            }
        } elseif (config('filesystems.default') === "local") {
            // Save image to local storage.
            return tenant_asset(Storage::disk('public')->putFile('logo', $file));
        } else {
            Log::error("An unsupported disk was specified for the file system. Check the FILESYSTEM_DISK setting.");
        }

        return null;
    }
}
