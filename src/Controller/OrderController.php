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

    #[Route('/order/new', name: 'app_new_order', methods: ['POST'])]
    public function newOrder(Request $request): JsonResponse
    {
        $payload = $request->getPayload();
        $input = [
            OrderService::RESULT_DESCRIPTION => $payload->getString(OrderService::RESULT_DESCRIPTION),
            OrderService::RESULT_ORDERS => $payload->all(OrderService::RESULT_ORDERS)
        ];
        $result = $this->orderService->createOrder($input);

        return new JsonResponse($result['result'], $result['status']);
    }

    #[Route('/order/{id}', name: 'app_get_order', methods: ['GET'])]
    public function getOrder(Request $request): JsonResponse
    {
        $id = $request->get('id');
        if (!is_numeric($id) || $id < 1) {
            return $this->jsonErrorResponse('Wrong order id');
        }

        if (!($order = $this->orderService->getOrder($id))) {
            return $this->jsonErrorResponse('Order not found');
        }

        return new JsonResponse($order);
    }

    private function jsonErrorResponse(string $message): JsonResponse
    {
        return new JsonResponse([
                'message' => $message
            ], 500
        );
    }
}
