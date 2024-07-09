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
    public const RESULT_ORDERS = 'orders';
    public const RESULT_DESCRIPTION = 'description';
    private const RESULT_PRODUCT_ID = 'product_id';
    private const RESULT_PRODUCT_COUNT = 'count';

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {}

    private function validateNewOrder(array $data): bool
    {
        if (
            !is_array($data[self::RESULT_ORDERS])
            || !count($data[self::RESULT_ORDERS])
        ) {
            return false;
        }

        $productRepository = $this->entityManager
            ->getRepository(Product::class);

        $productsToOrder = $data[self::RESULT_ORDERS];
        foreach ($productsToOrder as $item) {
            if (
                !array_key_exists(self::RESULT_PRODUCT_ID, $item)
                || !array_key_exists(self::RESULT_PRODUCT_COUNT, $item)
                || !is_int($item[self::RESULT_PRODUCT_ID])
                || !is_int($item[self::RESULT_PRODUCT_COUNT])
                || !$productRepository->find($item[self::RESULT_PRODUCT_ID])
            ) {
                return false;
            }

            $stockAvailability = $productRepository
                ->find($item[self::RESULT_PRODUCT_ID])->getStockAvailability();
            if (
                $item[self::RESULT_PRODUCT_COUNT] < 1
                || $item[self::RESULT_PRODUCT_COUNT] > $stockAvailability
            ) {
                return false;
            }
        }

        return true;
    }

    public function createOrder(array $data): array
    {
        if (!$this->validateNewOrder($data)) {
            return ['status' => 500, 'result' => []];
        }

        $order = new Order();
        $order->setDescription($data[self::RESULT_DESCRIPTION] ?? '-');
        $order->setDateCreated(new \DateTime());

        $productRepository = $this->entityManager
            ->getRepository(Product::class);

        $vatPriceCalculator = new PriceCalculatorCollector([
            new PolishVATPriceCalculator()
        ]);

        $productsToOrder = [];
        $count_all = 0;
        foreach ($data[self::RESULT_ORDERS] as $item) {
            $productOrder = new ProductOrder();
            $productOrder->setOrder($order);

            $product = $productRepository->find($item[self::RESULT_PRODUCT_ID]);
            $productOrder->setProduct($product);
            $productOrder->setCount($item[self::RESULT_PRODUCT_COUNT]);
            $order->addProductOrder($productOrder);

            $this->entityManager->persist($productOrder);

            unset($item[self::RESULT_PRODUCT_ID]);
            $item['name'] = $product->getName();
            $item['sum_vat'] = $vatPriceCalculator->calculate($productOrder);
            $productsToOrder []= $item;

            $count_all += $item[self::RESULT_PRODUCT_COUNT];
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $priceCalculator = new PriceCalculatorCollector([
            new PriceCalculator()
        ]);

        return [
            'status' => 200,
            'result' => [
                'id' => $order->getId(),
                self::RESULT_DESCRIPTION => $order->getDescription(),
                'date_created' => $order->getDateCreated(),
                self::RESULT_ORDERS => $productsToOrder,
                'count_all' => $count_all,
                'sum' => $priceCalculator->calculateCollection(
                    $order->getProductOrders()->toArray()
                ),
                'sum_vat' => $vatPriceCalculator->calculateCollection(
                    $order->getProductOrders()->toArray()
                ),
            ]
        ];
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
                'name' => $product->getName(),
                self::RESULT_PRODUCT_COUNT => $productOrder->getCount(),
                'sum_vat' => $vatPriceCalculator->calculate($productOrder)
            ];
        }

        return [
            self::RESULT_DESCRIPTION => $order->getDescription(),
            'date_created' => $order->getDateCreated(),
            self::RESULT_ORDERS => $orders,
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