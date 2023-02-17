<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Stancl\VirtualColumn\VirtualColumn;

class Timetable extends Model
{
    use HasFactory;
    use SoftDeletes;
    use VirtualColumn;

    protected $fillable = ['title'];

    protected $appends = ['active_tab_date'];

    /**
     * @return string[]
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'title',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    /**
     * @return HasMany
     */
    public function slots(): HasMany
    {
        return $this->hasMany(TimetableSlot::class)
            ->orderBy('start_datetime');
    }

    /**
     * Returns the date of the active tab (the closest date to the current date).
     *
     * @return string|null
     */
    public function getActiveTabDateAttribute(): ?string
    {
        $currentDate = now()->toDateString();

        $nearestSlot = $this->slots()
            ->whereDate('start_datetime', '>=', $currentDate)
            ->first();

        if ($nearestSlot) {
            return Carbon::parse($nearestSlot->start_datetime)->toDateString();
        }

        $nearestSlot = $this->slots()
            ->whereDate('start_datetime', '<', $currentDate)
            ->get()
            ->last();

        if ($nearestSlot) {
            return Carbon::parse($nearestSlot->start_datetime)->toDateString();
        }

        return null;
    }
}
