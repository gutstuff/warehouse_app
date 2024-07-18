<?php

namespace App\Dto;

class ProductOrderOutputDto
{
    private string $name;
    private int $count;
    private float $sumVat;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;
        return $this;
    }

    public function getSumVat(): float
    {
        return $this->sumVat;
    }

    public function setSumVat(float $sumVat): self
    {
        $this->sumVat = $sumVat;
        return $this;
    }
}