<?php

declare(strict_types=1);

namespace App\Domain\Feature\Exception;

final class OrderNotFoundException extends \DomainException
{
    public static function create(): self
    {
        return new self('Order not found.');
    }
}
