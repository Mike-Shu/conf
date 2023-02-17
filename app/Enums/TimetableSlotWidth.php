<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class TimetableSlotWidth extends Enum implements LocalizedEnum
{
    public const WIDTH_1_4 = "1/4";
    public const WIDTH_2_4 = "2/4";
    public const WIDTH_3_4 = "3/4";
    public const WIDTH_4_4 = "4/4";
}
