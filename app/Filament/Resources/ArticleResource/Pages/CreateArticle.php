<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Forms\Components;
use Filament\Resources\Pages\CreateRecord;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

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
                                    Components\Textarea::make('title')
                                        ->label(__('Title'))
                                        ->rows(2)
                                        ->maxLength(255)
                                        ->required()
                                        ->autofocus()
                                        ->columnSpan([
                                            'sm' => 2,
                                            '2xl' => 1,
                                        ]),

                                    Components\TextInput::make('slug')
                                        ->label(__('Slug'))
                                        ->helperText(__('If this field is left blank, the link will be generated automatically'))
                                        ->unique(ignoreRecord: true)
                                        ->maxValue(255)
                                        ->nullable()
                                        ->columnSpan([
                                            'sm' => 2,
                                            '2xl' => 1,
                                        ]),
                                ])->columns(),

                            Components\SpatieTagsInput::make('tags')
                                ->label(__('Tags'))
                                ->type("articles")
                                ->nullable(),

                            Components\RichEditor::make('content')
                                ->label(__('Content'))
                                ->disableToolbarButtons([
                                    'attachFiles',
                                    'codeBlock',
                                ])
                                ->required(),
                        ]),
                ])
                ->columnSpan(['lg' => 3]),

            Components\Group::make()
                ->schema([
                    Components\Card::make()
                        ->schema([
                            Components\Toggle::make('visibility')
                                ->label(__('Visibility'))
                                ->default(false),
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
