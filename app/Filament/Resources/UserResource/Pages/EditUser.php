<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Exception;
use Filament\Forms\Components;
use App\Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Rules\Password;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected $listeners = ['refresh' => "refreshForm"];

    /**
     * @return array
     * @throws Exception
     */
    protected function getActions(): array
    {
        return [
            Actions\Pages\DeleteAction::make(),
        ];
    }

    /**
     * @return array
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->makeForm()
                ->context('edit')
                ->model($this->getRecord())
                ->schema($this->getFormSchema())
                ->columns(3)
                ->statePath('data')
                ->inlineLabel(config('filament.layout.forms.have_inline_labels')),
        ];
    }

    /**
     * @return array
     */
    protected function getFormSchema(): array
    {
        return [
            Components\Group::make()
                ->schema([
                    Components\Card::make()
                        ->schema([
                            Components\TextInput::make('first_name')
                                ->label(__('First name'))
                                ->required(),

                            Components\TextInput::make('middle_name')
                                ->label(__('Middle name'))
                                ->nullable(),

                            Components\TextInput::make('last_name')
                                ->label(__('Last name'))
                                ->nullable(),

                            Components\TextInput::make('email')
                                ->label(__('Email'))
                                ->email()
                                ->required(),

                            Components\TextInput::make('new_password')
                                ->label(__('New password'))
                                ->rules(['nullable', 'string', new Password]),

                            Components\Select::make('gender')
                                ->label(__('Gender'))
                                ->options([
                                    'male' => __("Male"),
                                    'female' => __("Female"),
                                ])
                                ->placeholder("-")
                                ->nullable(),
                        ])
                        ->columns(),
                ])
                ->columnSpan(['lg' => 2]),
            Components\Group::make()
                ->schema([
                    Components\Card::make()
                        ->schema([
                            Components\Placeholder::make('created_at')
                                ->label(__('Created'))
                                ->content(fn($record): string => $record->created_at->diffForHumans()),

                            Components\Placeholder::make('updated_at')
                                ->label(__('Last modified'))
                                ->content(fn($record): string => $record->updated_at->diffForHumans()),

                            Components\Placeholder::make('balance')
                                ->label(__('Balance'))
                                ->content(fn($record): string => $record->balance)
                                ->extraAttributes(['class' => 'text-2xl text-primary-600']),
                        ]),
                ])
                ->columnSpan(['lg' => 1]),
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['new_password']) {
            $data['password'] = Hash::make($data['new_password']);
        }
        unset($data['new_password']);

        return $data;
    }

    /**
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? self::getResource()::getUrl('index');
    }

    public function refreshForm(): void
    {
        $this->fillForm();
    }
}
