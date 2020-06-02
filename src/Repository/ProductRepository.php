<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\HistorySale;
use App\Entity\Machine;
use App\Entity\Product;
use App\Exception\Product\ProductExistException;

class ProductRepository extends BaseRepository
{
    protected static function entityClass(): string
    {
        return Product::class;
    }

    public function findOneById(string $id): ?Product
    {
        /** @var Product $product */
        $product = $this->objectRepository->findOneBy(['id' => $id]);

        if (null == $product) {
            ProductExistException::notExist();
        }

        return $product;
    }

    public function save(Product $product): void
    {
        $this->saveEntity($product);
    }

    public function updateMachine(Machine $machine): void
    {
        $this->saveEntity($machine);
    }

    /**
     * @throws \Exception
     */
    public function addSale(Product $product): void
    {
        $sale = new HistorySale($product, $product->getCost());
        $this->saveEntity($sale);
    }
}
