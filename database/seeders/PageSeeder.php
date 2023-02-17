<?php

namespace Database\Seeders;

use App\Enums\PageTemplate;
use App\Models\Page;
use App\Models\Tenant;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
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
            Page::create([
                'title' => "Home page",
                'is_title_hidden' => true,
                'template' => PageTemplate::PLAYER_WITH_CHAT,
                'player' => "1",
                'chat' => "1",
            ]);

            Page::create([
                'title' => "News",
                'is_title_hidden' => false,
                'slug' => "news",
                'content' => "<p>This is our news feed.</p>",
                'template' => PageTemplate::DEFAULT,
            ]);

            Page::create([
                'title' => "Ratings",
                'is_title_hidden' => false,
                'template' => PageTemplate::DEFAULT,
            ]);
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
