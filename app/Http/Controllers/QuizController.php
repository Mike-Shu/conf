<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class QuizController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param Quiz $quiz
     *
     * @return Application|Factory|View
     */
    public function show(Quiz $quiz): Application|Factory|View
    {
        return view('pages.quiz', [
            'quiz' => $quiz,
        ]);
    }
}
