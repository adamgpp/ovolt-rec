<?php

declare(strict_types=1);

namespace App\Application\Query\Query\View;

use Symfony\Component\Uid\Ulid;

final readonly class Order
{
    private function __construct(
        public string $id,
        public string $status,
        public array $items,
        public int $totalValue,
    ) {
    }

    public static function fromArray(array $array): self
    {
        return new self(
            Ulid::fromBinary($array['id'])->toBase32(),
            $array['status'],
            array_map(static fn (array $item) => OrderItem::fromArray($item), $array['items']),
            $array['total'],
        );
    }
}
