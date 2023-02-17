<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Forms\Components;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Rules\Password;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * @return array
     */
    protected function getFormSchema(): array
    {
        return [
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

                    Components\TextInput::make('password')
                        ->label(__('Password'))
                        ->required()
                        ->rules(['required', 'string', new Password]),

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
        ];
    }

    /**
     * @param array $data
     * @return array
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = Hash::make($data['password']);

        return $data;
    }

    /**
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        return self::getResource()::getUrl('index');
    }
}
