<?php

namespace App\Filament\Resources\PlayerResource\Pages;

use App\Filament\Resources\PlayerResource;
use App\Services\VideoLinkService;
use Filament\Forms\Components;
use Filament\Resources\Pages\CreateRecord;

class CreatePlayer extends CreateRecord
{
    protected static string $resource = PlayerResource::class;

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
                                ->required()
                                ->autofocus(),

                            Components\TextInput::make('link')
                                ->label(__('Link'))
                                ->rules(['max:2048'])
                                ->required(),

                            Components\Checkbox::make('is_wrapper')
                                ->label(__('Is a wrapper'))
                                ->default(false),
                        ]),
                ])
                ->columnSpan(['lg' => 3]),

            Components\Group::make()
                ->schema([
                ])
                ->columnSpan(['lg' => 1]),
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $service = app(VideoLinkService::class);
        $linkData = $service->parseVideoUrl($data['link']);

        if ($linkData) {
            $data['thumbnail'] = $service->getThumbnailByUrl($data['link']);
            $data['provider'] = $linkData['provider'];
            $data['video_id'] = $linkData['id'];
        } else {
            $data['provider'] = null;
        }

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
