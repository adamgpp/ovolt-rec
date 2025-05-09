<?php

declare(strict_types=1);

namespace App\Application\Query\Query\View;

final readonly class OrderItem
{
    public function __construct(
        public string $productId,
        public string $productName,
        public int $price,
        public int $quantity,
    ) {
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['product_id'],
            $array['product_name'],
            $array['price'],
            $array['quantity'],
        );
    }
}
