<?php

namespace App\Dto;

class OrderInputDto
{
    private ?string $description;
    /**
     * @param ProductOrderInputDto[] $orders
     */
    public array $orders;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return ProductOrderInputDto[]
     */
    public function getOrders(): array
    {
        return $this->orders;
    }

    public function setOrders(array $orders): self
    {
        $this->orders = $orders;
        return $this;
    }

}