<?php

namespace App\Service\Tools;

class Standardization
{
    public function stringToFloat($value, int $decimal = 0): float
    {
        return number_format(floatval($value), $decimal);
    }
}
