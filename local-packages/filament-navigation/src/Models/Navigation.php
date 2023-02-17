<?php

namespace RyanChandler\FilamentNavigation\Models;

use App\Settings\TenantSettings;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $handle
 * @property array $items
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Navigation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'items' => 'json',
    ];

    protected $appends = [
        'is_active',
    ];

    /**
     * @return bool
     */
    public function getIsActiveAttribute(): bool
    {
        return app(TenantSettings::class)->menu === $this->handle;
    }
}
