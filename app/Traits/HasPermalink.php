<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * To add a prefix to a URL, you need to add the $permalinkPrefix property to the model.
 * For example (http://example.com/quizzes/mathematics):
 * ```
 * protected string $permalinkPrefix = "quizzes";
 * ```
 *
 * To assign a key forcibly:
 * ```
 * protected string $permalinkKey = "uuid";
 * ```
 *
 * @package WeconfModules\Core\Traits
 */
trait HasPermalink
{
    /**
     * @return string
     */
    public function getPermalinkAttribute(): string
    {
        if (!empty($this->permalinkKey)) { // The key is assigned forcibly.
            if (Str::lower($this->permalinkKey) === "uuid") {
                $key = $this->uuid;
            }

            if (Str::lower($this->permalinkKey) === "slug") {
                $key = $this->slug;
            }
        } else { // The key is assigned automatically.
            if (!empty($this->uuid)) {
                $key = $this->uuid;
            }

            if (!empty($this->slug)) {
                $key = $this->slug;
            }
        }

        if (isset($key)) {
            $host = tenant()->host;

            if ($host) {
                $urlParts = [
                    $host,
                    $this->permalinkPrefix ?? "",
                    $key,
                ];

                return implode("/", array_filter($urlParts, "trim"));
            }
        }

        return "";
    }
}
