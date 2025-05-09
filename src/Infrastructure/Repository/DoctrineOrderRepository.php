<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Order;
use App\Domain\Repository\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Ulid;

final readonly class DoctrineOrderRepository implements OrderRepositoryInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findById(Ulid $id): ?Order
    {
        return $this->entityManager->find(Order::class, $id);
    }

    public function add(Order $order): void
    {
        $this->entityManager->persist($order);
    }

    public function confirm(): void
    {
        $this->entityManager->flush();
    }
}
