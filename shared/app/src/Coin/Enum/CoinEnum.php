<?php

declare(strict_types=1);

namespace App\Coin\Enum;

use App\Currency\Enum\CurrencyEnum;

class CoinEnum
{
    public const BITCOIN_KEY = 'bitcoin';

    public const COINS
        = [
            self::BITCOIN_KEY => [
                CurrencyEnum::USD_KEY,
                CurrencyEnum::EURO_KEY,
                CurrencyEnum::GBP_KEY,
            ],
        ];
}