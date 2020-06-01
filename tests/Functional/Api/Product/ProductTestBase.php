<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Product;

use App\Tests\Functional\TestBase;

class ProductTestBase extends TestBase
{
    protected string $endpoint;

    public function setUp()
    {
        parent::setUp();

        $this->endpoint = '/api/v1/products';
    }
}
