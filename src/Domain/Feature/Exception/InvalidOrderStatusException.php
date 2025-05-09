<?php

declare(strict_types=1);

namespace App\Domain\Feature\Exception;

final class InvalidOrderStatusException extends \DomainException
{
    public static function create(): self
    {
        return new self('Invalid order status');
    }
}
