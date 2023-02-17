<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class VideoProvider extends Enum implements LocalizedEnum
{
    public const YOUTUBE = "youtube";
    public const VIMEO = "vimeo";
    public const MUX = "mux";
    public const RUTUBE = "rutube";
    public const FACECAST = "facecast";
}
