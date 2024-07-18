<?php

namespace App\Dto;

class ProductOutputDto
{
    private int $id;
    private string $name;
    private string $description;
    private int $stockAvailability;
    private float $netPrice;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStockAvailability(): int
    {
        return $this->stockAvailability;
    }

    public function setStockAvailability(int $stockAvailability): self
    {
        $this->stockAvailability = $stockAvailability;

        return $this;
    }

    public function getNetPrice(): float
    {
        return $this->netPrice;
    }

    public function setNetPrice(float $netPrice): self
    {
        $this->netPrice = $netPrice;

        return $this;
    }
}