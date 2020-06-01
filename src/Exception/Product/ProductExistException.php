<?php

declare(strict_types=1);

namespace App\Exception\Product;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ProductExistException extends BadRequestHttpException
{
    private const MESSAGE = 'The product does not exist';

    public static function notExist(): self
    {
        throw new self(self::MESSAGE);
    }
}
