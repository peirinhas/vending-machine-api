<?php

namespace App\DataFixtures;

use App\Entity\Machine;
use App\Entity\Product;
use App\Entity\User;
use App\Security\Role;
use App\Service\Password\EncoderService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private EncoderService $encoderService;

    public function __construct(EncoderService $encoderService)
    {
        $this->encoderService = $encoderService;
    }

    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $users = $this->getUsers();

        foreach ($users as $userData) {
            $user = new User($userData['name'], $userData['email'], $userData['id']);
            $user->setPassword($this->encoderService->generateEncodedPasswordForUser($user, $userData['password']));
            $user->setRoles($userData['roles']);

            $manager->persist($user);
        }

        $machines = $this->getMachines();

        foreach ($machines as $machineData) {
            $machine = new Machine($machineData['cash'], $machineData['wallet'], $machineData['id']);
            $manager->persist($machine);

            foreach ($machineData['products'] as $productData) {
                $product = new Product($machine, $productData['name'], $productData['cost'], $productData['stock'],$productData['id']);
                $manager->persist($product);
            }
        }

        $manager->flush();
    }

    private function getUsers(): array
    {
        return [
            [
                'id' => '0f6acbf3-a958-4d2e-9352-bd17f469b001',
                'name' => 'Service',
                'email' => 'service@vending.com',
                'password' => 'password',
                'roles' => [
                    Role::ROLE_ADMIN,
                ],
            ],
        ];
    }

    private function getMachines(): array
    {
        return [
            [
                'id' => '0f6acbf3-a958-4d2e-9352-bd17f469b002',
                'cash' => '10.00',
                'wallet' => '0.00',
                'products' => [
                    [
                        'id' => '0f6acbf3-a958-4d2e-9352-bd17f469b003',
                        'name' => 'Water',
                        'cost' => '0.65',
                        'stock' => '10',
                    ],
                    [
                        'id' => '0f6acbf3-a958-4d2e-9352-bd17f469b004',
                        'name' => 'Juice',
                        'cost' => '1.00',
                        'stock' => '10',
                    ],
                    [
                        'id' => '0f6acbf3-a958-4d2e-9352-bd17f469b005',
                        'name' => 'Soda',
                        'cost' => '1.50',
                        'stock' => '10',
                    ],
                ],
            ],
        ];
    }
}
