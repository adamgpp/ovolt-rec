<?php

declare(strict_types=1);

namespace App\Domain\Feature\Validation;

use App\Domain\Entity\OrderStatus;
use App\Domain\Feature\Exception\InvalidOrderStatusException;

final class OrderUpdateValidator implements OrderUpdateValidationInterface
{
    private const array STATUSES_MAPPING = [
        OrderStatus::New->value => [
            OrderStatus::Paid->value,
            OrderStatus::Cancelled->value,
        ],
        OrderStatus::Paid->value => [
            OrderStatus::Shipped->value,
            OrderStatus::Cancelled->value,
        ],
        OrderStatus::Shipped->value => [
            OrderStatus::Cancelled->value,
        ],
    ];

    public function assertStatusCanBeChanged(OrderStatus $currentOrderStatus, OrderStatus $newOrderStatus): void
    {
        if (false === in_array($newOrderStatus->value, self::STATUSES_MAPPING[$currentOrderStatus->value], true)) {
            throw InvalidOrderStatusException::create();
        }
    }
}
