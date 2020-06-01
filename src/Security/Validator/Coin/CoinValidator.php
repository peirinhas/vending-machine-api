<?php

declare(strict_types=1);

namespace App\Security\Validator\Coin;

interface CoinValidator
{
    public function validate(float $request): bool;
}
