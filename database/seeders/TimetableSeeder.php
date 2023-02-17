<?php

namespace Database\Seeders;

use App\Enums\TimetableSlotGradient;
use App\Enums\TimetableSlotWidth;
use App\Models\Tenant;
use App\Models\Timetable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;

class TimetableSeeder extends Seeder
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
            /** @var Timetable $timetable */
            $timetable = Timetable::factory([
                'title' => "Stages of the event",
            ])->create();

            $slots = collect([
                [
                    'title' => "Start of the event",
                    'description' => "<p>Guest registration, rock band performance</p>",
                    'start_datetime' => now()->toDateTimeLocalString(),
                    'finish_datetime' => now()->addHour()->toDateTimeLocalString(),
                    'width' => TimetableSlotWidth::WIDTH_4_4,
                    'gradient' => TimetableSlotGradient::TRACK_MAIN,
                ],
                [
                    'title' => "The main program of the event",
                    'description' => "<p>Bar, karaoke, quiz, performances by musicians and more</p>",
                    'start_datetime' => now()->addHour()->toDateTimeLocalString(),
                    'finish_datetime' => now()->addHours(6)->toDateTimeLocalString(),
                    'link' => "https://www.google.com/",
                    'link_anchor' => "Full list of activities here",
                    'width' => TimetableSlotWidth::WIDTH_2_4,
                    'gradient' => TimetableSlotGradient::YOUTUBE,
                ],
                [
                    'title' => "Completion of the event",
                    'description' => "<p>Awarding the winners of the quiz, autographs of musicians, memorable gifts</p>",
                    'start_datetime' => now()->addHours(6)->toDateTimeLocalString(),
                    'finish_datetime' => now()->addHours(8)->toDateTimeLocalString(),
                    'width' => TimetableSlotWidth::WIDTH_2_4,
                    'gradient' => TimetableSlotGradient::MANGO_PULP,
                ],
            ]);

            $slots->each(function ($_slot) use ($timetable) {
                $timetable->slots()->create($_slot);
            });
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
