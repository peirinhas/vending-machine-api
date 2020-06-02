<?php

declare(strict_types=1);

namespace App\Tests\Functional\Action;

use App\Tests\Functional\TestBase;

class ActionTestBase extends TestBase
{
    protected string $endpointMachine;
    protected string $endpointProduct;
    protected string $endpointHistorySale;

    public function setUp()
    {
        parent::setUp();

        $this->endpointMachine = '/api/v1/machines';
        $this->endpointProduct = '/api/v1/products';
        $this->endpointHistorySale = '/api/v1/history_sales';
    }
}
