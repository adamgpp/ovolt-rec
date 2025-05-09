<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Entity\Order;
use App\Domain\Feature\OrderCreation\OrderCreationInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateOrderHandler
{
    public function __construct(private OrderCreationInterface $orderCreation)
    {
    }

    public function __invoke(CreateOrderCommand $command): void
    {
        $order = Order::fromArray($command->orderId, $command->createdAt, $command->orderArray);
        $this->orderCreation->createOrder($order);
    }
}
