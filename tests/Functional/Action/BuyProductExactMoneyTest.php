<?php

declare(strict_types=1);

namespace App\Tests\Functional\Action;

use Symfony\Component\HttpFoundation\JsonResponse;

class BuyProductExactMoneyTest extends ActionTestBase
{
    /*
     * Example: Buy Soda with exact change
     * Looks products, insert 1, insert 0.25, insert 0.25, GET-SODA
     * -> SODA
     */
    public function testBuyProductExactMoneyTestForCustomer(): void
    {
        //Customer looks at the products
        self::$customer->request('GET', \sprintf('%s/%s/%s.%s', $this->endpointMachine, self::ID_MACHINE, 'products', self::FORMAT));

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);
        $this->assertCount(3, $responseData['hydra:member']);
        $this->assertEquals('Soda', $responseData['hydra:member'][2]['name']);
        $this->assertEquals(10, $responseData['hydra:member'][2]['stock']);
        $this->assertEquals(1.5, $responseData['hydra:member'][2]['cost']);

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

        //second insert wallet
        $payload = [
            'wallet' => 0.25,
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
        $this->assertEquals(1.25, $responseData['wallet']);

        //third insert wallet
        $payload = [
            'wallet' => 0.25,
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
        $this->assertEquals(1.5, $responseData['wallet']);

        //customer buy soda
        self::$customer->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpointProduct.'/buy', self::IDS_PRODUCT['soda']));

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('GET Soda', $responseData['product']);
    }
}
