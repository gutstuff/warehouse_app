<?php

namespace App\Service;

use App\Dto\OrderInputDto;
use App\Dto\OrderOutputDto;
use App\Dto\ProductOrderOutputDto;
use App\Dto\ProductOutputDto;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductOrder;
use App\Service\Collector\PolishVATPriceCalculator;
use App\Service\Collector\PriceCalculator;
use App\Service\Collector\PriceCalculatorCollector;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    public const RESULT_ORDERS = 'orders';
    public const RESULT_DESCRIPTION = 'description';

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {}

    public function createOrder(OrderInputDto $orderInputDto): OrderOutputDto
    {
        $order = new Order();
        $order->setDescription($orderInputDto->getDescription() ?? '-');
        $order->setDateCreated(new \DateTime());

        $productRepository = $this->entityManager
            ->getRepository(Product::class);

        foreach ($orderInputDto->getOrders() as $item) {
            $productOrder = new ProductOrder();
            $productOrder->setOrder($order);

            $product = $productRepository->find($item->getId());
            $productOrder->setProduct($product);
            $productOrder->setCount($item->getCount());
            $order->addProductOrder($productOrder);

            $this->entityManager->persist($productOrder);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $this->getOrder($order);
    }

    public function getOrder(Order $order): OrderOutputDto
    {
        $vatPriceCalculator = new PriceCalculatorCollector([
            new PolishVATPriceCalculator()
        ]);

        $priceCalculator = new PriceCalculatorCollector([
            new PriceCalculator()
        ]);

        $orderOutputDto = new OrderOutputDto();

        $orders = [];
        foreach ($order->getProductOrders() as $productOrder) {
            $product = $productOrder->getProduct();
            $orderOutputDto->setCountAll($orderOutputDto->getCountAll() + $productOrder->getCount());
            $orders []= (new ProductOrderOutputDto)
                ->setName($product->getName())
                ->setCount($productOrder->getCount())
                ->setSumVat($vatPriceCalculator->calculate($productOrder))
            ;
        }

        return (new OrderOutputDto())
            ->setId($order->getId())
            ->setDescription($order->getDescription())
            ->setDateCreated($order->getDateCreated())
            ->setOrders($orders)
            ->setSum($priceCalculator->calculateCollection(
                $order->getProductOrders()->toArray()
            ))
            ->setSumVat($vatPriceCalculator->calculateCollection(
                $order->getProductOrders()->toArray()
            ))
        ;
    }

    public function getOrderById(int $id): ?OrderOutputDto
    {
        $orderRepository = $this->entityManager
            ->getRepository(Order::class);

        if (!($order = $orderRepository->find($id))) {
            return null;
        }
        return $this->getOrder($order);
    }
}