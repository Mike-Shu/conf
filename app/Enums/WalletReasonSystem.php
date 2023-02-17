<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * The list of reasons is only for internal use in the system (not for display in the admin panel).
 *
 * @package App\Enums
 */
class WalletReasonSystem extends Enum implements LocalizedEnum
{
    public const TOTALIZATOR_BET = 'totalizator_bet';
    public const TOTALIZATOR_WIN = 'totalizator_win';
    public const TOTALIZATOR_REFUND = 'totalizator_refund';
    public const BONUS_ONE = 'bonus_one';
    public const GLOBAL_POLL = 'global_poll';
    public const FIRST_LOGIN = 'first_login';
    public const TIMETABLE_ACTIVATED = 'timetable_activated';
    public const PURPOSE = 'purpose';
    public const IMPORT = 'import';
    public const SEEDER = 'seeder';
    public const CORRECT_REGISTRATION = 'correct_registration';
    public const LIKES = 'likes';
    public const FILLED_PHONE = 'filled_phone';
    public const DAILY_VISIT = 'daily_visit';
    public const TOAST_CLICK = 'toast_click';
    public const TRANSFER = 'transfer';
    public const PURCHASE = 'purchase';
    public const BONUS = 'bonus';
}
