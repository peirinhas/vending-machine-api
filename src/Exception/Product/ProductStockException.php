<?php

namespace App\Exception\Product;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProductStockException extends BadRequestHttpException
{
    private const MESSAGE = 'There is not stock for this product';

    public static function notAreStock(): self
    {
        throw new self(self::MESSAGE);
    }
}
