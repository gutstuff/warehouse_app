<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ProductOrderInputDto
{
    #[Assert\Positive]
    private int $id;
    #[Assert\Positive]
    private int $count;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
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
}