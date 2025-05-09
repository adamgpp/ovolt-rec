<?php

declare(strict_types=1);

namespace App\Domain\Feature\OrderCreation;

use App\Domain\Entity\Order;

interface OrderCreationInterface
{
    public function createOrder(Order $order): void;
}
