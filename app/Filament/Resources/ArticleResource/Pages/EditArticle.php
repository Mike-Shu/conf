<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Actions;
use App\Filament\Resources\ArticleResource;
use Exception;
use Filament\Forms\Components;
use Filament\Resources\Pages\EditRecord;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

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
                            Components\Grid::make()
                                ->schema([
                                    Components\Textarea::make('title')
                                        ->label(__('Title'))
                                        ->rows(2)
                                        ->maxLength(255)
                                        ->required()
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
                ->columnSpan([
                    'sm' => 4,
                    '2xl' => 3,
                ]),

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

                            Components\Toggle::make('visibility')
                                ->label(__('Visibility')),
                        ]),
                ])
                ->columnSpan([
                    'sm' => 4,
                    '2xl' => 1,
                ]),
        ];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function mutateFormDataBeforeSave(array $data): array
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
