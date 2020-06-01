<?php

declare(strict_types=1);

namespace App\Api\Action\Machine;

use App\Repository\MachineRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReturnCash
{
    private MachineRepository $machineRepository;

    public function __construct(MachineRepository $machineRepository)
    {
        $this->machineRepository = $machineRepository;
    }

    /**
     * @Route("/machines/return-cash/{id}", methods={"PATCH"})
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->attributes->get('id');

        $machine = $this->machineRepository->findOneById($id);

        $cash = $machine->getCash();
        $machine->setCash(0.00);
        $this->machineRepository->save($machine);

        return new JsonResponse(['cash' => $cash]);
    }
}
