<?php

namespace App\Jobs;

use App\Enums\MediaType;
use App\Models\Media;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\File;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SaveMediaToLocalStorageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Media $media;

    /**
     * Create a new job instance.
     *
     * @param Media $media
     */
    public function __construct(Media $media)
    {
        $this->media = $media;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $tmpFilePath = storage_path("app/{$this->media->tmp_file}");
        $path = Storage::disk('public')->putFile('media', new File($tmpFilePath));
        Storage::delete($this->media->tmp_file);

        $transformCollection = collect();

        if ($this->media->type === MediaType::IMAGE) {
            $transformations = config('uploadio.transformations');

            foreach ($transformations as $_transformation) {
                $transformCollection->put($_transformation, tenant_asset($path));
            }
        } else {
            $transformCollection->put('file', tenant_asset($path));
        }

        $this->media->forceFill([
            'content' => $transformCollection,
            'local_file_path' => $path,
            'tmp_file' => null,
        ])->save();

        logger("Saved media to local storage: '{$path}'");
    }
}
