<?php

namespace Database\Factories;

use App\Enums\TimetableSlotGradient;
use App\Enums\TimetableSlotWidth;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class TimetableSlotFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->title(),
            'description' => $this->faker->text(100),
            'start_datetime' => now()->toDateTimeLocalString(),
            'finish_datetime' => now()->addHour()->toDateTimeLocalString(),
            'link' => "https://www.google.com/",
            'link_anchor' => "Google",
            'width' => TimetableSlotWidth::WIDTH_1_4,
            'gradient' => TimetableSlotGradient::TRACK_MAIN,
        ];
    }
}
