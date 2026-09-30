<?php

namespace App;

class VipCustomer extends Customer
{
    public function __construct(string $name, array $orders = [])
    {
        parent::__construct($name, "VIP", $orders);
    }

    public function getDiscount(): float
    {
        // VIP customers receive a 10% discount
        return 0.10;
    }
}