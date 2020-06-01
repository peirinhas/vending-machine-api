<?php

declare(strict_types=1);

namespace App\Api\Action\Machine;

use App\Api\Action\RequestTransformer;
use App\Repository\MachineRepository;
use App\Security\Validator\Coin\AreValidCoin;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InsertWallet
{
    private MachineRepository $machineRepository;

    public function __construct(MachineRepository $machineRepository)
    {
        $this->machineRepository = $machineRepository;
    }

    /**
     * @Route("/machines/insert-wallet/{id}", methods={"PATCH"})
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->attributes->get('id');
        $coin = RequestTransformer::getRequiredField($request, 'wallet');

        $machine = $this->machineRepository->findOneById($id);

        $coinValidator = new AreValidCoin();

        if ($coinValidator->validate($coin)) {
            $wallet = $machine->getWallet() + $coin;
            $machine->setWallet($wallet);
            $this->machineRepository->save($machine);

            return new JsonResponse(['wallet' => $wallet]);
        }
    }
}
