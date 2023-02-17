<?php

namespace App\Models;

use App\Settings\TenantSettings;
use App\Traits\HasPermalink;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Stancl\VirtualColumn\VirtualColumn;

class Page extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;
    use VirtualColumn;
    use HasPermalink;

    protected $fillable = [
        'title',
        'is_title_hidden',
        'slug',
        'content',
        'template',
        'player',
        'chat',
    ];

    protected $appends = [
        'permalink',
        'is_home_page',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        $slugOptions = SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->skipGenerateWhen(fn() => is_null($this->title));

        if (!is_null($this->slug)) {
            $slugOptions->doNotGenerateSlugsOnUpdate();
        }

        return $slugOptions;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return string[]
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'title',
            'slug',
            'content',
            'template',
            'status',
            'visibility',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    /**
     * @return bool
     */
    public function getIsHomePageAttribute(): bool
    {
        $tenant = tenant();

        if ($tenant) {
            return (int)app(TenantSettings::class)->home_page === $this->id;
        }

        return false;
    }

    /**
     * @param Builder $query
     * @param string $slug
     *
     * @return Builder
     */
    public function scopeBySlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug);
    }
}
