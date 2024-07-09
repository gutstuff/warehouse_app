<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductOrder;
use App\Service\Collector\PolishVATPriceCalculator;
use App\Service\Collector\PriceCalculator;
use App\Service\Collector\PriceCalculatorCollector;
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

            if (!is_int($item['productId'])) {
                continue;
            }
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

    public function getOrder(int $id): ?array
    {
        $orderRepository = $this->entityManager
            ->getRepository(Order::class);

        if (!($order = $orderRepository->find($id))) {
            return null;
        }

        $orders = [];

        $vatPriceCalculator = new PriceCalculatorCollector([
           new PolishVATPriceCalculator()
        ]);

        $priceCalculator = new PriceCalculatorCollector([
            new PriceCalculator()
        ]);

        $count_all = 0;
        foreach ($order->getProductOrders() as $productOrder) {
            $product = $productOrder->getProduct();
            $count_all += $productOrder->getCount();
            $orders []= [
                'product_id' => $product->getId(),
                'count' => $productOrder->getCount(),
                'net_price' => floatval($product->getNetPrice()),
                'sum' => $priceCalculator->calculate($productOrder),
                'sum_vat' => $vatPriceCalculator->calculate($productOrder)
            ];
        }

        return [
            'description' => $order->getDescription(),
            'date_created' => $order->getDateCreated(),
            'orders' => $orders,
            'count_all' => $count_all,
            'sum' => $priceCalculator->calculateCollection(
                $order->getProductOrders()->toArray()
            ),
            'sum_vat' => $vatPriceCalculator->calculateCollection(
                $order->getProductOrders()->toArray()
            ),
        ];
    }
}