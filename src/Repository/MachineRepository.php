<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Machine;
use App\Exception\Machine\MachineExistException;

class MachineRepository extends BaseRepository
{
    protected static function entityClass(): string
    {
        return Machine::class;
    }

    public function findOneById(string $id): ?Machine
    {
        /** @var Machine $machine */
        $machine = $this->objectRepository->findOneBy(['id' => $id]);

        if (null === $machine) {
            MachineExistException::notExist();
        }

        return $machine;
    }

    public function save(Machine $machine): void
    {
        $this->saveEntity($machine);
    }
}
