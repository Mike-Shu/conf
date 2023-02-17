<?php

namespace App\Filament\Resources\MediaResource\Pages;

use App\Enums\MediaStatus;
use App\Enums\VideoProvider;
use App\Facades\AllWalletReason;
use App\Filament\Actions;
use App\Filament\Resources\MediaResource;
use Exception;
use Filament\Forms\Components;
use Filament\Tables\Columns;
use Filament\Tables\Filters;
use Filament\Resources\Pages\ManageRecords;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Builder;

class ManageMedia extends ManageRecords
{
    protected static string $resource = MediaResource::class;

    /**
     * @return array
     */
    protected function getActions(): array
    {
        return [
            //
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

            Columns\TextColumn::make('user.name')
                ->label(__('User'))
                ->url(fn($record): string => route('filament.resources.users.edit', $record->user)),

            Columns\ViewColumn::make('media')
                ->view('tables.columns.media')
                ->label(__('Media'))
                ->getStateUsing(fn($record): array => [
                    'type' => $record->type,
                    'content' => $record->content->toArray(),
                ])
                ->toggleable(),

            Columns\ViewColumn::make('info')
                ->view('tables.columns.media-info')
                ->label(__('Note'))
                ->getStateUsing(fn($record): array => [
                    'description' => $record->description,
                    'comment' => $record->comment,
                ])
                ->toggleable(),

            Columns\ViewColumn::make('status')
                ->view('tables.columns.media-status')
                ->label(__('Status'))
                ->getStateUsing(fn($record): array => [
                    'status' => MediaStatus::getDescription($record->status),
                    'color' => match ($record->status) {
                        MediaStatus::PENDING => "secondary",
                        MediaStatus::ACCEPTED => "success",
                        MediaStatus::REJECTED => "danger"
                    },
                    'reason' => $record->reason
                        ? AllWalletReason::getDescription($record->reason)
                        : null,
                    'video_provider' => $record->video_provider
                        ? VideoProvider::getDescription($record->video_provider)
                        : null,
                ])
                ->toggleable(),

//            Columns\BadgeColumn::make('status')
//                ->label(__('Status'))
//                ->enum(MediaStatus::asSelectArray())
//                ->sortable()
//                ->colors([
//                    'secondary' => MediaStatus::PENDING,
//                    'success' => MediaStatus::ACCEPTED,
//                    'danger' => MediaStatus::REJECTED,
//                ])
//                ->toggleable(),
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
                ->label(__('Change status'))
                ->icon('heroicon-o-shield-check')
                ->tooltip(__('Edit'))
                ->modalHeading(__('Change status')),
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
            Filters\Filter::make('status_and_type')
                ->form([
                    Components\Select::make('status')
                        ->label(__('Status'))
                        ->placeholder("-")
                        ->options(MediaStatus::asSelectArray()),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        $data['status'],
                        fn(Builder $query, $status): Builder => $query->where('status', $status),
                    );
                })
                ->indicateUsing(function (array $data): ?string {
                    if ($data['status'] ?? null) {
                        return __('Status') . ' "' . MediaStatus::getDescription($data['status']) . '"';
                    }

                    return null;
                }),
        ];
    }
}
