<?php

declare(strict_types=1);

namespace App\Domain\Entity;

enum OrderStatus: string
{
    case New = 'new';
    case Paid = 'paid';
    case Shipped = 'shipped';
    case Cancelled = 'cancelled';

    public static function values(): array
    {
        return array_map(static fn (self $self) => $self->value, self::cases());
    }
}
