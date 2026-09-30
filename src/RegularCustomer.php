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
        // Regular customers do not receive a discount
        return 0.0;
    }
}