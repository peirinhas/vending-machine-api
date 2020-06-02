<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\HistorySale;

class HistorySaleRepository extends BaseRepository
{
    protected static function entityClass(): string
    {
        return HistorySale::class;
    }

    public function save(HistorySale $sale): void
    {
        $this->saveEntity($sale);
    }
}
