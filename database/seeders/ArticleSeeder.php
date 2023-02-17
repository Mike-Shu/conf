<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Tenant;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ArticleSeeder extends Seeder
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
            $articles = collect([
                [
                    'title' => "SpaceX destacks Starship to prep for epic 33-engine test fire",
                    'content' => "<p>The coming static fire is one of the last boxes to check before Starship's first orbital test flight.</p>",
                    'visibility' => true,
                    'tags' => ["space", "spacex", "starship"],
                ],
                [
                    'title' => "Car-sized asteroid will pass extremely close to Earth tonight",
                    'content' => "<p>A small space rock is about to make a super close pass at Earth tonight, but don't worry — we are perfectly safe.</p>",
                    'visibility' => true,
                    'tags' => ["space", "asteroid", "earth"],
                ],
                [
                    'title' => "Save over $200 on the Panasonic Lumix G100 mirrorless camera",
                    'content' => "<p>That's a 27% saving on a 4K mirrorless camera that we rate as one of the very best for beginners on the market, it won't last long though as they're currently low in stock.</p>",
                    'visibility' => true,
                    'tags' => ["space", "camera"],
                ],
            ]);

            $articles->each(function ($_article) {
                $tags = Arr::pull($_article, 'tags');
                $article = Article::create($_article);
                $article->attachTags($tags, "articles");
            });
        });
    }

    /**
     * @param string $subdomain
     *
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
