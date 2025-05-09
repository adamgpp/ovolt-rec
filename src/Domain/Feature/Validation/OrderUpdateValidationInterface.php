<?php

declare(strict_types=1);

namespace App\Domain\Feature\Validation;

use App\Domain\Entity\OrderStatus;

interface OrderUpdateValidationInterface
{
    public function assertStatusCanBeChanged(OrderStatus $currentOrderStatus, OrderStatus $newOrderStatus): void;
}
