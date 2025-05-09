<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\ValueObject\Exception\ValueValidationException;

final readonly class PositiveInteger
{
    public function __construct(public int $value)
    {
        if (0 >= $this->value) {
            throw ValueValidationException::withMessage('PositiveInteger must be greater than 0.');
        }
    }
}
