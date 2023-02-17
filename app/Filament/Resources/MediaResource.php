<?php

namespace App\Filament\Resources;

use App\Enums\MediaStatus;
use App\Enums\MediaType;
use App\Enums\VideoProvider;
use App\Enums\WalletReasonMain;
use App\Facades\AllWalletReason;
use App\Filament\Resources\MediaResource\Pages;
use App\Models\Media;
use Closure;
use Filament\Forms;
use Filament\Forms\Components;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static ?string $navigationIcon = 'heroicon-o-photograph';

    protected static ?int $navigationSort = 0;

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
        return __('Media');
    }

    /**
     * @param Form $form
     *
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Group::make([
                    Components\View::make('forms.components.media-preview')
                        ->columnSpan(fn($record): int => is_null($record->description) ? 4 : 1),

                    Components\Placeholder::make('description')
                        ->label(__('Description'))
                        ->content(fn($record): ?string => $record->description
                            ? trim(strip_tags($record->description))
                            : null)
                        ->hidden(fn($record): bool => is_null($record->description))
                        ->columnSpan(3),
                ])->columns(4),

                Components\Group::make([
                    Components\Select::make('status')
                        ->label(__('Status'))
                        ->options(MediaStatus::asSelectArray())
                        ->disablePlaceholderSelection()
                        ->reactive()
                        ->required(),

                    Components\Select::make('reason')
                        ->label(__('Reason'))
                        ->options(fn(Closure $get) => match ($get('status')) {
                            MediaStatus::ACCEPTED => AllWalletReason::asSelectArray(), // TODO: make a list of reasons through the settings
                            MediaStatus::REJECTED => AllWalletReason::asSelectArray([
                                WalletReasonMain::DISCREPANCY,
                            ]),
                            default => null,
                        })
                        ->placeholder("-")
                        ->required(),
                ])->columns(),

                Components\Group::make([
                    Components\Select::make('video_provider')
                        ->label(__('Video provider'))
                        ->options(VideoProvider::asSelectArray()) // TODO: make a list of reasons through the settings
                        ->placeholder("-")
                        ->required(),

                    Components\TextInput::make('video_identifier')
                        ->label(__('Video identifier'))
                        ->required(),
                ])->visible(fn($record): bool => $record->type === MediaType::VIDEO)
                    ->columns(),

                Components\Textarea::make('comment')
                    ->label(__('Comment'))
                    ->rows(2)
                    ->nullable(),
            ])->columns(1);
    }

    /**
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMedia::route('/'),
        ];
    }

    /**
     * @return string|null
     */
    protected static function getNavigationBadge(): ?string
    {
        return self::$model::accepted()->count();
    }
}
