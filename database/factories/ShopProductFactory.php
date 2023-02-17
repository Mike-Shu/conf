<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class ShopProductFactory extends Factory
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
            'price' => ($this->faker->randomDigit() + 1) * 10,
        ];
    }
}
