<?php

declare(strict_types=1);

namespace App\Exception\Coin;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UnsupportedCoinException extends BadRequestHttpException
{
    private const MESSAGE = "It's unsupported coin. Please insert 0.05, 0.10, 0.25 or 1  ";

    public static function fromCoin(): self
    {
        throw new self(self::MESSAGE);
    }
}
