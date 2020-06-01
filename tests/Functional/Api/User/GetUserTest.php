<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class GetUserTest extends UserTestBase
{
    /*
     *
     *  End point [GET] users
     *
     */
    public function testGetUsersForAdmin(): void
    {
        self::$admin->request('GET', \sprintf('%s.%s', $this->endpoint, self::FORMAT));

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertCount(1, $responseData['hydra:member']);
    }

    public function testGetUsersForCustomer(): void
    {
        self::$customer->request('GET', \sprintf('%s.%s', $this->endpoint, self::FORMAT));

        $response = self::$customer->getResponse();
        $this->assertEquals(JsonResponse::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    /*
     *
     *  End point [GET] user
     *
     */
    public function testGetUserWithAdmin(): void
    {
        self::$admin->request('GET', \sprintf('%s/%s.%s', $this->endpoint, self::IDS_USER, self::FORMAT));

        $response = self::$admin->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(self::IDS_USER, $responseData['id']);
    }

    public function testGetAdminWithCustomer(): void
    {
        self::$customer->request('GET', \sprintf('%s/%s.%s', $this->endpoint, self::IDS_USER, self::FORMAT));

        $response = self::$customer->getResponse();

        $this->assertEquals(JsonResponse::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }
}
