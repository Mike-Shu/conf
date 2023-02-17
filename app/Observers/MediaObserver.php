<?php

namespace App\Observers;

use App\Jobs\DeleteFileFromLocalStorageJob;
use App\Jobs\SaveMediaToLocalStorageJob;
use App\Jobs\SaveMediaToCloudJob;
use App\Jobs\DeleteFileFromCloudJob;
use App\Models\Media;
use Illuminate\Support\Facades\Log;

class MediaObserver
{
    public bool $afterCommit = true;

    /**
     * Handle the Media "created" event.
     *
     * @param Media $media
     *
     * @return void
     */
    public function created(Media $media): void
    {
        if ($media->tmp_file) {
            if (config('filesystems.default') === "uploadio") {
                SaveMediaToCloudJob::dispatch($media);
            } elseif (config('filesystems.default') === "local") {
                SaveMediaToLocalStorageJob::dispatch($media);
            } else {
                Log::error("An unsupported disk was specified for the file system. Check the FILESYSTEM_DISK setting.");
            }
        }
    }

    /**
     * Handle the Media "deleted" event.
     *
     * @param Media $media
     *
     * @return void
     */
    public function deleted(Media $media): void
    {
        if ($media->uploadio_file_path) {
            DeleteFileFromCloudJob::dispatch($media->uploadio_file_path);
        } elseif ($media->local_file_path) {
            DeleteFileFromLocalStorageJob::dispatch($media->local_file_path);
        }
    }
}
