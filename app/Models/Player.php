<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\VirtualColumn\VirtualColumn;

class Player extends Model
{
    use HasFactory;
    use SoftDeletes;
    use VirtualColumn;

    protected $fillable = [
        'title',
        'link',
        'provider',
        'video_id',
        'thumbnail',
        'is_wrapper',
    ];

    protected $casts = [
        'is_wrapper' => "boolean",
    ];

    /**
     * @return string[]
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'title',
            'link',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }
}
