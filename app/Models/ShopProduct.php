<?php

namespace App\Models;

use App\Enums\WalletReasonSystem;
use App\Observers\ShopProductObserver;
use App\Traits\Stockable;
use Bavix\Wallet\Interfaces\Customer;
use Bavix\Wallet\Interfaces\ProductInterface;
use Bavix\Wallet\Traits\HasWallet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Stancl\VirtualColumn\VirtualColumn;

class ShopProduct extends Model implements ProductInterface
{
    use HasFactory;
    use HasWallet;
    use HasSlug;
    use SoftDeletes;
    use VirtualColumn;
    use Stockable;

    protected $fillable = [
        'title',
        'description',
        'price',
        'visibility',
    ];

    protected $appends = [
        'thumb',
        'stock_total',
    ];

    protected $casts = [
        'visibility' => "boolean",
    ];

    public static function boot(): void
    {
        parent::boot();

        self::observe(ShopProductObserver::class);
    }

    /**
     * @return string[]
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'slug',
            'title',
            'description',
            'price',
            'visibility',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    /**
     * @return MorphMany
     */
    public function pictures(): MorphMany
    {
        return $this->morphMany(Picture::class, 'model');
    }

    /**
     * @return MorphOne
     */
    public function firstPicture(): MorphOne
    {
        return $this->morphOne(Picture::class, 'model')->oldestOfMany();
    }

    /**
     * @param Customer $customer
     *
     * @return int
     */
    public function getAmountProduct(Customer $customer): int
    {
        return $this->price;
    }

    /**
     * @return array|null
     */
    public function getMetaProduct(): ?array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'visibility' => $this->visibility,
            'reason' => WalletReasonSystem::PURCHASE,
        ];
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * @param Builder $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('visibility', true);
    }

    /**
     * @return int
     */
    public function getStockTotalAttribute(): int
    {
        return (int)$this->stockMutations()
            ->where('amount', '>', 0)
            ->sum('amount');
    }

    /**
     * @return string|null
     */
    public function getThumbAttribute(): ?string
    {
        $picture = $this->firstPicture;

        if ($picture) {
            return $picture->thumbnail_square;
        }

        return null;
    }
}
