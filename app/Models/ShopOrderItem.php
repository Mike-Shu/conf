<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Stancl\VirtualColumn\VirtualColumn;

class ShopOrderItem extends Model
{
    use HasFactory;
    use VirtualColumn;

    protected $fillable = [
        'order_id',
        'product_id',
        'amount',
        'price',
        'product_details',
    ];

    /**
     * @return string[]
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'order_id',
            'product_id',
            'amount',
            'price',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(ShopOrder::class);
    }

    /**
     * @return HasOne
     */
    public function product(): HasOne
    {
        return $this->hasOne(ShopProduct::class, 'id', 'product_id');
    }
}
