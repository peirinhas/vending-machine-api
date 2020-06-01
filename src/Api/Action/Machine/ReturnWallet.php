<?php

declare(strict_types=1);

namespace App\Api\Action\Machine;

use App\Repository\MachineRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReturnWallet
{
    private MachineRepository $machineRepository;

    public function __construct(MachineRepository $machineRepository)
    {
        $this->machineRepository = $machineRepository;
    }

    /**
     * @Route("/machines/return-wallet/{id}", methods={"PATCH"})
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->attributes->get('id');

        $machine = $this->machineRepository->findOneById($id);

        $wallet = $machine->getWallet();
        $machine->setWallet(0.00);
        $this->machineRepository->save($machine);

        return new JsonResponse(['wallet' => $wallet]);
    }
}
