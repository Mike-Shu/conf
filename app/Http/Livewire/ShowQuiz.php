<?php

namespace App\Http\Livewire;

use App\Enums\WalletReasonMain;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class ShowQuiz extends Component
{
    public Quiz $quiz;
    public int $currentQuestionNumber = 1;
    public Collection $userAnswers;
    public ?int $singleAnswer = null;
    public array $multipleAnswer = [];
    public ?int $userCorrectAnswersCount = null;

    public bool $isQuizPassed = false;
    public bool $selected = false;
    public bool $loading = false;

    public function mount(): void
    {
        $this->userAnswers = collect();
        $userAttempt = Request::user()->quizAttempts()->where('quiz_id', $this->quiz->id)->first();
        $this->isQuizPassed = !is_null($userAttempt);

        if ($this->isQuizPassed) {
            $this->userCorrectAnswersCount = $userAttempt->correct_answers;
        } else {
            $this->quiz->load([
                'questions' => function ($query) {
                    $query->with('answers');
                }
            ]);
        }
    }

    public function updatedMultipleAnswer(): void
    {
        $this->selected = !empty($this->multipleAnswer);
    }

    /**
     * @param QuizQuestion $question
     */
    public function applyAnswer(QuizQuestion $question): void
    {
        if ($question->multiple) {
            $answersIds = array_map("intval", $this->multipleAnswer);

            $questionAnswers = $question->answers
                ->where('correct', true)
                ->pluck('id');

            $diff = $questionAnswers->diff($answersIds);

            $this->userAnswers->push([
                'question_id' => $question->id,
                'question_multiple' => true,
                'user_answer_ids' => $answersIds,
                'correct' => $diff->isEmpty(),
            ]);
        } else {
            $this->userAnswers->push([
                'question_id' => $question->id,
                'question_multiple' => false,
                'user_answer_id' => $this->singleAnswer,
                'correct' => $question->answers->find($this->singleAnswer)->correct,
            ]);
        }

        $this->nextQuestion();
    }

    /**
     * @param QuizQuestion $question
     */
    public function skipQuestion(QuizQuestion $question): void
    {
        $this->userAnswers->push([
            'question_id' => $question->id,
            'question_multiple' => $question->multiple,
            'skipped' => true,
        ]);

        $this->nextQuestion();
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.show-quiz');
    }

    private function nextQuestion(): void
    {
        if ($this->userAnswers->count() === $this->quiz->questions->count()) {
            $this->calculateResult();
        } else {
            $this->multipleAnswer = [];
            $this->singleAnswer = null;
            $this->selected = false;
            $this->loading = false;
            $this->currentQuestionNumber++;
        }
    }

    private function calculateResult(): void
    {
        $this->userCorrectAnswersCount = $this->userAnswers
            ->where('correct', true)
            ->count();

        $user = Request::user();

        $rewardAmount = (int)$this->quiz->reward_try + (int)$this->quiz->reward_answer * $this->userCorrectAnswersCount;

        $user->quizAttempts()->create([
            'quiz_id' => $this->quiz->id,
            'correct_answers' => $this->userCorrectAnswersCount,
            'answers' => $this->userAnswers,
            'reward_amount' => $rewardAmount,
        ]);

        if ($rewardAmount) {
            $user->deposit($rewardAmount, [
                'reason' => WalletReasonMain::QUIZ,
                'entity_id' => $this->quiz->id,
            ]);

            Notification::make()
                ->title(__('You have earned bonus points'))
                ->success()
                ->send();
        }
    }
}
