<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Stancl\VirtualColumn\VirtualColumn;

class ShopOrder extends Model
{
    use HasFactory;
    use VirtualColumn;

    protected $fillable = [
        'user_id',
        'address',
    ];

    /**
     * @return string[]
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'user_id',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(ShopOrderItem::class, 'order_id')
            ->orderByDesc('created_at');
    }

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
