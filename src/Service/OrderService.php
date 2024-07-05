<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductOrder;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {}

    public function createOrder(array $data): bool
    {
        $order = new Order();
        $order->setDescription($data['description']);
        $order->setNumber(1);
        $order->setDateCreated(new \DateTime());

        $productRepository = $this->entityManager
            ->getRepository(Product::class);

        $productsToOrder = $data['orders'];
        foreach ($productsToOrder as $item) {
            $productOrder = new ProductOrder();
            $productOrder->setOrder($order);

            $product = $productRepository->find($item['productId']);
            if (!$product) {
                continue;
            }
            $productOrder->setProduct($product);

            $count = $item['count'];
            if (!is_int($count) || $count < 1)
                $count = 1;
            $productOrder->setCount($count);

            $order->addProductOrder($productOrder);

            $this->entityManager->persist($productOrder);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();
        return true;
    }
}