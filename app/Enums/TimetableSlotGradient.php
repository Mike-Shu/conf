<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class TimetableSlotGradient extends Enum implements LocalizedEnum
{
    public const TRACK_MAIN = "track-main";
    public const TRACK_SPORT = "track-sport";
    public const TRACK_CREATIVITY = "track-creativity";
    public const TRACK_MIND = "track-mind";
    public const MOJITO = "mojito";
    public const YODA = "yoda";
    public const QUEPAL = "quepal";
    public const ELECTRIC_VIOLET = "electric-violet";
    public const JUICY_ORANGE = "juicy-orange";
    public const CELESTIAL = "celestial";
    public const CHERRY = "cherry";
    public const STRIPE = "stripe";
    public const MANGO_PULP = "mango-pulp";
    public const AUBERGINE = "aubergine";
    public const ROSE_WATER = "rose-water";
    public const COOL_BROWN = "cool-brown";
    public const YOUTUBE = "youtube";
}
