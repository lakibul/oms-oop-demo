<?php

namespace App;

use InvalidArgumentException;

final class Product
{
    private string $name;
    private float $price;

    public function __construct(string $name, float $price)
    {
        if ($price < 0) {
            throw new InvalidArgumentException("Price cannot be negative: {$price}");
        }
        $this->name = $name;
        $this->price = $price;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }
}