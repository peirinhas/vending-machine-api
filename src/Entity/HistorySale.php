<?php

declare(strict_types=1);

namespace App\Entity;

use Ramsey\Uuid\Uuid;

class HistorySale
{
    protected ?string $id;

    private Product $product;

    protected float $cost;

    private ?\DateTime $createdAt = null;

    /**
     * @throws \Exception
     */
    public function __construct(Product $product, float $cost, string $id = null)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
        $this->product = $product;
        $this->cost = $cost;
        $this->createdAt = new \DateTime();
        $this->markAsUpdated();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getCost(): float
    {
        return $this->cost;
    }

    public function setCost(float $cost): void
    {
        $this->cost = $cost;
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

    public function isOwnedByProduct(Machine $product): bool
    {
        return $this->getProduct()->getId() === $product->getId();
    }
}
