<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Tenant;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $this->getTenant("first")->run(function () {
            $quizzes = collect([
                [
                    'data' => [
                        'title' => "Entertaining mathematics",
                        'reward_try' => 10,
                        'reward_answer' => 3,
                        'final_text' => "<p>Awesome! You have successfully completed this quiz.</p>",
                        'shuffle_questions' => false,
                        'shuffle_answers' => false,
                    ],
                    'questions' => collect([
                        [
                            'data' => [
                                'content' => "<p>2 + 2 =</p>",
                                'sort' => 1,
                            ],
                            'answers' => collect([
                                [
                                    'content' => "<p>3</p>",
                                    'sort' => 1,
                                ],
                                [
                                    'content' => "<p>4</p>",
                                    'correct' => true,
                                    'sort' => 2,
                                ],
                                [
                                    'content' => "<p>5</p>",
                                    'sort' => 3,
                                ],
                            ]),
                        ],
                        [
                            'data' => [
                                'content' => "<p>5 * 5 =</p>",
                                'sort' => 2,
                            ],
                            'answers' => collect([
                                [
                                    'content' => "<p>10</p>",
                                    'sort' => 1,
                                ],
                                [
                                    'content' => "<p>25</p>",
                                    'correct' => true,
                                    'sort' => 2,
                                ],
                            ]),
                        ],
                    ]),
                ],
            ]);

            $quizzes->each(function ($_quiz) {
                $quiz = Quiz::create($_quiz['data']);

                $_quiz['questions']->each(function ($_question) use ($quiz) {
                    $question = $quiz->questions()->create($_question['data']);

                    $_question['answers']->each(function ($_answer) use ($question) {
                        $question->answers()->create($_answer);
                    });
                });
            });
        });
    }

    /**
     * @param string $subdomain
     *
     * @return Tenant
     */
    private function getTenant(string $subdomain): Tenant
    {
        return Tenant::whereHas('domains', static function (Builder $query) use ($subdomain) {
            $query->where('domain', $subdomain . '.' . config('app.domain'));
        })
            ->with('domains')
            ->first();
    }
}
