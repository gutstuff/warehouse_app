<?php

namespace App\Service;

use App\Dto\ProductOutputDto;
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
            $result []= (new ProductOutputDto())
                ->setId($product->getId())
                ->setName($product->getName())
                ->setDescription($product->getDescription() ?? '-')
                ->setStockAvailability($product->getStockAvailability())
                ->setNetPrice(floatval($product->getNetPrice()))
            ;
        }

        return $result;
    }
}