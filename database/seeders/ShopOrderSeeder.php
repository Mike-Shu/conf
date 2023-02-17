<?php

namespace Database\Seeders;

use App\Enums\WalletReasonSystem;
use App\Models\Tenant;
use App\Models\User;
use App\Services\ShopService;
use Bavix\Wallet\Internal\Exceptions\ExceptionInterface;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ShopOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function run(): void
    {
        $this->getTenant("first")->run(function () {
            $orders = collect([
                [
                    'user_id' => 1,
                    'products' => [
                        [
                            'product_id' => 1,
                            'price' => 10,
                            'amount' => 2,
                        ],
                        [
                            'product_id' => 2,
                            'price' => 25,
                            'amount' => 1,
                        ],
                    ],
                    'address' => "N Los Robles Ave, Pasadena, CA 91101",
                ],
                [
                    'user_id' => 2,
                    'products' => [
                        [
                            'product_id' => 1,
                            'price' => 10,
                            'amount' => 3,
                        ],
                        [
                            'product_id' => 3,
                            'price' => 30,
                            'amount' => 2,
                        ],
                    ],
                    'address' => null,
                ],
            ]);

            $orders->each(function ($_order) {
                $user = User::find(Arr::pull($_order, 'user_id'));

                $user->deposit(300, [
                    'reason' => WalletReasonSystem::SEEDER,
                ]);

                app(ShopService::class)->buyProducts($_order, $user);
            });
        });
    }

    /**
     * @param string $subdomain
     * @return Tenant
     */
    private function getTenant(string $subdomain): Tenant
    {
        return Tenant::whereHas('domains', static function (Builder $query) use ($subdomain) {
            $query->where('domain', $subdomain . '.' . config('app.domain'));
        })
            ->with('domains')
            ->first();
    }
}
