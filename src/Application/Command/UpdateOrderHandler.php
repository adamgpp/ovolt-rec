<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Feature\OrderUpdate\OrderUpdateInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UpdateOrderHandler
{
    public function __construct(private OrderUpdateInterface $orderUpdate)
    {
    }

    public function __invoke(UpdateOrderCommand $command): void
    {
        $this->orderUpdate->updateOrder($command->orderId, $command->status, $command->createdAt);
    }
}
