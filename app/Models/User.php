<?php

namespace App\Models;

use App\Casts\PhoneNumberCast;
use App\Casts\StringToLower;
use App\Traits\HasUUID;
use Bavix\Wallet\Interfaces\Customer;
use Bavix\Wallet\Traits\CanPay;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Stancl\VirtualColumn\VirtualColumn;

class User extends Authenticatable implements FilamentUser, HasName, Customer
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto {
        getProfilePhotoUrlAttribute as getPhotoUrl;
    }
    use CanPay;
    use VirtualColumn;
    use Notifiable;
    use HasUUID;

//    use Achiever;
//    use HasTenantTeams;
//    use Favoriter;
//    use SetsProfilePhotoFromUrl;
//    use HasConnectedAccounts;
//    use AuthenticationLoggable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
//        'city',
//        'position',
        'email',
        'phone',
//        'business',
//        'business_unit',
//        'education_region',
//        'education',
        'password',
        'gender',
//        'user_type',
//        'totalizator',
//        'timesheet_number',
//        'organization',
//        'members',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'name',
//        'profile_photo_url',
//        'user_referrer',
    ];

    protected $casts = [
        'phone' => PhoneNumberCast::class,
        'email' => StringToLower::class,
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
//    public function getProfilePhotoUrlAttribute()
//    {
//        $profilePhotoPath = $this->profile_photo_path ? tenant_asset($this->profile_photo_path) : url('images/avatar.svg');
//        if (filter_var($profilePhotoPath, FILTER_VALIDATE_URL)) {
//            return $profilePhotoPath;
//        }
//        return $this->getPhotoUrl();
//    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'uuid',
            'first_name',
            'middle_name',
            'last_name',
            'email',
            'email_verified_at',
            'phone',
            'password',
            'remember_token',
            'current_connected_account_id',
            'profile_photo_path',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @return \App\Models\Tenant\User|null
     */
//    public function getUserReferrerAttribute(): ?User
//    {
//        if ($this->user_type === FairyGamesUserType::REFERRAL) {
//            $team = $this->teams->first();
//            return $team->owner ?? null;
//        }
//        return null;
//    }

//    public function opponents()
//    {
//        return $this->belongsToMany(User::class, 'videochat_opponent_user', 'user_id', 'opponent_id');
//    }
//
//    public function findOpponent(User $user)
//    {
//        return $this->opponents()->find($user->id);
//    }
//
//    public function addOpponent(User $user)
//    {
//        $this->opponents()->attach($user->id);
//    }
//
//    public function removeOpponent(User $user)
//    {
//        $this->opponents()->detach($user->id);
//    }

//    public function scopeFilter($query, array $filters)
//    {
//        $query->when($filters['search'] ?? null, function ($query, $search) {
//            $query->where(function ($query) use ($search) {
//                $query
//                    ->where('email', 'ilike', '%' . $search . '%')
//                    ->orWhere('first_name', 'ilike', '%' . $search . '%')
//                    ->orWhere('middle_name', 'ilike', '%' . $search . '%')
//                    ->orWhere('last_name', 'ilike', '%' . $search . '%');
//            });
//        });
//    }

    /**
     * @return HasMany
     */
    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizUserAttempt::class);
    }

    /**
     * @return HasMany
     */
//    public function userResults(): HasMany
//    {
//        return $this->hasMany(UserResult::class);
//    }

    /**
     * @return HasMany
     */
//    public function likes(): HasMany
//    {
//        return $this->hasMany(Like::class);
//    }

    /**
     * @return HasMany
     */
//    public function cards(): HasMany
//    {
//        return $this->hasMany(Card::class);
//    }

    /**
     * @param Likeable $likeable
     *
     * @return $this
     */
//    public function like(Likeable $likeable): self
//    {
//        if ($this->hasLiked($likeable)) {
//            return $this;
//        }
//
//        (new Like())
//            ->user()->associate($this)
//            ->likeable()->associate($likeable)
//            ->save();
//
//        return $this;
//    }

    /**
     * @param Likeable $likeable
     *
     * @return $this
     */
//    public function unlike(Likeable $likeable): self
//    {
//        if (!$this->hasLiked($likeable)) {
//            return $this;
//        }
//
//        $likeable->likes()
//            ->whereHas('user', fn($q) => $q->whereId($this->id))
//            ->delete();
//
//        return $this;
//    }

    /**
     * @param Likeable $likeable
     *
     * @return bool
     */
//    public function hasLiked(Likeable $likeable): bool
//    {
//        if (!$likeable->exists) {
//            return false;
//        }
//
//        return $likeable->likes()
//            ->whereHas('user', fn($q) => $q->whereId($this->id))
//            ->exists();
//    }

    public function canAccessFilament(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->getFilamentName();
    }

    /**
     * @return string
     */
    public function getFilamentName(): string
    {
        $nameParts = Arr::where([
            ucfirst($this->first_name),
            ucfirst($this->middle_name),
            ucfirst($this->last_name),
        ], static function ($_item) {
            return !empty($_item);
        });

        return implode(" ", $nameParts);
    }
}
