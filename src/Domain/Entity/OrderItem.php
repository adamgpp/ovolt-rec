<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\BaseString;
use App\Domain\ValueObject\PositiveInteger;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(
    name: 'order_items'
)]
#[ORM\UniqueConstraint(name: 'order_id_product_id_unique', columns: ['order_id', 'product_id'])]
class OrderItem
{
    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[ORM\Column]
    private string $productId;

    #[ORM\Column]
    private string $productName;

    #[ORM\Column]
    private int $price;

    #[ORM\Column]
    private int $quantity;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
    #[ORM\JoinColumn(nullable: false)]
    private Order $order;

    public function __construct(
        Ulid $id,
        BaseString $productId,
        BaseString $productName,
        PositiveInteger $price,
        PositiveInteger $quantity,
        \DateTimeImmutable $createdAt,
        Order $order,
    ) {
        $this->id = $id;
        $this->productId = $productId->value;
        $this->productName = $productName->value;
        $this->price = $price->value;
        $this->quantity = $quantity->value;
        $this->createdAt = $createdAt;
        $this->order = $order;
    }

    public function getValue(): PositiveInteger
    {
        return new PositiveInteger($this->price * $this->quantity);
    }
}
