<?php

declare(strict_types=1);

namespace App\Application\Command;

use Symfony\Component\Uid\Ulid;

/**
 * @see CreateOrderHandler
 */
final readonly class CreateOrderCommand implements CommandInterface
{
    public function __construct(public Ulid $orderId, public \DateTimeImmutable $createdAt, public array $orderArray)
    {
    }
}
