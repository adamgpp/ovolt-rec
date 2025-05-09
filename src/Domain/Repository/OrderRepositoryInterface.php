<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\Order;
use Symfony\Component\Uid\Ulid;

interface OrderRepositoryInterface
{
    public function findById(Ulid $id): ?Order;

    public function add(Order $order): void;

    public function confirm(): void;
}
