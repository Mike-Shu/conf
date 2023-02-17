<?php

namespace App\Models;

use App\Traits\HasPermalink;
use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Stancl\VirtualColumn\VirtualColumn;

class Quiz extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;
    use VirtualColumn;
    use HasUUID;
    use HasPermalink;

    protected $fillable = [
        'slug',
        'title',
        'reward_try',
        'reward_answer',
        'final_text',
        'shuffle_questions',
        'shuffle_answers',
    ];

    protected $appends = ['permalink'];

    protected $casts = [
        'shuffle_questions' => "boolean",
        'shuffle_answers' => "boolean",
    ];

    protected $permalinkPrefix = "quizzes";

    protected $permalinkKey = "slug";

    /**
     * @return string[]
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'uuid',
            'slug',
            'title',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    /**
     * @return HasMany
     */
    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }

    /**
     * @return SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        $slugOptions = SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->skipGenerateWhen(fn() => is_null($this->title));

        if (!is_null($this->slug)) {
            $slugOptions->doNotGenerateSlugsOnUpdate();
        }

        return $slugOptions;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return "slug";
    }
}
