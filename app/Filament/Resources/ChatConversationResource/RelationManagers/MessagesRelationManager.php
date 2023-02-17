<?php

namespace App\Filament\Resources\ChatConversationResource\RelationManagers;

use App\Filament\Actions;
use App\Filament\Actions\Tables;
use Exception;
use Filament\Forms\Components;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables\Columns;
use Filament\Tables\Filters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';

    /**
     * @return string
     */
    public static function getTitle(): string
    {
        return __('Messages');
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
                Columns\TextColumn::make('user.name')
                    ->label(__('User'))
                    ->url(fn($record): string => route('filament.resources.users.edit', $record->user)),

                Columns\TextColumn::make('text')
                    ->label(__('Message'))
                    ->wrap()
                    ->sortable()
                    ->searchable(),

                Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->date("j M Y, H:i:s")
                    ->sortable()
                    ->toggleable(),

            ])
            ->defaultSort('created_at');
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getTableActions(): array
    {
        return [
            Tables\DeleteAction::make(),
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getTableFilters(): array
    {
        return [
            Filters\Filter::make('created_from_until')
                ->form([
                    Components\Grid::make()
                        ->schema([
                            Components\DateTimePicker::make('created_from')
                                ->label(__('Created from'))
                                ->withoutSeconds()
                                ->displayFormat("j M Y, H:i"),

                            Components\DateTimePicker::make('created_until')
                                ->label(__('Created until'))
                                ->withoutSeconds()
                                ->displayFormat("j M Y, H:i"),
                        ])
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn(Builder $query, $dateTime): Builder => $query->where('created_at', '>=', $dateTime),
                        )
                        ->when(
                            $data['created_until'],
                            fn(Builder $query, $dateTime): Builder => $query->where('created_at', '<=', $dateTime),
                        );
                })
                ->indicateUsing(function (array $data): array {
                    $indicators = [];
                    if ($data['created_from'] ?? null) {
                        $indicators['created_from'] = __('Created from') . ' ' . Carbon::parse($data['created_from'])
                                ->translatedFormat("j M Y, H:i");
                    }
                    if ($data['created_until'] ?? null) {
                        $indicators['created_until'] = __('Created until') . ' ' . Carbon::parse($data['created_until'])
                                ->translatedFormat("j M Y, H:i");
                    }

                    return $indicators;
                }),
        ];
    }

    /**
     * @return string
     */
    protected function getTableFiltersFormWidth(): string
    {
        return 'xl';
    }
}
