<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Entity\OrderStatus;
use Symfony\Component\Uid\Ulid;

/**
 * @see UpdateOrderHandler
 */
final readonly class UpdateOrderCommand implements CommandInterface
{
    public function __construct(public Ulid $orderId, public OrderStatus $status, public \DateTimeImmutable $createdAt)
    {
    }
}
