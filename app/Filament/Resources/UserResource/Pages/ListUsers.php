<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enums\ExportEntity;
use App\Enums\UserGender;
use App\Filament\Resources\UserResource;
use App\Services\ExportDataService;
use Exception;
use Filament\Forms\Components;
use Filament\Notifications\Notification;
use Filament\Pages;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Table;
use App\Filament\Actions;
use Filament\Tables\Columns;
use Filament\Tables\Filters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected $listeners = [
        'exportUsers',
    ];

    /**
     * @return array
     * @throws Exception
     */
    protected function getActions(): array
    {
        return [
            Pages\Actions\CreateAction::make(),

            Pages\Actions\Action::make('export')
                ->label(__('Export'))
                ->color('success')
                ->icon('heroicon-o-archive')
                ->visible(fn(): bool => $this->getAllTableRecordsCount())
                ->action(function (): void {
                    $this->emit('exportUsers');
                }),
        ];
    }

    /**
     * @param ExportDataService $export
     */
    public function exportUsers(ExportDataService $export): void
    {
        Notification::make()
            ->icon('heroicon-o-archive')
            ->title(__('Export started successfully'))
            ->success()
            ->send();

        $export->batchExport(ExportEntity::USERS);
    }

    /**
     * @param Table $table
     *
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table->defaultSort('id', 'desc');
    }

    /**
     * @return array
     */
    protected function getTableColumns(): array
    {
        return [
            Columns\TextColumn::make('id')
                ->label(__('ID'))
                ->sortable()
                ->toggleable(),

            Columns\TextColumn::make('name')
                ->label(__('Name'))
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query
                        ->where('first_name', 'ilike', "%{$search}%")
                        ->orWhere('middle_name', 'ilike', "%{$search}%")
                        ->orWhere('last_name', 'ilike', "%{$search}%");
                })
                ->color(fn($record): ?string => is_null($record->deleted_at) ? null : "danger"),

            Columns\TextColumn::make('gender')
                ->label(__('Gender'))
                ->enum(UserGender::asSelectArray())
                ->toggleable(),

            Columns\TextColumn::make('email')
                ->label(__('Email'))
                ->searchable()
                ->toggleable(),

            Columns\TextColumn::make('balance')
                ->label(__('Balance'))
                ->toggleable(),

            Columns\TextColumn::make('created_at')
                ->label(__('Created'))
                ->date("j M Y, H:i")
                ->sortable()
                ->toggleable(),
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
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getTableBulkActions(): array
    {
        return [
            //
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getTableFilters(): array
    {
        return [
            Filters\Filter::make('created_at')
                ->form([
                    Components\DatePicker::make('published_from')
                        ->label(__('Created from'))
                        ->displayFormat("j M Y"),
                    Components\DatePicker::make('published_until')
                        ->label(__('Created until'))
                        ->displayFormat("j M Y"),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['published_from'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['published_until'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })
                ->indicateUsing(function (array $data): array {
                    $indicators = [];
                    if ($data['published_from'] ?? null) {
                        $indicators['published_from'] = __('Created from') . ' ' . Carbon::parse($data['published_from'])
                                ->translatedFormat("j M Y");
                    }
                    if ($data['published_until'] ?? null) {
                        $indicators['published_until'] = __('Created until') . ' ' . Carbon::parse($data['published_until'])
                                ->translatedFormat("j M Y");
                    }

                    return $indicators;
                }),
        ];
    }
}
