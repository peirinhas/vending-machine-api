<?php

declare(strict_types=1);

namespace App\Tests\Functional\Action;

use Symfony\Component\HttpFoundation\JsonResponse;

class BuyProductAdminLookCashTest extends ActionTestBase
{

    /*
     * Example: Customer Buy Juice without exact change after that Admin looks Juice stock  and remove cash
     *  insert 0.25, insert 0.25, insert 1, GET Juice
     * -> JUICE
     * Admin remove cash
     * -> 11
     * Admin look stock Juice
     * -> 9
     */
    public function testBuyProductExactMoneyTestForCustomer(): void
    {
        //Customer looks at the products
        self::$customer->request('GET', \sprintf('%s/%s/%s.%s', $this->endpointMachine, self::ID_MACHINE,'products', self::FORMAT));

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);
        $this->assertCount(3, $responseData['hydra:member']);
        $this->assertEquals('Juice',$responseData['hydra:member'][1]['name']);
        $this->assertEquals(10,$responseData['hydra:member'][1]['stock']);
        $this->assertEquals(1,$responseData['hydra:member'][1]['cost']);

        //First insert wallet
        $payload = [
            'wallet' => 0.25
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
        $this->assertEquals(0.25, $responseData['wallet']);

        //second insert wallet
        $payload = [
            'wallet' => 0.25
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
        $this->assertEquals(0.50, $responseData['wallet']);

        //third insert wallet
        $payload = [
            'wallet' => 1
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

        //customer buy juice
        self::$customer->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpointProduct.'/buy', self::IDS_PRODUCT['juice']));

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('GET Juice', $responseData['product']);

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
        $this->assertEquals(11, $responseData['cash']);

        //Admin look Juice stock
        self::$admin->request('GET', \sprintf('%s/%s/%s.%s', $this->endpointMachine, self::ID_MACHINE,'products', self::FORMAT));

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);
        $this->assertCount(3, $responseData['hydra:member']);
        $this->assertEquals('Juice',$responseData['hydra:member'][1]['name']);
        $this->assertEquals(9,$responseData['hydra:member'][1]['stock']);
    }
}