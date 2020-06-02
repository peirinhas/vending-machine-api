<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Product;

use Symfony\Component\HttpFoundation\JsonResponse;

class PatchProductTest extends ProductTestBase
{
    /*
     *
     *  End point [PATCH] product
     *
     */
    public function testPatchProductForCustomer(): void
    {
        $payload = [
            'cost' => 0.50,
            'stock' => 8,
        ];

        self::$customer->request(
            'PATCH',
            \sprintf('%s/%s.%s', $this->endpoint, self::IDS_PRODUCT['water'], self::FORMAT),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$customer->getResponse();
        $this->assertEquals(JsonResponse::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}
