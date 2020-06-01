<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Machine;

use Symfony\Component\HttpFoundation\JsonResponse;

class GetMachineTest extends MachineTestBase
{
    /*
     *
     *  End point [GET] machines
     *
     */
    public function testGetMachinesForAdmin(): void
    {
        self::$admin->request('GET', \sprintf('%s.%s', $this->endpoint, self::FORMAT));

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(1, $responseData['hydra:member']);
    }

    public function testGetMachinesForCustomer(): void
    {
        self::$customer->request('GET', \sprintf('%s.%s', $this->endpoint, self::FORMAT));

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(1, $responseData['hydra:member']);
    }

    /*
     *
     *  End point [GET] machine
     *
     */
    public function testGetMachineWithAdmin(): void
    {
        self::$admin->request('GET', \sprintf('%s/%s.%s', $this->endpoint, self::ID_MACHINE, self::FORMAT));

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(self::ID_MACHINE, $responseData['id']);
        $this->assertEquals(0, $responseData['wallet']);
    }

    public function testGetMachineWithCustomer(): void
    {
        self::$customer->request('GET', \sprintf('%s/%s.%s', $this->endpoint, self::ID_MACHINE, self::FORMAT));

        $response = self::$customer->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(self::ID_MACHINE, $responseData['id']);
        $this->assertEquals(0, $responseData['wallet']);
    }
}
