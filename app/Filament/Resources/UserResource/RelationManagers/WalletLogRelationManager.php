<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enums\WalletReasonMain;
use App\Enums\WalletTransactionType;
use App\Facades\AllWalletReason;
use App\Filament\Actions;
use App\Models\User;
use Bavix\Wallet\Models\Transaction;
use Closure;
use Exception;
use Filament\Forms\Components;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Contracts\HasRelationshipTable;
use Filament\Tables\Filters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class WalletLogRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    /**
     * @return string
     */
    public static function getTitle(): string
    {
        return __('Wallet log');
    }

    /**
     * @param Form $form
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\Grid::make()
                    ->schema([
                        Components\Select::make('reason')
                            ->label(__('Reason'))
                            ->placeholder("-")
                            ->options(AllWalletReason::asSelectArray([
                                // Filtering the list of reasons.
                                WalletReasonMain::INSTAGRAM,
                                WalletReasonMain::FILLWORD,
                                WalletReasonMain::BIRTHDAY,
                                WalletReasonMain::BUSINESS,
                                WalletReasonMain::CORRECTION,
                            ]))
                            ->reactive()
                            ->afterStateUpdated(function (Closure $set, Closure $get) {
                                $set('points', match ($get('reason')) {
                                    // Automatic insertion of the required number of points.
                                    WalletReasonMain::INSTAGRAM, WalletReasonMain::FILLWORD => 100,
                                    WalletReasonMain::BUSINESS => 150,
                                    default => null,
                                });
                            })
                            ->required(),

                        Components\TextInput::make('points')
                            ->label(__('Points'))
                            ->hint(__('How many points to deposit or withdraw'))
                            ->numeric()
                            ->minValue(0)
                            ->required(),
                    ]),

                Components\Radio::make('transaction_type')
                    ->label(__('Type of transaction'))
                    ->options([
                        'deposit' => str(__('Deposit points'))->lower()->value(),
                        'withdraw' => str(__('Withdraw points'))->lower()->value(),
                    ])
                    ->inline()
                    ->required(),

                Components\Textarea::make('comment')
                    ->label(__('Comment'))
                    ->rows(2)
                    ->nullable(),
            ])
            ->columns(1);
    }

    /**
     * @param Table $table
     * @return Table
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type'))
                    ->getStateUsing(fn($record): string => WalletTransactionType::getDescription($record->type)),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('Amount')),
                Tables\Columns\TextColumn::make('meta.reason')
                    ->label(__('Reason'))
                    ->getStateUsing(fn($record): string => AllWalletReason::getDescription($record->meta['reason']))
                    ->description(fn($record): ?string => $record->meta['comment'] ?? null)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Time'))
                    ->date("j M Y, H:i")
                    ->sortable()
                    ->toggleable(),

            ])
            ->defaultSort('created_at', 'desc');
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make()
                ->label(__('Create a deposit'))
                ->modalHeading(__('Create a deposit'))
                ->using(function (HasRelationshipTable $livewire, array $data): ?Transaction {
                    /** @var User $user */
                    $user = $livewire->ownerRecord;

                    if ($data['transaction_type'] === WalletTransactionType::DEPOSIT) {
                        return $user->deposit($data['points'], [
                            'reason' => $data['reason'],
                            'comment' => $data['comment'],
                        ]);
                    }

                    if ($data['transaction_type'] === WalletTransactionType::WITHDRAW) {
                        return $user->withdraw($data['points'], [
                            'reason' => $data['reason'],
                            'comment' => $data['comment'],
                        ]);
                    }

                    return null;
                })
                ->disableCreateAnother()
                ->successNotificationTitle(__('Points deposited'))
                ->after(function (RelationManager $livewire) {
                    $livewire->emit('refresh');
                }),
        ];
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
                                ->options(function (RelationManager $livewire) {
                                    $usedReasons = $livewire->ownerRecord->transactions()
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
        return 'xl';
    }
}
