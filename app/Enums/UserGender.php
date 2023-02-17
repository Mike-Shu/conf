<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class UserGender extends Enum implements LocalizedEnum
{
    public const MALE = "male";
    public const FEMALE = "female";
}
