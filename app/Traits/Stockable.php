<?php

namespace App\Traits;

use App\Models\ShopStock;
use Appstract\Stock\HasStock;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Stockable
{
    use HasStock;

    /**
     * Relation with StockMutation.
     *
     * @return morphMany
     */
    public function stockMutations(): MorphMany
    {
        return $this->morphMany(ShopStock::class, 'stockable');
    }
}
