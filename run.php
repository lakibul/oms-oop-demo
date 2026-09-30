<?php

require __DIR__ . '/vendor/autoload.php';

use App\Order;

// TODO: build a fake $order array/data here (customer, products, payment method, etc.)

$regularOrder = new Order(
    "John Smith",
    "Regular",
    [
        ['name' => 'Widget', 'price' => 20.0, 'quantity' => 3],
        ['name' => 'Gadget', 'price' => 50.0, 'quantity' => 1],
    ],
    "PayPal"
);

// TODO: call $processor->processOrder($order) (or your current method) and echo/var_dump the result
