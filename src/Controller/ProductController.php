<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    #[Route('/product/list', name: 'app_product', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return $this->json($this->productService->getProducts());
    }
}
