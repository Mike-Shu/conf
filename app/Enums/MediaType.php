<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class MediaType extends Enum implements LocalizedEnum
{
    public const IMAGE = 'image';
    public const VIDEO = 'video';
}
