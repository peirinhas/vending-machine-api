<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Machine;

use Symfony\Component\HttpFoundation\JsonResponse;

class PatchMachineTest extends MachineTestBase
{
    /*
     *
     *  End point [PATCH] insert-cash
     *
     */
    public function testPatchInsertCashForAdmin(): void
    {
        $payload = [
            'cash' => 0.25
        ];

        self::$admin->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpoint.'/insert-cash', self::ID_MACHINE),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(10.25, $responseData['cash']);
    }

    public function testPatchInsertCashForCustomer(): void
    {
        $payload = [
            'cash' => 0.25,
        ];

        self::$customer->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpoint.'/insert-cash', self::ID_MACHINE),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$customer->getResponse();
        $this->assertEquals(JsonResponse::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testPatchInsertCashUnsupportedCoinForAdmin(): void
    {
        $payload = [
            'cash' => 0.8
        ];

        self::$admin->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpoint.'/insert-cash', self::ID_MACHINE),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertContains("It's unsupported coin. Please insert 0.05, 0.10, 0.25 or 1", $responseData['message']);
    }

    /*
     *
     *  End point [PATCH] insert-wallet
     *
     */
    public function testPatchInsertWalletForAdmin(): void
    {
        $payload = [
            'wallet' => 0.25
        ];

        self::$admin->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpoint.'/insert-wallet', self::ID_MACHINE),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(0.25, $responseData['wallet']);
    }

    public function testPatchInsertWalletForCustomer(): void
    {
        $payload = [
            'wallet' => 0.25,
        ];

        self::$customer->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpoint.'/insert-wallet', self::ID_MACHINE),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(0.25, $responseData['wallet']);
    }

    public function testPatchInsertWalletUnsupportedCoinForAdmin(): void
    {
        $payload = [
            'wallet' => 0.8
        ];

        self::$admin->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpoint.'/insert-wallet', self::ID_MACHINE),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertContains("It's unsupported coin. Please insert 0.05, 0.10, 0.25 or 1", $responseData['message']);
    }

    public function testPatchInsertWalletUnsupportedCoinForCustomer(): void
    {
        $payload = [
            'wallet' => 0.8
        ];

        self::$customer->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpoint.'/insert-wallet', self::ID_MACHINE),
            [],
            [],
            [],
            \json_encode($payload)
        );

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertContains("It's unsupported coin. Please insert 0.05, 0.10, 0.25 or 1", $responseData['message']);
    }

    /*
     *
     *  End point [PATCH] return-cash
     *
     */
    public function testPatchReturnCashForAdmin(): void
    {
        self::$admin->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpoint.'/return-cash', self::ID_MACHINE));

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(10, $responseData['cash']);
    }

    public function testPatchReturnCashForCustomer(): void
    {
        self::$customer->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpoint.'/return-cash', self::ID_MACHINE));

        $response = self::$customer->getResponse();
        $this->assertEquals(JsonResponse::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /*
     *
     *  End point [PATCH] return-wallet
     *
     */
    public function testPatchReturnWalletForAdmin(): void
    {
        self::$admin->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpoint.'/return-wallet', self::ID_MACHINE));

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(0, $responseData['wallet']);
    }

    public function testPatchReturnWalletForCustomer(): void
    {
        self::$customer->request(
            'PATCH',
            \sprintf('%s/%s', $this->endpoint.'/return-wallet', self::ID_MACHINE));

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(0, $responseData['wallet']);
    }
}
