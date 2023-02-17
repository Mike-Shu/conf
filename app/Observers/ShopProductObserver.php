<?php

namespace App\Observers;

use App\Models\ShopProduct;

class ShopProductObserver
{
    public bool $afterCommit = true;

    /**
     * Handle the Product "forceDeleted" event.
     *
     * @param ShopProduct $product
     * @return void
     */
    public function forceDeleted(ShopProduct $product): void
    {
        $product->pictures->each(function ($_picture) {
            $_picture->delete();
        });
    }
}
