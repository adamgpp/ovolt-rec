<?php

declare(strict_types=1);

namespace App\Tests\Integration\Presentation\Controller;

use App\Domain\Entity\Order;
use App\Domain\Repository\OrderRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

final class CreateOrderWebTest extends WebTestCase
{
    private KernelBrowser $client;

    private OrderRepositoryInterface $orderRepository;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $this->orderRepository = self::getContainer()->get(OrderRepositoryInterface::class);
    }

    public function testShouldSuccessfullyCreateOrderWithItem(): void
    {
        $requestContent = <<<JSON
            {
              "items": [
                {
                  "productId": "1",
                  "productName": "Product A",
                  "price": 100,
                  "quantity": 2
                },
                {
                  "productId": "2",
                  "productName": "Product B",
                  "price": 50,
                  "quantity": 1
                }
              ]
            }
        JSON;

        $this->client->request(method: Request::METHOD_POST, uri: '/orders', content: $requestContent);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $createdOrderId = strval(json_decode($this->client->getResponse()->getContent() ?: '', true)['order']['id'] ?? '');

        $order = $this->orderRepository->findById(Ulid::fromString($createdOrderId));

        self::assertInstanceOf(Order::class, $order);
        self::assertSame($createdOrderId, $order->getId()->toBase32());
        self::assertCount(2, $order->getItems());

        $expectedResponse = <<<JSON
            {
                "order": {
                    "id": "$createdOrderId",
                    "status": "new",
                    "items": [
                        {
                            "productId": "1",
                            "productName": "Product A",
                            "price": 100,
                            "quantity": 2
                        },
                        {
                            "productId": "2",
                            "productName": "Product B",
                            "price": 50,
                            "quantity": 1
                        }
                    ],
                    "totalValue": 250
                }
            }
        JSON;
        self::assertJsonStringEqualsJsonString($expectedResponse, (string) $this->client->getResponse()->getContent());
    }
}
