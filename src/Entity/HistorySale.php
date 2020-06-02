<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MachineRepository;
use Ramsey\Uuid\Uuid;

class Product
{
    protected ?string $id;

    private ?Machine $machine;

    protected string $name;

    protected float $cost;

    protected int $stock;

    private ?\DateTime $createdAt = null;

    private ?\DateTime $updatedAt = null;

    /**
     * @throws \Exception
     */
    public function __construct(Machine $machine, string $name, float $cost, int $stock, string $id = null)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->machine = $machine;
        $this->name = $name;
        $this->cost = $cost;
        $this->stock = $stock;
        $this->createdAt = new \DateTime();
        $this->markAsUpdated();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMachine(): Machine
    {
        return $this->machine;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCost(): float
    {
        return $this->cost;
    }

    public function setCost(float $cost): void
    {
        $this->cost = $cost;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function markAsUpdated(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function isOwnedByMachine(Machine $machine): bool
    {
        return $this->getMachine()->getId() === $machine->getId();
    }

    public function getWallet(): float
    {
        return $this->getMachine()->getWallet();
    }

    public function setWallet(float $wallet): void
    {
        $this->getMachine()->setWallet($wallet);
    }

    public function getCash(): float
    {
        return $this->getMachine()->getCash();
    }

    public function setCash(float $cash): void
    {
        $this->getMachine()->setCash($cash);
    }
}
