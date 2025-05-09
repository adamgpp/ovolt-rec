<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Application\Bus\CommandBusInterface;
use App\Application\Command\CreateOrderCommand;
use App\Application\Command\UpdateOrderCommand;
use App\Application\Query\Query\OrderQueryInterface;
use App\Domain\Feature\Exception\OrderNotFoundException;
use App\Presentation\Controller\Request\CreateOrderRequest;
use App\Presentation\Controller\Request\GetOrderRequest;
use App\Presentation\Controller\Request\UpdateOrderRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Ulid;

final class OrderController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly OrderQueryInterface $orderQuery,
    ) {
    }

    #[Route(path: '/orders', methods: [Request::METHOD_POST])]
    public function create(CreateOrderRequest $request): Response
    {
        $orderId = new Ulid();

        $this->commandBus->dispatch(new CreateOrderCommand($orderId, new \DateTimeImmutable(), $request->asArray()));

        $order = $this->orderQuery->findById($orderId);

        return null === $order
            ? throw new NotFoundHttpException() : $this->json(['order' => $order], Response::HTTP_CREATED);
    }

    #[Route(path: '/orders/{id}', methods: [Request::METHOD_GET])]
    public function get(GetOrderRequest $request): Response
    {
        $order = $this->orderQuery->findById($request->getId());

        return null === $order
            ? throw new NotFoundHttpException() : $this->json(['order' => $order], Response::HTTP_OK);
    }

    #[Route(path: '/orders/{id}', methods: [Request::METHOD_PATCH])]
    public function update(UpdateOrderRequest $request): Response
    {
        $orderId = $request->getId();

        try {
            $this->commandBus->dispatch(
                new UpdateOrderCommand($orderId, $request->getStatus(), new \DateTimeImmutable())
            );

            return $this->json(null, Response::HTTP_NO_CONTENT);
        } catch (OrderNotFoundException) {
            throw new NotFoundHttpException();
        }
    }
}
