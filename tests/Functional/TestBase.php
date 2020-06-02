<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\DataFixtures\AppFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TestBase extends WebTestCase
{
    use FixturesTrait;

    protected const FORMAT = 'jsonld';

    protected const  IDS_USER = '0f6acbf3-a958-4d2e-9352-bd17f469b001';

    protected const  ID_MACHINE = '0f6acbf3-a958-4d2e-9352-bd17f469b002';

    protected const  IDS_PRODUCT = [
        'water' => '0f6acbf3-a958-4d2e-9352-bd17f469b003',
        'juice' => '0f6acbf3-a958-4d2e-9352-bd17f469b004',
        'soda' => '0f6acbf3-a958-4d2e-9352-bd17f469b005',
    ];

    protected static ?KernelBrowser $client = null;
    protected static ?KernelBrowser $admin = null;
    protected static ?KernelBrowser $customer = null;

    /**
     * @throws ToolsException
     */
    public function setUp()
    {
        if (null === self::$client) {
            self::$client = static::createClient();
        }

        $this->resetDatabase();

        if (null === self::$admin) {
            self::$admin = clone self::$client;
            $this->createAuthenticatedUser(self::$admin, 'service@vending.com', 'password');
        }

        if (null === self::$customer) {
            self::$customer = clone self::$client;
        }
    }

    private function createAuthenticatedUser(KernelBrowser &$client, string $username, string $password): void
    {
        $client->request(
            'POST',
            '/api/v1/login_check',
            [
                '_email' => $username,
                '_password' => $password,
            ]
        );

        $data = \json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameters(
            [
                'HTTP_Authorization' => \sprintf('Bearer %s', $data['token']),
                'CONTENT_TYPE' => 'application/json',
            ]
        );
    }

    protected function getResponseData(Response $response): array
    {
        return \json_decode($response->getContent(), true);
    }

    /**
     * @throws ToolsException
     */
    private function resetDatabase(): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        if (!isset($metadata)) {
            $metadata = $em->getMetadataFactory()->getAllMetadata();
        }

        $schemaTool = new SchemaTool($em);
        $schemaTool->dropDatabase();

        if (!empty($metadata)) {
            $schemaTool->createSchema($metadata);
        }

        $this->postFixtureSetup();
        $this->loadFixtures([AppFixtures::class]);
    }
}
