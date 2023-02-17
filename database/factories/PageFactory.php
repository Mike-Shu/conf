<?php

namespace Database\Factories;

use App\Enums\PageTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->text(50);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->faker->text(),
            'template' => PageTemplate::DEFAULT,
        ];
    }
}
