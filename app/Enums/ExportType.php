<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class ExportType extends Enum implements LocalizedEnum
{
    public const DATA = 'data';
    public const FILES = 'files';
}
