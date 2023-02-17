<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\VirtualColumn\VirtualColumn;

class QuizQuestionAnswer extends Model
{
    use HasFactory;
    use VirtualColumn;

    protected $fillable = [
        'content',
        'correct',
        'sort',
    ];

    protected $casts = [
        'correct' => "boolean",
    ];

    /**
     * @return string[]
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'quiz_question_id',
            'content',
            'correct',
            'sort',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @return BelongsTo
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class);
    }
}
