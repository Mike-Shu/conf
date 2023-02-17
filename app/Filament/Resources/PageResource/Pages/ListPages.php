<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Enums\PageTemplate;
use App\Filament\Resources\PageResource;
use App\Models\ChatConversation;
use App\Models\Page;
use App\Models\Player;
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

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

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
        return $table->defaultSort('id');
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
                ->description(fn($record): ?string => $record->is_title_hidden ? __('Title hidden') : null)
                ->color(fn($record): ?string => is_null($record->deleted_at) ? null : "danger")
                ->limit(200)
                ->wrap()
                ->searchable(),

            Columns\IconColumn::make('is_home_page')
                ->label(__('Home'))
                ->boolean()
                ->trueIcon('heroicon-o-badge-check')
                ->falseIcon('')
                ->toggleable(),

            Columns\TextColumn::make('permalink')
                ->label(__('Permalink'))
                ->url(fn($record): ?string => $record->permalink, true)
                ->wrap()
                ->toggleable(),

            Columns\ViewColumn::make('template')
                ->view('tables.columns.page-template')
                ->label(__('Template'))
                ->getStateUsing(fn($record): array => [
                    'template' => PageTemplate::getDescription($record->template),
                    'player' => $record->player
                        ? Player::find($record->player)->title
                        : null,
                    'chat' => $record->chat
                        ? ChatConversation::find($record->chat)->title
                        : null,
                ])
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
     * @return Closure|null
     */
    public function isTableRecordSelectable(): ?Closure
    {
        return static fn(Page $record): bool => !$record->is_home_page;
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getTableFilters(): array
    {
        return [
            Filters\TrashedFilter::make(),

            Filters\Filter::make('template')
                ->form([
                    Components\Select::make('template')
                        ->label(__('Template'))
                        ->placeholder("-")
                        ->options(PageTemplate::asSelectArray()),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        $data['template'],
                        fn(Builder $query, $template): Builder => $query->where('template', $template),
                    );
                })
                ->indicateUsing(function (array $data): ?string {
                    if ($data['template']) {
                        return __('Template') . ' "' . PageTemplate::getDescription($data['template']) . '"';
                    }

                    return null;
                }),
        ];
    }
}
