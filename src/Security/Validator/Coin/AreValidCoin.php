<?php

declare(strict_types=1);

namespace App\Security\Validator\Coin;

use App\Exception\Coin\UnsupportedCoinException;
use App\Security\Coin;

class AreValidCoin implements CoinValidator
{
    public function validate(float $coin): bool
    {
        if (!\in_array(number_format(floatval($coin), 2), Coin::getSupportedCoins(), true)) {
            throw UnsupportedCoinException::fromCoin();
        } else {
            return true;
        }
    }
}
