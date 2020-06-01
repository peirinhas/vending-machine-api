<?php

declare(strict_types=1);

namespace App\Security;

abstract class Coin
{
    public static function getSupportedCoins(): array
    {
        return [
            '0.05',
            '0.10',
            '0.25',
            '1.00',
        ];
    }
}
