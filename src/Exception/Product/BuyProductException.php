<?php

declare(strict_types=1);

namespace App\Exception\Product;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BuyProductException extends BadRequestHttpException
{
    private const MESSAGE = 'Wallet does not have enough money to buy %s';

    public static function notMinimumCost(string $productName): self
    {
        throw new self(\sprintf(self::MESSAGE, $productName));
    }
}
