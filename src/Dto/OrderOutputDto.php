<?php

namespace App\Dto;

class OrderOutputDto
{
    private int $id = 0;
    private string $description = '';
    private \DateTimeInterface $dateCreated;

    /**
     * @var ProductOrderOutputDto[]
     */
    private array $orders = [];
    private int $countAll = 0;
    private float $sum = .0;
    private float $sumVat = .0;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
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

    public function getDateCreated(): \DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    /**
     * @return ProductOrderOutputDto[]
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

    public function getCountAll(): int
    {
        return $this->countAll;
    }

    public function setCountAll(int $countAll): self
    {
        $this->countAll = $countAll;
        return $this;
    }

    public function getSum(): float
    {
        return $this->sum;
    }

    public function setSum(float $sum): self
    {
        $this->sum = $sum;
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