<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Product;

use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteProductTest extends ProductTestBase
{

    /*
     *
     *  End point [DELETE] product
     *
     */
    public function testDeleteProductForAdmin(): void
    {
        self::$admin->request('DELETE',
            \sprintf('%s/%s.%s', $this->endpoint, self::IDS_PRODUCT['water'], self::FORMAT));

        $response = self::$admin->getResponse();
        $this->assertEquals(JsonResponse::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteProductForCustomer(): void
    {
        self::$customer->request('DELETE',
            \sprintf('%s/%s.%s', $this->endpoint, self::IDS_PRODUCT['water'], self::FORMAT));

        $response = self::$customer->getResponse();
        $this->assertEquals(JsonResponse::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}