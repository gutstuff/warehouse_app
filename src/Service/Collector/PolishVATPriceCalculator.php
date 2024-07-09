<?php

namespace App\Service\Collector;

use App\Service\Collector\PriceCalculatorInterface;

class PolishVATPriceCalculator implements PriceCalculatorInterface
{

    public function calculate(float $price): float
    {
        return $price * 1.23;
    }
}