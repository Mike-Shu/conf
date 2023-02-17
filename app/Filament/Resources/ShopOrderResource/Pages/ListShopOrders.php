<?php

namespace App\Filament\Resources\ShopOrderResource\Pages;

use App\Enums\ExportEntity;
use App\Filament\Resources\ShopOrderResource;
use App\Services\ExportDataService;
use Exception;
use Filament\Notifications\Notification;
use Filament\Pages;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Table;
use App\Filament\Actions;
use Filament\Tables\Columns;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class ListShopOrders extends ListRecords
{
    protected static string $resource = ShopOrderResource::class;

    protected $listeners = [
        'exportShopOrders',
    ];

    /**
     * @return array
     * @throws Exception
     */
    protected function getActions(): array
    {
        return [
            Pages\Actions\Action::make('export')
                ->label(__('Export'))
                ->color('success')
                ->icon('heroicon-o-archive')
                ->action(function (): void {
                    $this->emit('exportShopOrders');
                }),
        ];
    }

    /**
     * @param ExportDataService $export
     */
    public function exportShopOrders(ExportDataService $export): void
    {
        Notification::make()
            ->icon('heroicon-o-archive')
            ->title(__('Export started successfully'))
            ->success()
            ->send();

        $export->batchExport(ExportEntity::SHOP_ORDERS);
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
                ->label(__('ID')),

            Columns\TextColumn::make('user.name')
                ->label(__('User'))
                ->url(fn($record): string => route('filament.resources.users.edit', $record->user)),

            Columns\TextColumn::make('list')
                ->label(__('Order list'))
                ->getStateUsing(function ($record) {
                    $orderList = Arr::map($record->items->toArray(), static function ($_item) {
                        return $_item['product_details']['title'] . ': ' . $_item['amount'];
                    });

                    return collect($orderList)->implode("<br/>");
                })
                ->html(),

            Columns\TextColumn::make('cost')
                ->label(__('Order cost'))
                ->getStateUsing(fn($record): int => array_sum(Arr::map($record->items->toArray(),
                    static function ($_item) {
                        return (int)$_item['price'] * $_item['amount'];
                    })))
                ->toggleable(),

            Columns\TextColumn::make('address')
                ->label(__('Address'))
                ->sortable(query: function (Builder $query, string $direction): Builder {
                    return $query->orderBy('data->address', $direction);
                })
                ->toggleable(),

            Columns\TextColumn::make('created_at')
                ->label(__('Time'))
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
            Actions\Tables\DeleteAction::make()
                ->label("")
                ->tooltip(__('Delete')),
        ];
    }

    /**
     * @return array
     */
    protected function getTableBulkActions(): array
    {
        return [
            //
        ];
    }
}
