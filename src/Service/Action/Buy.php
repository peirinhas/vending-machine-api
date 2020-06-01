<?php

namespace App\Service\Action;

use App\Entity\Machine;
use App\Entity\Product;

class Buy
{
    private Product $product;

    private Machine $machine;

    public function __construct(Product $product, Machine $machine)
    {
        $this->product = $product;
        $this->machine = $machine;
    }
}
