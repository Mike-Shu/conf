<?php

namespace App\Jobs;

use App\Enums\MediaType;
use App\Models\Media;
use App\UploadIO\UploadIO;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class SaveMediaToCloudJob implements ShouldQueue
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
     * @param UploadIO $cloud
     *
     * @return void
     */
    public function handle(UploadIO $cloud): void
    {
        try {
            $tmpFilepath = storage_path("app/{$this->media->tmp_file}");
            $cloudFileData = $cloud->upload($tmpFilepath);
            File::delete($tmpFilepath);

            $transformCollection = collect();

            if ($this->media->type === MediaType::IMAGE) {
                $transformCollection = $cloud->getTransformationsCollection($cloudFileData['fileUrl']);
            } else {
                $transformCollection->put('file', $cloudFileData['fileUrl']);
            }

            $this->media->forceFill([
                'content' => $transformCollection,
                'uploadio_file_path' => $cloudFileData['filePath'],
                'tmp_file' => null,
            ])->save();

            logger("Saved media to cloud: '{$cloudFileData['filePath']}'");
        } catch (RuntimeException | FileNotFoundException | RequestException $e) {
            Log::error($e->getMessage());
        }
    }
}
