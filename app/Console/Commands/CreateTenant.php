<?php

namespace App\Console\Commands;

use App\Enums\UserGender;
use App\Models\Tenant;
use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:create {domain?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create tenant with domain.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $domain = $this->argument('domain');

        if ($domain) {
            if (Str::contains($domain, ".")) {
                try {
                    $tenant = Tenant::create([
                        'title' => Str::random(8),
                    ]);

                    $tenant->domains()->create([
                        'domain' => $domain,
                    ]);
                } catch (Exception $e) {
                    $this->error("Error: " . Str::lcfirst($e->getMessage()));
                    return 0;
                }
            } else {
                $this->warn("Warning: the domain must be fully qualified. For example: first.conf.test");
                return 0;
            }
        } else {
            $tenant = Tenant::create([
                'title' => Str::random(8),
            ]);
        }

        $tenant->run(function () {
            User::create([
                'first_name' => "root",
                'email' => "root@root.root",
                'password' => Hash::make('conf'),
            ]);
        });

        $this->info("Tenant created successfully");
        return 0;
    }
}
