<?php

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * The main list of reasons (for display in the admin panel and for internal use in the system).
 *
 * @package App\Enums
 */
class WalletReasonMain extends Enum implements LocalizedEnum
{
    public const RECOMMENDATIONS = 'recommendations';
    public const FUNFAIR = 'funfair';
    public const CORRECTION = 'correction';
    public const CARD = 'card';
    public const QUIZ = 'quiz';
    public const INSTAGRAM = 'instagram';
    public const VKONTAKTE = 'vkontakte';
    public const CROSSWORD = 'crossword';
    public const FILLWORD = 'fillword';
    public const CHECKERS = 'checkers';
    public const CHESS = 'chess';
    public const SPORTS_CHALLENGE = 'sports_challenge';
    public const MIND_FITNESS = 'mind_fitness';
    public const GIFT_WRAPPING = 'gift_wrapping';
    public const BIRTHDAY = 'birthday';
    public const FOOTBALL_FREESTYLE = 'football_freestyle';
    public const BELLY_DANCE = 'belly_dance';
    public const TRAVELS = 'travels';
    public const QUILLING = 'quilling';
    public const POWER_TRAINING = 'power_training';
    public const FASHION_SCIENCE = 'fashion_science';
    public const JUMP_STYLE = 'jump_style';
    public const CHILD_SUIT = 'child_suit';
    public const FLORISTICS = 'floristics';
    public const GRAFFITI = 'graffiti';
    public const FIT_XIKI = 'fit_xiki';
    public const VOLUNTEERING = 'volunteering';
    public const SPORT = 'sport';
    public const CREATIVITY = 'creativity';
    public const GTO = 'gto';
    public const BUSINESS = 'business';
    public const MIND = 'mind';
    public const REGISTRATION = 'registration';
    public const RESULT_UPLOAD = 'result_upload';
    public const PHOTO = 'photo';
    public const VIDEO = 'video';
    public const DISCREPANCY = 'discrepancy';
    // TODO: Borjomi
    public const BRJ_OFFLINE = 'brj_offline';
    public const BRJ_TELEGRAM = 'brj_telegram';
    public const BRJ_PHOTO_CONTEST = 'brj_photo_contest';
    public const BRJ_MASTER_CLASS = 'brj_master_class';
}
