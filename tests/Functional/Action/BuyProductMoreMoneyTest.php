<?php

declare(strict_types=1);

namespace App\Tests\Functional\Action;

use Symfony\Component\HttpFoundation\JsonResponse;

class BuyProductMoreMoneyTest extends ActionTestBase
{
    /*
     * Example: Buy Water without exact change
     * 1, GET-WATER
     * -> WATER, 0.25, 0.10
     */
    public function testBuyProductMoreMoneyTestForCustomer(): void
    {
        //Customer looks at the products
        self::$customer->request('GET', \sprintf('%s/%s/%s.%s', $this->endpointMachine, self::ID_MACHINE, 'products', self::FORMAT));

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);
        $this->assertCount(3, $responseData['hydra:member']);
        $this->assertEquals('Water', $responseData['hydra:member'][0]['name']);
        $this->assertEquals(10, $responseData['hydra:member'][0]['stock']);
        $this->assertEquals(0.65, $responseData['hydra:member'][0]['cost']);

        //First insert wallet
        $payload = [
            'wallet' => 1,
        ];

        self::$customer->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpointMachine.'/insert-wallet', self::ID_MACHINE),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(1, $responseData['wallet']);

        //customer buy water
        self::$customer->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpointProduct.'/buy', self::IDS_PRODUCT['water']));

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('GET Water', $responseData['product']);

        //return wallet
        self::$customer->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpointMachine.'/return-wallet', self::ID_MACHINE));

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(0.35, $responseData['wallet']);
    }
}
