<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Exception;
use Filament\Pages;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Table;
use App\Filament\Actions;
use Filament\Tables\Columns;
use Filament\Tables\Filters;

class ListArticles extends ListRecords
{
    protected static string $resource = ArticleResource::class;

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
                ->searchable()
                ->sortable(),

            Columns\TextColumn::make('title')
                ->label(__('Title'))
                ->color(fn($record): ?string => is_null($record->deleted_at) ? null : "danger")
                ->limit(200)
                ->wrap()
                ->searchable(),

            Columns\SpatieTagsColumn::make('tags')
                ->label(__('Tags'))
                ->type("articles")
                ->toggleable(),

            Columns\TextColumn::make('permalink')
                ->label(__('Permalink'))
                ->url(fn($record): ?string => $record->permalink, true)
                ->wrap()
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
     * @return array
     * @throws Exception
     */
    protected function getTableFilters(): array
    {
        return [
            Filters\TrashedFilter::make(),
        ];
    }
}
