<?php

namespace App\Filament\Resources\TimetableResource\Pages;

use App\Filament\Resources\TimetableResource;
use Filament\Forms\Components;
use Filament\Resources\Pages\CreateRecord;

class CreateTimetable extends CreateRecord
{
    protected static string $resource = TimetableResource::class;

    protected static bool $canCreateAnother = false;

    /**
     * @return array
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->makeForm()
                ->context('create')
                ->model($this->getModel())
                ->schema($this->getFormSchema())
                ->columns(4)
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
                            Components\TextInput::make('title')
                                ->label(__('Title'))
                                ->rule('max:255')
                                ->required()
                                ->autofocus(),
                        ]),
                ])
                ->columnSpan(['lg' => 3]),

            Components\Group::make()
                ->schema([
                    //
                ])
                ->columnSpan(['lg' => 1]),
        ];
    }
}
