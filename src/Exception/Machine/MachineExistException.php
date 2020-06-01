<?php

declare(strict_types=1);

namespace App\Exception\Machine;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MachineExistException extends BadRequestHttpException
{
    private const MESSAGE = 'The machine does not exist';

    public static function notExist(): self
    {
        throw new self(self::MESSAGE);
    }
}
