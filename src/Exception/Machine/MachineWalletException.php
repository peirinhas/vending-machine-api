<?php

declare(strict_types=1);

namespace App\Exception\Machine;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MachineWalletException extends BadRequestHttpException
{
    private const MESSAGE_NOT_AUTHORIZED = 'You are not authorized for this action';
    private const MESSAGE_NOT_WALLET = 'There are not coins at wallet';

    public static function notAuthorized(): self
    {
        throw new self(self::MESSAGE_NOT_AUTHORIZED);
    }

    public static function notAreWallet(): self
    {
        throw new self(self::MESSAGE_NOT_WALLET);
    }
}
