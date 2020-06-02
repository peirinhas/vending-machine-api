<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Product;

use Symfony\Component\HttpFoundation\JsonResponse;

class PostProductTest extends ProductTestBase
{
    /*
     *
     *  End point [POST] product
     *
     */
    public function testPostProductForAdmin(): void
    {
        $payload = [
            'machine' => '/api/v1/machines/0f6acbf3-a958-4d2e-9352-bd17f469b002',
            'name' => 'snack',
            'cost' => 1.15,
            'stock' => 10,
        ];

        self::$admin->request(
            'POST',
            \sprintf('%s.%s', $this->endpoint, self::FORMAT),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('/api/v1/machines/0f6acbf3-a958-4d2e-9352-bd17f469b002', $responseData['machine']);
        $this->assertEquals('snack', $responseData['name']);
        $this->assertEquals(1.15, $responseData['cost']);
        $this->assertEquals(10, $responseData['stock']);

        self::$admin->request('GET', \sprintf('%s.%s', $this->endpoint, self::FORMAT));

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);
        $this->assertCount(4, $responseData['hydra:member']);
    }

    public function testPostProductForCustomer(): void
    {
        $payload = [
            'machine' => '/api/v1/machines/0f6acbf3-a958-4d2e-9352-bd17f469b002',
            'name' => 'snack',
            'cost' => 1.15,
            'stock' => 10,
        ];

        self::$customer->request(
            'POST',
            \sprintf('%s.%s', $this->endpoint, self::FORMAT),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$customer->getResponse();
        $this->assertEquals(JsonResponse::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}
