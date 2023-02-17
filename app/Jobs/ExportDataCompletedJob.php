<?php

namespace App\Jobs;

use App\Models\Export;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExportDataCompletedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    private array $details;

    /**
     * Create a new job instance.
     *
     * @param array $details
     */
    public function __construct(array $details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        Tenant::find($this->details['tenant_id'])->run(function () {
            Export::find($this->details['export_id'])->update([
                'batch_finished' => true,
            ]);
        });

        logger("Export data [4]: entity '{$this->details['entity']}', export completed");
    }
}
