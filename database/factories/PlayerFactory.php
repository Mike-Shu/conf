<?php

namespace Database\Factories;

use App\Enums\VideoProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => "Тестовое видео",
            'link' => "https://vimeo.com/59474226",
            'thumbnail' => "https://i.vimeocdn.com/video/413347642-ee9c0136593de27d2f0d36e2251b84d0d605d56e1f155f2dc426e8549dddb9f1-d_200x150",
            'provider' => VideoProvider::VIMEO,
            'video_id' => "59474226",
        ];
    }
}
