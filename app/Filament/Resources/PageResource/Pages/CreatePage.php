<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Enums\PageTemplate;
use App\Filament\Resources\PageResource;
use App\Models\ChatConversation;
use App\Models\Player;
use Closure;
use Filament\Forms\Components;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

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
                            Components\Grid::make()
                                ->schema([
                                    Components\Group::make([
                                        Components\TextInput::make('title')
                                            ->label(__('Title'))
                                            ->required()
                                            ->autofocus(),

                                        Components\Checkbox::make('is_title_hidden')
                                            ->label(__('Hide title'))
                                            ->default(false),
                                    ]),

                                    Components\TextInput::make('slug')
                                        ->label(__('Slug'))
                                        ->helperText(__('If this field is left blank, the link will be generated automatically'))
                                        ->unique(ignoreRecord: true)
                                        ->nullable(),
                                ]),

                            Components\RichEditor::make('content')
                                ->label(__('Content'))
                                ->disableToolbarButtons([
                                    'attachFiles',
                                    'codeBlock',
                                ])
                                ->nullable(),
                        ]),
                ])
                ->columnSpan(['lg' => 3]),

            Components\Group::make()
                ->schema([
                    Components\Card::make()
                        ->schema([
                            Components\Select::make('template')
                                ->label(__('Template'))
                                ->options(PageTemplate::asSelectArray())
                                ->disablePlaceholderSelection()
                                ->default(PageTemplate::DEFAULT)
                                ->reactive(),

                            Components\Select::make('player')
                                ->label(__('Player'))
                                ->options(Player::all()->pluck('title', 'id'))
                                ->placeholder("-")
                                ->hidden(fn(Closure $get) => !in_array($get('template'), [
                                    PageTemplate::PLAYER,
                                    PageTemplate::PLAYER_WITH_CHAT,
                                    PageTemplate::PLAYER_WITH_CHAT_AND_UPLOAD_FORM,
                                ], true))
                                ->required(),

                            Components\Select::make('chat')
                                ->label(__('Chat'))
                                ->options(ChatConversation::all()->pluck('title', 'id'))
                                ->placeholder("-")
                                ->hidden(fn(Closure $get) => !in_array($get('template'), [
                                    PageTemplate::PLAYER_WITH_CHAT,
                                    PageTemplate::PLAYER_WITH_CHAT_AND_UPLOAD_FORM,
                                ], true))
                                ->required(),
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
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Remove redundant line breaks.
        if ($data['content']) {
            $data['content'] = strReplace("<br><br><br>", "<br><br>", $data['content']);
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
