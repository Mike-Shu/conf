<?php

namespace App\Filament\Resources\TimetableResource\RelationManagers;

use App\Enums\TimetableSlotGradient;
use App\Enums\TimetableSlotWidth;
use App\Filament\Actions;
use Closure;
use Exception;
use Filament\Forms;
use Filament\Forms\Components;
use Filament\Forms\Components\Actions\Action;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns;
use Filament\Tables\Filters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class SlotsRelationManager extends RelationManager
{
    protected static string $relationship = 'slots';

    protected static ?string $recordTitleAttribute = 'title';

    /**
     * @return string
     */
    public static function getTitle(): string
    {
        return __('Timeslots');
    }

    /**
     * @return string
     */
    public static function getModelLabel(): string
    {
        return Str::lower(__('Timeslot'));
    }

    /**
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('Title'))
                    ->rule('max:255')
                    ->required(),

                Forms\Components\RichEditor::make('description')
                    ->label(__('Description'))
                    ->disableToolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'bulletList',
                        'codeBlock',
                        'h2',
                        'h3',
                        'link',
                        'orderedList',
                    ])
                    ->mutateDehydratedStateUsing(function ($state) {
                        // Remove redundant line breaks.
                        if ($state) {
                            $state = strReplace("<br><br><br>", "<br><br>", $state);
                        }

                        return $state;
                    })
                    ->nullable(),

                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\DateTimePicker::make('start_datetime')
                            ->label(__('Date from'))
                            ->displayFormat("j M Y, H:i")
                            ->withoutSeconds()
                            ->required(),

                        Forms\Components\DateTimePicker::make('finish_datetime')
                            ->label(__('Date to'))
                            ->displayFormat("j M Y, H:i")
                            ->withoutSeconds()
                            ->required(),
                    ]),

                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Select::make('width')
                            ->label(__('Width'))
                            ->options(TimetableSlotWidth::asSelectArray())
                            ->placeholder("-")
                            ->nullable(),

                        Forms\Components\Select::make('gradient')
                            ->label(__('Gradient'))
                            ->options(TimetableSlotGradient::asSelectArray())
                            ->placeholder("-")
                            ->nullable(),
                    ]),

                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('link')
                            ->label(__('Link'))
                            ->url()
                            ->reactive()
                            ->suffixAction(fn(Closure $get): Action => Action::make('visit')
                                ->label(__(''))
                                ->icon('heroicon-s-external-link')
                                ->visible(filled($get('link')))
                                ->url(
                                    filled($get('link')) ? $get('link') : null,
                                    shouldOpenInNewTab: true,
                                ),
                            )
                            ->rule('max:2048')
                            ->requiredWith('link_anchor')
                            ->nullable(),

                        Forms\Components\TextInput::make('link_anchor')
                            ->label(__('Link anchor'))
                            ->rule('max:255')
                            ->nullable(),
                    ]),
            ])
            ->columns(1);
    }

    /**
     * @param Table $table
     * @return Table
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->description(fn($record): ?string => $record->description)
                    ->color(fn($record): ?string => is_null($record->deleted_at) ? null : "danger")
                    ->wrap()
                    ->limit()
                    ->searchable(),

                Columns\ViewColumn::make('start_finish_datetime')
                    ->view('tables.columns.timeslot-datetime')
                    ->label(__('Start/finish date'))
                    ->getStateUsing(fn($record): array => [
                        'start_datetime' => $record->start_datetime->translatedFormat("j M Y, H:i"),
                        'finish_datetime' => $record->finish_datetime->translatedFormat("j M Y, H:i"),
                    ])
                    ->toggleable(),

                Columns\TextColumn::make('width')
                    ->label(__('Width'))
                    ->getStateUsing(fn($record): ?string => $record->width
                        ? TimetableSlotWidth::getDescription($record->width)
                        : null)
                    ->toggleable(),

                Columns\TextColumn::make('gradient')
                    ->label(__('Gradient'))
                    ->getStateUsing(fn($record): ?string => $record->gradient
                        ? TimetableSlotGradient::getDescription($record->gradient)
                        : null)
                    ->toggleable(),

                Columns\ViewColumn::make('link')
                    ->view('tables.columns.timeslot-link')
                    ->label(__('Link'))
                    ->getStateUsing(fn($record): array => [
                        'link' => $record->link,
                        'anchor' => $record->link_anchor,
                    ])
                    ->toggleable(),
            ]);
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make(),
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getTableActions(): array
    {
        return [
            Actions\Tables\EditAction::make()
                ->label("")
                ->tooltip(__('Edit')),
            Actions\Tables\DeleteAction::make()
                ->label("")
                ->tooltip(__('Delete')),
            Actions\Tables\ForceDeleteAction::make()
                ->label("")
                ->tooltip(__('Delete permanently')),
            Actions\Tables\RestoreAction::make()
                ->label("")
                ->tooltip(__('Restore')),
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getTableBulkActions(): array
    {
        return [
            Actions\Tables\DeleteBulkAction::make(),
            Actions\Tables\RestoreBulkAction::make(),
            Actions\Tables\ForceDeleteBulkAction::make(),
        ];
    }

    /**
     * @return Builder
     * @throws Exception
     */
    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getTableFilters(): array
    {
        return [
            Filters\TrashedFilter::make(),

            Filters\Filter::make('date_from')
                ->form([
                    Components\DatePicker::make('start_date')
                        ->label(__('Date from'))
                        ->displayFormat("j M Y"),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        $data['start_date'],
                        fn(Builder $query, $dateTime): Builder => $query->whereDate('start_datetime', $dateTime),
                    );
                })
                ->indicateUsing(function (array $data): ?string {
                    if ($data['start_date']) {
                        return __('Date from') . ' "' . Carbon::parse($data['start_date'])
                                ->translatedFormat("j M Y, H:i") . '"';
                    }

                    return null;
                }),

            Filters\Filter::make('width')
                ->form([
                    Components\Select::make('width')
                        ->label(__('Width'))
                        ->placeholder("-")
                        ->options(TimetableSlotWidth::asSelectArray()),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        $data['width'],
                        fn(Builder $query, $width): Builder => $query->where('data->width', $width),
                    );
                })
                ->indicateUsing(function (array $data): ?string {
                    if ($data['width']) {
                        return __('Width') . ' "' . TimetableSlotWidth::getDescription($data['width']) . '"';
                    }

                    return null;
                }),

            Filters\Filter::make('gradient')
                ->form([
                    Components\Select::make('gradient')
                        ->label(__('Gradient'))
                        ->placeholder("-")
                        ->options(TimetableSlotGradient::asSelectArray()),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        $data['gradient'],
                        fn(Builder $query, $gradient): Builder => $query->where('data->gradient', $gradient),
                    );
                })
                ->indicateUsing(function (array $data): ?string {
                    if ($data['gradient']) {
                        return __('Gradient') . ' "' . TimetableSlotGradient::getDescription($data['gradient']) . '"';
                    }

                    return null;
                }),
        ];
    }
}
