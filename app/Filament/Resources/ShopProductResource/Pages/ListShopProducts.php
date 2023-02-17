<?php

namespace App\Filament\Resources\ShopProductResource\Pages;

use App\Enums\ExportEntity;
use App\Filament\Resources\ShopProductResource;
use App\Services\ExportDataService;
use App\Settings\ShopSettings;
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

class ListShopProducts extends ListRecords
{
    protected static string $resource = ShopProductResource::class;

    protected $listeners = [
        'exportShopStock',
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
                    $this->emit('exportShopStock');
                }),
        ];
    }

    /**
     * @param ExportDataService $export
     */
    public function exportShopStock(ExportDataService $export): void
    {
        Notification::make()
            ->icon('heroicon-o-archive')
            ->title(__('Export started successfully'))
            ->success()
            ->send();

        $export->batchExport(ExportEntity::SHOP_STOCK);
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
                ->searchable(),

            Columns\TextColumn::make('title')
                ->label(__('Title'))
                ->color(fn($record): ?string => is_null($record->deleted_at) ? null : "danger")
                ->description(fn($record): ?string => $record->description ?: null)
                ->limit(200)
                ->wrap()
                ->searchable()
                ->sortable(),

            Columns\ImageColumn::make('thumb')
                ->label(__('Picture')),

            Columns\TextColumn::make('price')
                ->label(__('Price'))
                ->searchable()
                ->sortable()
                ->toggleable(),

            Columns\TextColumn::make('stock_total')
                ->label(__('Stock total'))
                ->toggleable(),

            Columns\TextColumn::make('purchased')
                ->label(__('Purchased'))
                ->getStateUsing(fn($record): int => $record->walletTransactions()->count())
                ->toggleable(),

            Columns\TextColumn::make('stock')
                ->label(__('Stock balance'))
                ->toggleable(),

            Columns\IconColumn::make('visibility')
                ->label(__('Visibility'))
                ->boolean()
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
            Actions\Tables\Custom\UpdateProductStockAction::make()
                ->visible(fn($record): bool => app(ShopSettings::class)->allow_shopping_cart === false),
            Actions\Tables\DeleteAction::make()
                ->label("")
                ->tooltip(__('Delete'))
                ->visible(fn($record): bool => (
                    app(ShopSettings::class)->allow_shopping_cart === false
                    && $record->wallet->transactions()->count() === 0
                )),
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
     * @return array
     * @throws Exception
     */
    protected function getTableFilters(): array
    {
        return [
            Filters\TrashedFilter::make(),

            Filters\Filter::make('visibility')
                ->form([
                    Components\Select::make('visibility')
                        ->label(__('Visibility'))
                        ->placeholder("-")
                        ->options([
                            'true' => __("Yes"),
                            'false' => __("No"),
                        ]),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        $data['visibility'],
                        fn(Builder $query, $visibility): Builder => $query->where('visibility',
                            json_decode($visibility)),
                    );
                })
                ->indicateUsing(function (array $data): ?string {
                    if ($data['visibility'] ?? null) {
                        $visibility = json_decode($data['visibility'])
                            ? __("Yes")
                            : __("No");

                        return __('Visibility') . ' "' . $visibility . '"';
                    }

                    return null;
                }),
        ];
    }
}
