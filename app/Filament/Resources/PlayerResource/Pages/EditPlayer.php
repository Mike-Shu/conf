<?php

namespace App\Filament\Resources\PlayerResource\Pages;

use App\Filament\Actions;
use App\Filament\Resources\PlayerResource;
use App\Services\VideoLinkService;
use Exception;
use Filament\Forms\Components;
use Filament\Resources\Pages\EditRecord;

class EditPlayer extends EditRecord
{
    protected static string $resource = PlayerResource::class;

    /**
     * @return array
     * @throws Exception
     */
    protected function getActions(): array
    {
        return [
            Actions\Pages\DeleteAction::make(),
            Actions\Pages\ForceDeleteAction::make(),
            Actions\Pages\RestoreAction::make(),
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
                                ->required(),

                            Components\TextInput::make('link')
                                ->label(__('Link'))
                                ->rules(['max:2048'])
                                ->required(),

                            Components\Checkbox::make('is_wrapper')
                                ->label(__('Is a wrapper')),
                        ]),
                ])
                ->columnSpan(['lg' => 3]),

            Components\Group::make()
                ->schema([
                    Components\Card::make()
                        ->schema([
                            Components\Placeholder::make('created_at')
                                ->label(__('Created at'))
                                ->content(fn($record): string => $record->created_at->diffForHumans()),

                            Components\Placeholder::make('updated_at')
                                ->label(__('Last modified at'))
                                ->content(fn($record): string => $record->updated_at->diffForHumans()),
                        ]),
                ])
                ->columnSpan(['lg' => 1]),
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $service = app(VideoLinkService::class);
        $linkData = $service->parseVideoUrl($data['link']);

        if ($linkData) {
            $data['thumbnail'] = $service->getThumbnailByUrl($data['link']);
            $data['provider'] = $linkData['provider'];
            $data['video_id'] = $linkData['id'];
        } else {
            $data['thumbnail'] = null;
            $data['provider'] = null;
            $data['video_id'] = null;
        }

        return $data;
    }

    /**
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? self::getResource()::getUrl('index');
    }
}
