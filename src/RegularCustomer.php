<?php

namespace App;

class RegularCustomer extends Customer
{
    public function __construct(string $name, array $orders = [])
    {
        parent::__construct($name, "Regular", $orders);
    }

    public function getDiscount(): float
    {
        return 0.0;
    }
}