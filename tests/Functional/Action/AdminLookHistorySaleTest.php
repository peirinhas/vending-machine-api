<?php

declare(strict_types=1);

namespace App\Tests\Functional\Action;

use Symfony\Component\HttpFoundation\JsonResponse;

class AdminLookHistorySaleTest extends ActionTestBase
{
    /*
     * Example: Customer Buy Juice  and  Soda without exact change after that Admin looks cash, stock and history sale
     *  insert 1, insert 1, insert 1, GET Juice
     * -> JUICE
     *  GET Soda
     * -> SODA
     * Admin look cash
     * -> 12.5
     * Admin look stock
     * -> JUICE 9 , Soda 9, Water 10
     * Admin look history sale
     * -> 1 row Juice , 1 row Soda
     */
    public function testAdminLookHistorySale(): void
    {
        //Customer looks at the products
        self::$customer->request('GET', \sprintf('%s/%s/%s.%s', $this->endpointMachine, self::ID_MACHINE, 'products', self::FORMAT));

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);
        $this->assertCount(3, $responseData['hydra:member']);
        $this->assertEquals('Juice', $responseData['hydra:member'][1]['name']);
        $this->assertEquals(10, $responseData['hydra:member'][1]['stock']);
        $this->assertEquals(1, $responseData['hydra:member'][1]['cost']);
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
        $this->assertEquals(2, $responseData['wallet']);

        //third insert wallet
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
        $this->assertEquals(3, $responseData['wallet']);

        //customer buy juice
        self::$customer->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpointProduct.'/buy', self::IDS_PRODUCT['juice']));

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('GET Juice', $responseData['product']);

        //customer buy soda
        self::$customer->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpointProduct.'/buy', self::IDS_PRODUCT['soda']));

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('GET Soda', $responseData['product']);

        //return wallet
        self::$customer->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpointMachine.'/return-wallet', self::ID_MACHINE));

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(0.5, $responseData['wallet']);

        //Admin remove cash
        self::$admin->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpointMachine.'/return-cash', self::ID_MACHINE));

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(12.5, $responseData['cash']);

        //Admin look Juice stock
        self::$admin->request('GET', \sprintf('%s/%s/%s.%s', $this->endpointMachine, self::ID_MACHINE, 'products', self::FORMAT));

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);
        $this->assertCount(3, $responseData['hydra:member']);
        $this->assertEquals('Water', $responseData['hydra:member'][0]['name']);
        $this->assertEquals(10, $responseData['hydra:member'][0]['stock']);
        $this->assertEquals('Juice', $responseData['hydra:member'][1]['name']);
        $this->assertEquals(9, $responseData['hydra:member'][1]['stock']);
        $this->assertEquals('Soda', $responseData['hydra:member'][2]['name']);
        $this->assertEquals(9, $responseData['hydra:member'][2]['stock']);

        //Admin look history sale
        self::$admin->request('GET', \sprintf('%s.%s', $this->endpointHistorySale, self::FORMAT));

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);
        $this->assertCount(2, $responseData['hydra:member']);
    }
}
