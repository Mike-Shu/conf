<?php

namespace App\Enums;

use Bavix\Wallet\Models\Transaction;
use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

/**
 * @package App\Enums
 */
class WalletTransactionType extends Enum implements LocalizedEnum
{
    public const DEPOSIT = Transaction::TYPE_DEPOSIT;
    public const WITHDRAW = Transaction::TYPE_WITHDRAW;
}
