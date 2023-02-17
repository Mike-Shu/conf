<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Stancl\VirtualColumn\VirtualColumn;

class QuizQuestion extends Model
{
    use HasFactory;
    use VirtualColumn;

    protected $fillable = [
        'content',
        'multiple',
        'sort',
    ];

    protected $casts = [
        'multiple' => "boolean",
    ];

    /**
     * @return string[]
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'quiz_id',
            'content',
            'multiple',
            'sort',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @return BelongsTo
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * @return HasMany
     */
    public function answers(): HasMany
    {
        return $this->hasMany(QuizQuestionAnswer::class)->orderBy('sort');
    }
}
