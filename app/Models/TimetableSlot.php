<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\VirtualColumn\VirtualColumn;

class TimetableSlot extends Model
{
    use HasFactory;
    use SoftDeletes;
    use VirtualColumn;

    protected $fillable = [
        'title',
        'description',
        'start_datetime',
        'finish_datetime',
        'link',
        'link_anchor',
        'width',
        'gradient',
    ];

    protected $casts = [
        'start_datetime' => "datetime",
        'finish_datetime' => "datetime",
    ];

//    protected $touches = ['timetable'];

    /**
     * @return BelongsTo
     */
    public function timetable(): BelongsTo
    {
        return $this->belongsTo(Timetable::class);
    }

    /**
     * @return string[]
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'timetable_id',
            'title',
            'description',
            'start_datetime',
            'finish_datetime',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }
}
