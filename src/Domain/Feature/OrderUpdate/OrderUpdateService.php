<?php

declare(strict_types=1);

namespace App\Domain\Feature\OrderUpdate;

use App\Domain\Entity\Order;
use App\Domain\Entity\OrderStatus;
use App\Domain\Feature\Exception\OrderNotFoundException;
use App\Domain\Feature\Validation\OrderUpdateValidationInterface;
use App\Domain\Repository\OrderRepositoryInterface;
use Symfony\Component\Uid\Ulid;

final readonly class OrderUpdateService implements OrderUpdateInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private OrderUpdateValidationInterface $orderUpdateValidation,
    ) {
    }

    public function updateOrder(Ulid $orderId, OrderStatus $orderStatus, \DateTimeImmutable $createdAt): void
    {
        $this->assertOrderExists($orderId);

        /**
         * @var Order $order
         */
        $order = $this->orderRepository->findById($orderId);
        $order->updateStatus($this->orderUpdateValidation, $orderStatus);

        $this->orderRepository->add($order);
        $this->orderRepository->confirm();
    }

    private function assertOrderExists(Ulid $orderId): void
    {
        $order = $this->orderRepository->findById($orderId);

        if (null === $order) {
            throw OrderNotFoundException::create();
        }
    }
}
