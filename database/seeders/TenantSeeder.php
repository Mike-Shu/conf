<?php

namespace Database\Seeders;

use App\Enums\UserGender;
use App\Models\Tenant;
use App\Models\User;
use App\Settings\TenantSettings;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        // First tenant
        $this->createTenant("first")->run(function () {
            $this->createAdminUser();

            User::factory()
                ->count(14)
                ->create();
        });

        // Second tenant
        $this->createTenant("second")->run(function () {
            $this->createAdminUser();
        });

        // Third tenant
        $this->createTenant("third")->run(function () {
            $this->createAdminUser();
        });
    }

    /**
     * @param string $subdomain
     *
     * @return Tenant
     */
    private function createTenant(string $subdomain): Tenant
    {
        $tenant = Tenant::create();

        $tenant->domains()->create([
            'domain' => $subdomain . '.' . config('app.domain'),
        ]);

        $tenant->run(function () use ($subdomain) {
            $settings = app(TenantSettings::class);
            $settings->title = Str::ucfirst($subdomain) . " project";
            $settings->save();
        });

        return $tenant;
    }

    /**
     * @return User
     */
    private function createAdminUser(): User
    {
        return User::create([
            'first_name' => "Administrator",
            'gender' => UserGender::MALE,
            'email' => "admin@tenant.test",
            'password' => Hash::make('password'),
        ]);
    }
}
