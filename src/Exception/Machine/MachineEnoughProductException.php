<?php

declare(strict_types=1);

namespace App\Exception\Machine;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MachineEnoughProductException extends BadRequestHttpException
{
    private const MESSAGE = 'Machine need 3 products. Now Machine has %d product/s';

    public static function notMinimum(int $num): self
    {
        throw new self(\sprintf(self::MESSAGE, $num));
    }
}
