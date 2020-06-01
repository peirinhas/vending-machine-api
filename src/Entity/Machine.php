<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

class Machine
{
    protected ?string $id;

    protected ?float $cash;

    protected ?float $wallet;

    /** @var Collection|Product[] */
    protected ?Collection $products = null;

    protected \DateTime $createdAt;

    protected \DateTime $updatedAt;

    /**
     * @throws \Exception
     */
    public function __construct(float $cash = null, float $wallet = null, string $id = null)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->cash = $cash ?? 0.00;
        $this->wallet = $wallet ?? 0.00;
        $this->products = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->markAsUpdated();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCash(): float
    {
        return $this->cash;
    }

    public function setCash(float $cash): void
    {
        $this->cash = $cash;
    }

    public function getWallet(): float
    {
        return $this->wallet;
    }

    public function setWallet(float $wallet): void
    {
        $this->wallet = $wallet;
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

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }
}
