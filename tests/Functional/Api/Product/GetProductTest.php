<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Product;

use Symfony\Component\HttpFoundation\JsonResponse;

class GetProductTest extends ProductTestBase
{
    /*
     *
     *  End point [GET] products
     *
     */
    public function testGetProductsForAdmin(): void
    {
        self::$admin->request('GET', \sprintf('%s.%s', $this->endpoint, self::FORMAT));

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(3, $responseData['hydra:member']);
    }

    public function testGetProductsForCustomer(): void
    {
        self::$customer->request('GET', \sprintf('%s.%s', $this->endpoint, self::FORMAT));

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(3, $responseData['hydra:member']);
    }

    /*
     *
     *  End point [GET] product
     *
     */
    public function testGetProductWithAdmin(): void
    {

        self::$admin->request('GET', \sprintf('%s/%s.%s', $this->endpoint, self::IDS_PRODUCT['water'], self::FORMAT));

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(self::IDS_PRODUCT['water'], $responseData['id']);
        $this->assertEquals(0.65, $responseData['cost']);
        $this->assertEquals(10, $responseData['stock']);

    }

    public function testGetProductWithCustomer(): void
    {
        self::$customer->request('GET', \sprintf('%s/%s.%s', $this->endpoint, self::IDS_PRODUCT['water'], self::FORMAT));

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(self::IDS_PRODUCT['water'], $responseData['id']);
        $this->assertEquals(0.65, $responseData['cost']);
        $this->assertEquals(10, $responseData['stock']);
    }
}