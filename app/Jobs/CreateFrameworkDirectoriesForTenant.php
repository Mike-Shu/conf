<?php

namespace App\Jobs;

use RuntimeException;
use Stancl\Tenancy\Contracts\Tenant;

class CreateFrameworkDirectoriesForTenant
{
    private Tenant $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function handle(): void
    {
        $this->tenant->run(function () {
            // @see https://tenancyforlaravel.com/docs/v3/realtime-facades/
            $dirPath = storage_path() . '/framework/cache';

            if (!mkdir($dirPath, 0777, true) && !is_dir($dirPath)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $dirPath));
            }
        });
    }
}
