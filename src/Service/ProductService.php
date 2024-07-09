<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {}

    public function getProducts(): array
    {
        $productRepository = $this->entityManager->getRepository(Product::class);
        $products = $productRepository->findBy(
            array(),
            array('id' => 'ASC'),
            50,
            0
        );

        $result = [];
        foreach ($products as $product) {
            $result []= [
                'product_id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription() ?? '-',
                'stock_availability' => $product->getStockAvailability(),
                'net_price' => floatval($product->getNetPrice())
            ];
        }

        return $result;
    }
}