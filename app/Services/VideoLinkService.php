<?php

namespace App\Services;

use App\Enums\VideoProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VideoLinkService
{
    /**
     * @param string $url
     *
     * @return array|null
     */
    public function parseVideoUrl(string $url): ?array
    {
        $youtubeId = $this->getYoutubeId($url);
        if ($youtubeId) {
            return [
                'provider' => VideoProvider::YOUTUBE,
                'id' => $youtubeId,
            ];
        }

        $vimeoId = $this->getVimeoId($url);
        if ($vimeoId) {
            return [
                'provider' => VideoProvider::VIMEO,
                'id' => $vimeoId,
            ];
        }

        $muxId = $this->getMuxId($url);
        if ($muxId) {
            return [
                'provider' => VideoProvider::MUX,
                'id' => $muxId,
            ];
        }

        $rutubeId = $this->getRutubeId($url);
        if ($rutubeId) {
            return [
                'provider' => VideoProvider::RUTUBE,
                'id' => $rutubeId,
            ];
        }

        $facecastId = $this->getFacecastId($url);
        if ($facecastId) {
            return [
                'provider' => VideoProvider::FACECAST,
                'id' => $facecastId,
            ];
        }

        return null;
    }

    /**
     * @param string $url
     *
     * @return string|null
     */
    public function getThumbnailByUrl(string $url): ?string
    {
        $youtubeId = $this->getYoutubeId($url);
        if ($youtubeId) {
            // https://stackoverflow.com/questions/2068344/how-do-i-get-a-youtube-video-thumbnail-from-the-youtube-api
            return "http://img.youtube.com/vi/{$youtubeId}/default.jpg";
        }

        if ($this->getVimeoId($url)) {
            try {
                // https://developer.vimeo.com/api/oembed/videos
                $payload = file_get_contents("https://vimeo.com/api/oembed.json?url={$url}&height=120");
                if ($payload) {
                    $payload = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
                    return $payload['thumbnail_url'] ?? null;
                }
            } catch (\Exception $e) {
                Log::error("Get Vimeo video thumbnail: " . $e->getMessage());
            }
        }

        $muxId = $this->getMuxId($url);
        if ($muxId) {
            // https://docs.mux.com/guides/video/get-images-from-a-video#get-an-image-from-a-video
            return "https://image.mux.com/$muxId/thumbnail.png?height=80&fit_mode=preserve";
        }

        if ($this->getRutubeId($url)) {
            try {
                // https://vk.com/topic-49008312_28268593 (look for a *.doc file)
                $payload = file_get_contents("http://rutube.ru/api/oembed/?url={$url}&format=json");
                if ($payload) {
                    $payload = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
                    if (!empty($payload['thumbnail_url'])) {
                        // Available sizes: l - large (default), m - medium, s - small.
                        return Str::replace('size=l', 'size=s', $payload['thumbnail_url']);
                    }
                }
            } catch (\Exception $e) {
                Log::error("Get Rutube video thumbnail: " . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * @param string $url
     *
     * @return string|null
     */
    public function getYoutubeId(string $url): ?string
    {
        preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^&\"'>]+)/",
            $url, $matches);

        return $matches[1] ?? null;
    }

    /**
     * @param string $url
     *
     * @return string|null
     */
    public function getVimeoId(string $url): ?string
    {
        preg_match('#(?:https?://)?(?:www.)?(?:player.)?vimeo.com/(?:[a-z]*/)*(\d{6,11})[?]?.*#', $url, $matches);

        return $matches[1] ?? null;
    }

    /**
     * @param string $url
     *
     * @return string|null
     */
    public function getMuxId(string $url): ?string
    {
        $string = Str::lower($url);
        if (Str::contains($string, "stream.mux.com") && Str::endsWith($string, "m3u8")) {
            return Str::between($url, "stream.mux.com/", ".m3u8");
        }

        return null;
    }

    /**
     * @param string $url
     *
     * @return string|null
     */
    public function getRutubeId(string $url): ?string
    {
        preg_match('/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:rutube\.ru\/(?:video|play\/embed)\/([a-zA-Z0-9_\-]+))/', $url,
            $matches);

        return $matches[1] ?? null;
    }

    /**
     * @param string $url
     *
     * @return string|null
     */
    public function getFacecastId(string $url): ?string
    {
        preg_match('/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:facecast\.net\/(?:v)\/([a-zA-Z0-9_\-]+))/', $url,
            $matches);

        return $matches[1] ?? null;
    }
}
