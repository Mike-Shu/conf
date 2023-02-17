<?php

namespace App\Filament\Resources\QuizResource\Pages;

use App\Filament\Resources\QuizResource;
use Closure;
use Filament\Forms\Components;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CreateQuiz extends CreateRecord
{
    protected static string $resource = QuizResource::class;

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

                            Components\Grid::make()
                                ->schema([
                                    Components\TextInput::make('reward_try')
                                        ->label(__('Try score'))
                                        ->numeric()
                                        ->minValue(0)
                                        ->nullable()
                                        ->columnSpan(['xl' => 1]),

                                    Components\TextInput::make('reward_answer')
                                        ->label(__('Correct score'))
                                        ->numeric()
                                        ->minValue(0)
                                        ->nullable()
                                        ->columnSpan(['xl' => 1]),
                                ])->columns(),

                            Components\RichEditor::make('final_text')
                                ->label(__('Quiz final text'))
                                ->disableAllToolbarButtons()
                                ->enableToolbarButtons([
                                    'bold',
                                    'italic',
                                    'strike',
                                    'link',
                                    'bulletList',
                                    'orderedList',
                                    'redo',
                                    'undo',
                                ])
                                ->nullable(),
                        ]),
                ])
                ->columnSpan([
                    'sm' => 4,
                    '2xl' => 3,
                ]),

            Components\Group::make()
                ->schema([
//                    Components\Card::make() // TODO: Temporarily disabled
//                        ->schema([
//                            Components\Checkbox::make('shuffle_questions')
//                                ->label(__('Shuffle questions')),
//
//                            Components\Checkbox::make('shuffle_answers')
//                                ->label(__('Shuffle answers')),
//                        ]),
                ])
                ->columnSpan([
                    'sm' => 4,
                    '2xl' => 1,
                ]),

            Components\Group::make()
                ->schema([
                    // A list of questions
                    Components\Repeater::make('questions')
                        ->label(__('Questions'))
                        ->relationship()
                        ->createItemButtonLabel(__('Add a question'))
                        ->itemLabel(fn(array $state) => $this->getPlainContent($state['content']))
                        ->mutateRelationshipDataBeforeSaveUsing(function ($data) {
                            // Remove redundant line breaks.
                            if ($data['content']) {
                                $data['content'] = strReplace(
                                    "<br><br><br>",
                                    "<br><br>",
                                    $data['content']
                                );
                            }

                            return $data;
                        })
                        ->rules([
                            function () {
                                return static function (string $attribute, $value, Closure $fail) {
                                    if (empty($value)) {
                                        $fail(__('The quiz should contain questions') . ".");
                                    }
                                };
                            },
                        ])
                        ->collapsible()
                        ->orderable()
                        ->schema([
                            Components\RichEditor::make('content')
                                ->label(__('Question'))
                                ->disableAllToolbarButtons()
                                ->enableToolbarButtons([
                                    'bold',
                                    'italic',
                                    'strike',
                                    'redo',
                                    'undo',
                                ])
                                ->reactive()
                                ->required(),

                            Components\Radio::make('multiple')
                                ->label(__('The user can choose') . ":")
                                ->boolean(
                                    trueLabel: Str::lower(__('Multiple answers')),
                                    falseLabel: Str::lower(__('Only one answer'))
                                )
                                ->default(false)
                                ->inline(),

                            // A list of answers
                            Components\Repeater::make('answers')
                                ->label(__('Answers'))
                                ->relationship()
                                ->createItemButtonLabel(__('Add an answer'))
                                ->itemLabel(function ($state) {
                                    $label = $this->getPlainContent($state['content']);

                                    return $state['correct']
                                        ? "* " . $label
                                        : $label;
                                })
                                ->mutateRelationshipDataBeforeSaveUsing(function ($data) {
                                    // Remove redundant line breaks.
                                    if ($data['content']) {
                                        $data['content'] = strReplace(
                                            "<br><br><br>",
                                            "<br><br>",
                                            $data['content']
                                        );
                                    }

                                    return $data;
                                })
                                ->rules([
                                    function (Closure $get) {
                                        return static function (string $attribute, $value, Closure $fail) use (
                                            $get
                                        ) {
                                            if (empty($value)) {
                                                $fail(__('The question must contain answers') . ".");
                                            }

                                            $correctedAnswers = Arr::where($value,
                                                static fn($_item) => $_item['correct'] === true);

                                            if (empty($correctedAnswers)) {
                                                $fail(__('The question must contain answer marked correct answer') . ".");
                                            }

                                            if (!$get('multiple') && count($correctedAnswers) > 1) {
                                                $fail(__('The question should contain only one answer marked correct answer') . ".");
                                            }

                                            if ($get('multiple') && count($correctedAnswers) === 1) {
                                                $fail(__('The question must contain multiple answers marked correct answer') . ".");
                                            }
                                        };
                                    },
                                ])
                                ->collapsible()
                                ->orderable()
                                ->schema([
                                    Components\RichEditor::make('content')
                                        ->label(__('Answer'))
                                        ->disableAllToolbarButtons()
                                        ->enableToolbarButtons([
                                            'bold',
                                            'italic',
                                            'strike',
                                            'redo',
                                            'undo',
                                        ])
                                        ->reactive()
                                        ->required(),

                                    Components\Checkbox::make('correct')
                                        ->label(__('This is the correct answer'))
                                        ->inline()
                                        ->reactive(),
                                ]),
                        ]),
                ])
                ->columnSpan(['lg' => 4]),
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
        if ($data['final_text']) {
            $data['final_text'] = strReplace("<br><br><br>", "<br><br>", $data['final_text']);
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

    /**
     * @param string|null $content
     *
     * @return string|null
     */
    private function getPlainContent(?string $content): ?string
    {
        return $content
            ? Str::limit(strGetPlainText($content), 60)
            : null;
    }
}
