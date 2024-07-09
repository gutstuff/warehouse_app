<?php

namespace App\Service\Collector;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductOrder;

class PriceCalculatorCollector
{
    /**
     * @param PriceCalculatorInterface[] $priceCalculators
     */
    public function __construct(
        private readonly array $priceCalculators
    ) {}

    public function calculate(ProductOrder $productOrder): float
    {
        $price = floatval($productOrder->getProduct()->getNetPrice());

        foreach ($this->priceCalculators as $calculator) {
            $price = $calculator->calculate($price) * $productOrder->getCount();
        }

        return $price;
    }

    /**
     * @param ProductOrder[] $productOrders
     */
    public function calculateCollection(array $productOrders): float
    {
        $price = 0.;
        foreach ($productOrders as $productOrder) {
            $price += $this->calculate($productOrder);
        }

        return $price;
    }
}