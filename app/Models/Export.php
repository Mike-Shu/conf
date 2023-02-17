<?php

namespace App\Models;

use App\Observers\ExportObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Stancl\VirtualColumn\VirtualColumn;

class Export extends Model
{
    use HasFactory;
    use VirtualColumn;

    protected $fillable = [
        'entity',
        'filename',
        'type',
        'batch_progress',
        'batch_finished',
    ];

    protected $appends = [
        'file_url',
        'file_size',
    ];

    public static function boot(): void
    {
        parent::boot();

        self::observe(ExportObserver::class);
    }

    /**
     * @return string[]
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'entity',
            'filename',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @return string|null
     */
    public function getFileUrlAttribute(): ?string
    {
        return $this->getFilePath()
            ? route('files.exports', $this->filename)
            : null;
    }

    /**
     * @return string|null
     */
    public function getFileSizeAttribute(): ?string
    {
        $filePath = $this->getFilePath();

        return $filePath
            ? formatFileSize(Storage::size($filePath))
            : null;
    }

    /**
     * @return string|null
     */
    public function getFilePath(): ?string
    {
        $filePath = config('export.files.folder') . '/' . $this->filename;

        if (Storage::exists($filePath)) {
            return $filePath;
        }

        return null;
    }
}
