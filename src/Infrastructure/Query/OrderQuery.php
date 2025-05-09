<?php

declare(strict_types=1);

namespace App\Infrastructure\Query;

use App\Application\Query\Query\OrderQueryInterface;
use App\Application\Query\Query\View\Order;
use Doctrine\DBAL\Connection;
use Symfony\Component\Uid\Ulid;

final readonly class OrderQuery implements OrderQueryInterface
{
    public function __construct(private Connection $connection)
    {
    }

    private function fetchOrderItemRawData(array $rawData): array
    {
        return [
            'product_id' => $rawData['product_id'],
            'product_name' => $rawData['product_name'],
            'price' => $rawData['price'],
            'quantity' => $rawData['quantity'],
        ];
    }

    private function fetchOrderRawData(array $rawData): array
    {
        return [
            'id' => $rawData['order_id'],
            'status' => $rawData['status'],
            'total' => $rawData['total'],
        ];
    }

    public function findById(Ulid $id): ?Order
    {
        $rawData = $this->connection
            ->createQueryBuilder()
            ->select('o.id, o.status, o.total, i.*')
            ->from('orders', 'o')
            ->join('o', 'order_items', 'i', 'o.id = i.order_id')
            ->where('o.id = :id')
            ->setParameter('id', $id->toBinary())
            ->executeQuery()
            ->fetchAllAssociative();

        if (0 === count($rawData)) {
            return null;
        }

        $orderData = $this->fetchOrderRawData($rawData[0]);
        $orderData['items'] = array_map(fn (array $itemRawData) => $this->fetchOrderItemRawData($itemRawData), $rawData);

        return Order::fromArray($orderData);
    }
}
