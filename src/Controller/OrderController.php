<?php

namespace App\Controller;

use App\Dto\OrderInputDto;
use App\Service\OrderService;
use Symfony\Component\HttpFoundation\Request;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{

    public function __construct(
        private readonly OrderService $orderService
    ) {}

    #[Route('/order/new', name: 'app_new_order', methods: ['POST'], format: 'json')]
    public function newOrder(
        #[MapRequestPayload(
            acceptFormat: 'json',
            validationGroups: ['strict', 'read'],
            validationFailedStatusCode: Response::HTTP_NOT_FOUND
        )] OrderInputDto $orderInputDto
    ): JsonResponse
    {
        $result = $this->orderService->createOrder($orderInputDto);

        return $this->json($result);
    }

    #[Route('/order/{id}', name: 'app_get_order', methods: ['GET'])]
    public function getOrder(Request $request): JsonResponse
    {
        $id = $request->get('id');
        if (!is_numeric($id) || $id < 1) {
            return $this->json([],500);
        }

        if (!($order = $this->orderService->getOrderById($id))) {
            return $this->json([],500);
        }

        return $this->json($order);
    }
}
