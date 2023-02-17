<?php

namespace App\Http\Livewire;

use App\Enums\WalletTransactionType;
use App\Facades\AllWalletReason;
use Exception;
use Filament\Forms\Components;
use Filament\Tables;
use Filament\Tables\Filters;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class ShowWalletLog extends Component implements Tables\Contracts\HasRelationshipTable, Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    /**
     * @return string
     */
    public function getInverseRelationshipName(): string
    {
        return "users";
    }

    /**
     * @return Relation|Builder
     */
    public function getRelationship(): Relation|Builder
    {
        return Request::user()->transactions();
    }

    /**
     * @return array
     */
    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('type')
                ->label(__('Type'))
                ->getStateUsing(fn($record): string => WalletTransactionType::getDescription($record->type)),

            Tables\Columns\TextColumn::make('amount')
                ->label(__('Amount')),

            Tables\Columns\TextColumn::make('meta.reason')
                ->label(__('Reason'))
                ->getStateUsing(fn($record): string => AllWalletReason::getDescription($record->meta['reason'])),

            Tables\Columns\TextColumn::make('created_at')
                ->label(__('Time'))
                ->date("j M Y, H:i")
                ->sortable(),
        ];
    }

    /**
     * @return string|null
     */
    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    /**
     * @return string|null
     */
    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    /**
     * @return bool
     */
    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getTableFilters(): array
    {
        return [
            Filters\Filter::make('type_reason')
                ->form([
                    Components\Grid::make()
                        ->schema([
                            Components\Select::make('type')
                                ->label(__('Type'))
                                ->placeholder("-")
                                ->options(WalletTransactionType::asSelectArray()),

                            Components\Select::make('reason')
                                ->label(__('Reason'))
                                ->placeholder("-")
                                ->options(function () {
                                    $usedReasons = Request::user()->transactions()
                                        ->get()
                                        ->pluck('meta.reason')
                                        ->toArray();

                                    return AllWalletReason::asSelectArray($usedReasons);
                                }),
                        ])
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['type'],
                            fn(Builder $query, $type): Builder => $query->where('type', $type),
                        )
                        ->when(
                            $data['reason'],
                            fn(Builder $query, $reason): Builder => $query->where('meta->reason', $reason),
                        );
                })
                ->indicateUsing(function (array $data): array {
                    $indicators = [];
                    if ($data['type'] ?? null) {
                        $indicators['type'] = __('Type') . ' "' . WalletTransactionType::getDescription($data['type']) . '"';
                    }

                    if ($data['reason'] ?? null) {
                        $indicators['reason'] = __('Reason') . ' "' . AllWalletReason::getDescription($data['reason']) . '"';
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
        return '2xl';
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.show-wallet-log');
    }
}
