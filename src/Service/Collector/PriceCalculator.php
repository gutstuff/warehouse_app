<?php

namespace App\Service\Collector;

use App\Service\Collector\PriceCalculatorInterface;

class PriceCalculator implements PriceCalculatorInterface
{

    public function calculate(float $price): float
    {
        return $price;
    }
}