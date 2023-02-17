<?php

namespace Database\Seeders;

use App\Models\ShopProduct;
use App\Models\Tenant;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;

class ShopProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $this->getTenant("first")->run(function () {
            $products = collect([
                [
                    'title' => "Socks",
                    'description' => "<p>Color: white, size: XL</p>",
                    'price' => 10,
                    'visibility' => true,
                ],
                [
                    'title' => "Shorts",
                    'description' => "<p>Color: black, size: XL</p>",
                    'price' => 25,
                    'visibility' => true,
                ],
                [
                    'title' => "T-shirt",
                    'description' => "<p>Color: blue, size: XL</p>",
                    'price' => 30,
                    'visibility' => true,
                ],
            ]);

            $products->each(function ($_product) {
                $product = ShopProduct::create($_product);
                $product->mutateStock(10);
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
