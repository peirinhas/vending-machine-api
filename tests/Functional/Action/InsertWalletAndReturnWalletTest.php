<?php

declare(strict_types=1);

namespace App\Tests\Functional\Action;

use Symfony\Component\HttpFoundation\JsonResponse;

class InsertWalletAndReturnWalletTest extends ActionTestBase
{

    /*
     * Example: Start adding money, but user ask for return coin
     *   0.10, 0.10, RETURN-COIN
     *   -> 0.10, 0.10
     */
    public function testPatchProductForCustomer(): void
    {
        $payload = [
            'wallet' => 0.10
        ];

        //First insert wallet
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
        $this->assertEquals(0.10, $responseData['wallet']);

        //second insert wallet
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
        $this->assertEquals(0.20, $responseData['wallet']);

        //return wallet
        self::$customer->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpointMachine.'/return-wallet', self::ID_MACHINE));

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(0.2, $responseData['wallet']);
    }
}