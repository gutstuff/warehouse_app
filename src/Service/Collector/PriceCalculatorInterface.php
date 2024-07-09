<?php

namespace App\Service\Collector;

interface PriceCalculatorInterface
{
    public function calculate(float $price): float;
}