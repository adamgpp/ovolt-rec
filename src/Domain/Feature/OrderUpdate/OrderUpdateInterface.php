<?php

declare(strict_types=1);

namespace App\Domain\Feature\OrderUpdate;

use App\Domain\Entity\OrderStatus;
use Symfony\Component\Uid\Ulid;

interface OrderUpdateInterface
{
    public function updateOrder(Ulid $orderId, OrderStatus $orderStatus, \DateTimeImmutable $createdAt): void;
}
