<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\VirtualColumn\VirtualColumn;

class QuizUserAttempt extends Model
{
    use HasFactory;
    use VirtualColumn;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'correct_answers',
        'answers',
        'reward_amount',
    ];

    /**
     * @return string[]
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'user_id',
            'quiz_id',
            'correct_answers',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * Get the quiz that owns the question.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
