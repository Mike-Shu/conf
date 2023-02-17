<?php

namespace App\Filament\Resources\PlayerResource\Pages;

use App\Enums\VideoProvider;
use App\Filament\Resources\PlayerResource;
use Exception;
use Filament\Forms\Components;
use Filament\Pages;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Table;
use App\Filament\Actions;
use Filament\Tables\Columns;
use Filament\Tables\Filters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ListPlayers extends ListRecords
{
    protected static string $resource = PlayerResource::class;

    /**
     * @return array
     * @throws Exception
     */
    protected function getActions(): array
    {
        return [
            Pages\Actions\CreateAction::make(),
        ];
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
                ->limit(200)
                ->wrap()
                ->searchable(),

            Columns\ViewColumn::make('thumbnail')
                ->view('tables.columns.image-as-link')
                ->label(__('Video'))
                ->getStateUsing(fn($record): array => [
                    'href' => $record->link,
                    'src' => $record->thumbnail,
                ])
                ->toggleable(),

            Columns\TextColumn::make('provider')
                ->label(__('Provider'))
                ->getStateUsing(fn($record): ?string => $record->provider
                    ? VideoProvider::getDescription($record->provider)
                    : null)
                ->toggleable(),

            Columns\TextColumn::make('video_id')
                ->label(__('ID'))
                ->toggleable(),

            Columns\IconColumn::make('is_wrapper')
                ->label(__('Is a wrapper'))
                ->boolean()
                ->trueIcon('heroicon-o-badge-check')
                ->falseIcon('')
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

            Filters\Filter::make('provider')
                ->form([
                    Components\Select::make('provider')
                        ->label(__('Provider'))
                        ->placeholder("-")
                        ->options(fn(): Collection => collect(VideoProvider::asSelectArray())->put('no_provider', __("No"))),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        $data['provider'],
                        fn(Builder $query, $provider): Builder => $provider === "no_provider"
                            ? $query->whereNull('data->provider')
                            : $query->where('data->provider', $provider),
                    );
                })
                ->indicateUsing(function (array $data): ?string {
                    if ($data['provider']) {
                        $provider = $data['provider'] === "no_provider"
                            ? __("No")
                            : VideoProvider::getDescription($data['provider']);

                        return __('Provider') . ' "' . $provider . '"';
                    }

                    return null;
                }),
        ];
    }
}
