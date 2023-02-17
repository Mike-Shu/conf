<?php

namespace App\Filament\Resources\ExportResource\Pages;

use App\Enums\ExportEntity;
use App\Enums\ExportType;
use App\Filament\Resources\ExportResource;
use App\Models\Export;
use Closure;
use Exception;
use Filament\Forms\Components;
use Filament\Pages;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Table;
use App\Filament\Actions;
use Filament\Tables\Columns;
use Filament\Tables\Filters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ListExports extends ListRecords
{
    protected static string $resource = ExportResource::class;

    /**
     * @return array
     * @throws Exception
     */
    protected function getActions(): array
    {
        return [
            Pages\Actions\Action::make('delete_files')
                ->label(__('Delete files'))
                ->color('danger')
                ->icon('heroicon-o-trash')
                ->modalWidth('lg')
                ->modalButton(__('Delete'))
                ->modalSubheading(__('When files are deleted, active filters will be applied'))
                ->visible(fn(): bool => $this->getFilteredTableQuery()
                    ->where('data->batch_finished', true)
                    ->count())
                ->action(function (array $data): void {
                    $olderDateTime = Carbon::now()
                        ->subDays((integer)$data['older_days'])
                        ->toDateTimeString();

                    $records = $this->getFilteredTableQuery()
                        ->where('created_at', '<=', $olderDateTime)
                        ->where('data->batch_finished', true)
                        ->get();

                    $records->each(function ($_record) {
                        $_record->delete();
                    });
                })
                ->form([
                    Components\Select::make('older_days')
                        ->disableLabel()
                        ->disablePlaceholderSelection()
                        ->default(5)
                        ->options([
                            0 => __('All files'),
                            1 => __('Older than one day'),
                            2 => __('Older than two days'),
                            3 => __('Older than three days'),
                            4 => __('Over four days old'),
                            5 => __('Over five days old'),
                        ]),
                ]),
        ];
    }

    /**
     * @param Table $table
     *
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->poll()
            ->defaultSort('id', 'desc');
    }

    /**
     * @return array
     */
    protected function getTableColumns(): array
    {
        return [
            Columns\TextColumn::make('id')
                ->label(__('ID')),

            Columns\TextColumn::make('entity')
                ->label(__('Export entity'))
                ->getStateUsing(fn($record): string => ExportEntity::getDescription($record->entity)),

            Columns\TextColumn::make('type')
                ->label(__('Export type'))
                ->getStateUsing(fn($record): string => ExportType::getDescription($record->type)),

            Columns\ViewColumn::make('file')
                ->view('tables.columns.export-file')
                ->label(__('File'))
                ->getStateUsing(fn($record): array => [
                    'batch_finished' => $record->batch_finished,
                    'batch_progress' => $record->batch_progress,
                    'file_url' => $record->file_url,
                    'file_size' => $record->file_size,
                ]),

            Columns\TextColumn::make('created_at')
                ->label(__('Created at'))
                ->date("j M Y, H:i")
                ->sortable(),
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getTableActions(): array
    {
        return [
            Actions\Tables\DeleteAction::make()
                ->label("")
                ->tooltip(__('Delete'))
                ->visible(fn($record): bool => isset($record->batch_finished)),
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
        ];
    }

    /**
     * @return Closure|null
     */
    public function isTableRecordSelectable(): ?Closure
    {
        return static fn(Export $record): bool => isset($record->batch_finished);
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getTableFilters(): array
    {
        return [
            Filters\Filter::make('entity_type')
                ->form([
                    Components\Grid::make()
                        ->schema([
                            Components\Select::make('entity')
                                ->label(__('Export entity'))
                                ->placeholder("-")
                                ->options(ExportEntity::asSelectArray()),

                            Components\Select::make('type')
                                ->label(__('Export type'))
                                ->placeholder("-")
                                ->options(ExportType::asSelectArray()),
                        ])
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['entity'],
                            fn(Builder $query, $entity): Builder => $query->where('entity', $entity),
                        )
                        ->when(
                            $data['type'],
                            fn(Builder $query, $type): Builder => $query->where('data->type', $type),
                        );
                })
                ->indicateUsing(function (array $data): array {
                    $indicators = [];
                    if ($data['entity'] ?? null) {
                        $indicators['entity'] = __('Export entity') . ' "' . ExportEntity::getDescription($data['entity']) . '"';
                    }

                    if ($data['type'] ?? null) {
                        $indicators['type'] = __('Export type') . ' "' . ExportType::getDescription($data['type']) . '"';
                    }

                    return $indicators;
                }),

            Filters\Filter::make('created_from_until')
                ->form([
                    Components\Grid::make()
                        ->schema([
                            Components\DatePicker::make('created_from')
                                ->label(__('Created from'))
                                ->displayFormat("j M Y"),

                            Components\DatePicker::make('created_until')
                                ->label(__('Created until'))
                                ->displayFormat("j M Y"),
                        ])
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })
                ->indicateUsing(function (array $data): array {
                    $indicators = [];
                    if ($data['created_from'] ?? null) {
                        $indicators['created_from'] = __('Created from') . ' ' . Carbon::parse($data['created_from'])
                                ->translatedFormat("j M Y");
                    }
                    if ($data['created_until'] ?? null) {
                        $indicators['created_until'] = __('Created until') . ' ' . Carbon::parse($data['created_until'])
                                ->translatedFormat("j M Y");
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
        return 'lg';
    }

    protected function shouldPersistTableFiltersInSession(): bool
    {
        return true;
    }
}
