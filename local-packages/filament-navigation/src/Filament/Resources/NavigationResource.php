<?php

namespace RyanChandler\FilamentNavigation\Filament\Resources;

use Closure;
use App\Filament\Actions;
use Exception;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;
use RyanChandler\FilamentNavigation\Models\Navigation;

class NavigationResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-menu';

    protected static bool $showTimestamps = true;

    /**
     * @return string
     */
    public static function getNavigationLabel(): string
    {
        return __('Menus');
    }

    /**
     * @return string
     */
    public static function getModelLabel(): string
    {
        return __('Menu');
    }

    /**
     * @return string
     */
    public static function getPluralModelLabel(): string
    {
        return __('Menus');
    }

    /**
     * @param bool $condition
     */
    public static function disableTimestamps(bool $condition = true): void
    {
        static::$showTimestamps = !$condition;
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
                Card::make([
                    Grid::make()
                        ->schema([
                            TextInput::make('title')
                                ->label(__('filament-navigation::filament-navigation.attributes.title'))
                                ->reactive()
                                ->afterStateUpdated(function (?string $state, Closure $set) {
                                    if (!$state) {
                                        return;
                                    }

                                    $set('handle', Str::slug($state));
                                })
                                ->maxLength(255)
                                ->required()
                                ->columnSpan([
                                    'sm' => 2,
                                    '2xl' => 1,
                                ]),

                            TextInput::make('handle')
                                ->label(__('filament-navigation::filament-navigation.attributes.handle'))
                                ->unique(column: 'handle', ignoreRecord: true)
                                ->maxLength(255)
                                ->required()
                                ->columnSpan([
                                    'sm' => 2,
                                    '2xl' => 1,
                                ]),
                        ])->columns(),

                    ViewField::make('items')
                        ->label(__('filament-navigation::filament-navigation.attributes.items'))
                        ->default([])
                        ->required()
                        ->view('filament-navigation::navigation-builder'),
                ])->columnSpan([
                    12,
                    'lg' => 8,
                ]),

                Group::make([
                    Card::make([
                        Placeholder::make('created_at')
                            ->label(__('filament-navigation::filament-navigation.attributes.created_at'))
                            ->content(fn($record): string => $record->created_at->diffForHumans()),

                        Placeholder::make('updated_at')
                            ->label(__('filament-navigation::filament-navigation.attributes.updated_at'))
                            ->content(fn($record): string => $record->updated_at->diffForHumans()),
                    ])
                        ->visible(fn($record): bool => static::$showTimestamps && !is_null($record)),
                ])->columnSpan([
                    12,
                    'lg' => 4,
                ]),
            ])
            ->columns(12);
    }

    /**
     * @param Table $table
     *
     * @return Table
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(__('ID'))
                    ->searchable(),

                TextColumn::make('title')
                    ->label(__('filament-navigation::filament-navigation.attributes.title'))
                    ->searchable(),

                IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean()
                    ->trueIcon('heroicon-o-badge-check')
                    ->falseIcon('')
                    ->toggleable(),

                TextColumn::make('items')
                    ->label(__('filament-navigation::filament-navigation.attributes.items'))
                    ->getStateUsing(fn($record): int => collect($record->items)->count())
                    ->toggleable(),

                TextColumn::make('handle')
                    ->label(__('filament-navigation::filament-navigation.attributes.handle'))
                    ->searchable()
                    ->toggleable(),
            ])
            ->actions([
                Actions\Tables\EditAction::make()
                    ->label("")
                    ->tooltip(__('Edit')),
                Actions\Tables\DeleteAction::make()
                    ->label("")
                    ->tooltip(__('Delete'))
                    ->hidden(fn($record): bool => $record->is_active),
            ])
            ->bulkActions([
                //
            ])
            ->filters([
                //
            ]);
    }

    /**
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => NavigationResource\Pages\ListNavigations::route('/'),
            'create' => NavigationResource\Pages\CreateNavigation::route('/create'),
            'edit' => NavigationResource\Pages\EditNavigation::route('/{record}'),
        ];
    }

    /**
     * @return string
     */
    public static function getModel(): string
    {
        return config('filament-navigation.navigation_model') ?? Navigation::class;
    }
}
