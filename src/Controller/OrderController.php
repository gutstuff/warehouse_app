<?php

namespace App\Controller;

use App\Service\OrderService;
use Symfony\Component\HttpFoundation\Request;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{

    public function __construct(
        private readonly OrderService $orderService
    ) {}
    #[Route('/order', name: 'app_order')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/OrderController.php',
        ]);
    }

    #[Route('/order/new', name: 'app_new_order')]
    public function newOrder(Request $request): JsonResponse
    {
        if (
            $request->getMethod() !== 'POST'
        ) {
            return new JsonResponse([
                    'message' => 'error'
                ], 500
            );
        }

        $payload = $request->getPayload();
        $this->orderService->createOrder(
            [
                'description' => $payload->getString('description'),
                'orders' => $payload->all('orders')
            ]
        );

        return new JsonResponse(
            [
                'description' => $payload->getString('description'),
                'orders' => $payload->all('orders')
            ]
        );
    }
}
