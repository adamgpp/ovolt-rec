<?php

declare(strict_types=1);

namespace App\Domain\Feature\OrderCreation;

use App\Domain\Entity\Order;
use App\Domain\Repository\OrderRepositoryInterface;

final readonly class OrderCreationService implements OrderCreationInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {
    }

    public function createOrder(Order $order): void
    {
        $this->orderRepository->add($order);
        $this->orderRepository->confirm();
    }
}
