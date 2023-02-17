<?php

namespace App\Models;

use App\Enums\MediaStatus;
use App\Enums\MediaType;
use App\Observers\MediaObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\VirtualColumn\VirtualColumn;

class Media extends Model
{
    use HasFactory;
    use VirtualColumn;

    protected $fillable = [
        'user_id',
        'type',
        'status',
        'reason',
//        'city',
        'video_provider',
        'video_identifier',
        'points',
        'description',
        'comment',
    ];

    protected $casts = [
        'content' => AsCollection::class,
    ];

    protected $attributes = [
        'content' => "{}",
    ];

//    protected $appends = [
//        'media_item',
//        'likes_count',
//        'status_description',
//        'reason_description',
//        'video_provider_description',
//        'can_be_likeable',
//        'can_be_unlikeable',
//    ];

    public static function boot(): void
    {
        parent::boot();

        self::observe(MediaObserver::class);
    }

    /**
     * @return string[]
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'user_id',
            'type',
            'status',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return int
     */
//    public function getLikesCountAttribute(): int
//    {
//        return $this->likes()->count();
//    }

    /**
     * @return bool
     */
//    public function canBeLikeable(): bool
//    {
//        $user = request()->user();
//        $tenant = $user->currentTenant ?? tenant();
//
//        if ($tenant) {
//            return (bool)$tenant->universal_media_likeable;
//        }
//
//        return false;
//    }

    /**
     * @return bool
     */
//    public function getCanBeLikeableAttribute(): bool
//    {
//        return $this->canBeLikeable();
//    }

    /**
     * @return bool
     */
//    public function canBeUnlikeable(): bool
//    {
//        $user = request()->user();
//        $tenant = $user->currentTenant ?? tenant();
//
//        if ($tenant) {
//            return (bool)$tenant->universal_media_unlikeable;
//        }
//
//        return false;
//    }

    /**
     * @return bool
     */
//    public function getCanBeUnlikeableAttribute(): bool
//    {
//        return $this->canBeUnlikeable();
//    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeAccepted(Builder $query): Builder
    {
        return $query->where('status', MediaStatus::ACCEPTED);
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeTypeImage(Builder $query): Builder
    {
        return $query->where('type', MediaType::IMAGE);
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeTypeVideo(Builder $query): Builder
    {
        return $query->where('type', MediaType::VIDEO);
    }
}
