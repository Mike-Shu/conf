<?php

namespace App\Observers;

use App\Jobs\ExportDeleteFileJob;
use App\Models\Export;

class ExportObserver
{
    public bool $afterCommit = true;

    /**
     * Handle the Export "deleted" event.
     *
     * @param Export $export
     *
     * @return void
     */
    public function deleted(Export $export): void
    {
        $filepath = $export->getFilePath();

        if ($filepath) {
            ExportDeleteFileJob::dispatch($filepath);
        }
    }
}
