<?php

namespace Database\Seeders;

use App\Enums\VideoProvider;
use App\Models\Player;
use App\Models\Tenant;
use App\Services\VideoLinkService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
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
            $videoLinks = [
                VideoProvider::YOUTUBE => 'https://www.youtube.com/watch?v=t99KH0TR-J4',
                VideoProvider::VIMEO => "https://vimeo.com/59474226",
                VideoProvider::MUX => 'https://stream.mux.com/cBgaCCGtJpXNU26UjkgcsWcfZUCCvwWw.m3u8',
                VideoProvider::RUTUBE => 'https://rutube.ru/video/9f3986e93db8c66a6fce4afe25f21cd1/',
                VideoProvider::FACECAST => 'https://facecast.net/v/pybh3r',
            ];

            $service = app(VideoLinkService::class);

            foreach ($videoLinks as $_provider => $_link) {
                $linkData = $service->parseVideoUrl($_link);

                Player::factory([
                    'title' => VideoProvider::getDescription($_provider) . " video",
                    'link' => $_link,
                    'thumbnail' => $service->getThumbnailByUrl($_link),
                    'provider' => $linkData['provider'],
                    'video_id' => $linkData['id'],
                ])->create();
            }
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
