<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Feature\Validation\OrderUpdateValidationInterface;
use App\Domain\ValueObject\BaseString;
use App\Domain\ValueObject\PositiveInteger;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(name: 'orders')]
class Order
{
    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[ORM\Column(enumType: OrderStatus::class)]
    private OrderStatus $status;

    #[ORM\Column]
    private int $total;

    /**
     * @var Collection<int, OrderItem>
     */
    #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'order', cascade: ['persist', 'remove'])]
    private Collection $items;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function __construct(
        Ulid $id,
        \DateTimeImmutable $createdAt,
    ) {
        $this->id = $id;
        $this->status = OrderStatus::New;
        $this->createdAt = $createdAt;
    }

    /**
     * @param Collection<OrderItem> $items
     */
    public function assignItems(Collection $items): void // @phpstan-ignore-line
    {
        $this->items = $items;
        $this->total = $this->countTotal($items)->value;
    }

    public function getId(): Ulid
    {
        return $this->id;
    }

    public function getItems(): array
    {
        return $this->items->toArray();
    }

    /**
     * @param Collection<OrderItem> $items
     */
    private function countTotal(Collection $items): PositiveInteger // @phpstan-ignore-line
    {
        $total = 0;

        foreach ($items as $item) {
            $total += $item->getValue()->value;
        }

        return new PositiveInteger($total);
    }

    public function updateStatus(OrderUpdateValidationInterface $validation, OrderStatus $status): void
    {
        $validation->assertStatusCanBeChanged($this->status, $status);
        $this->status = $status;
    }

    public static function fromArray(Ulid $id, \DateTimeImmutable $createdAt, array $orderArray): self
    {
        $order = new Order($id, $createdAt);

        $items = [];

        foreach ($orderArray['items'] as $itemArray) {
            $items[] = new OrderItem(
                new Ulid(),
                new BaseString($itemArray['productId']),
                new BaseString($itemArray['productName']),
                new PositiveInteger($itemArray['price']),
                new PositiveInteger($itemArray['quantity']),
                $createdAt,
                $order,
            );
        }

        $order->assignItems(new ArrayCollection($items));

        return $order;
    }
}
