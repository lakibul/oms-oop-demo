<?php

require __DIR__ . '/vendor/autoload.php';

use App\OrderProcessor;

$processor = new OrderProcessor(
    "Jane Doe",
    "VIP",
    [
        ['name' => 'Widget', 'price' => 20.0, 'quantity' => 3],
        ['name' => 'Gadget', 'price' => 50.0, 'quantity' => 1],
    ],
    "PayPal"
);

echo '<pre>';
echo "Total: " . $processor->calculateTotal() . "\n";
echo $processor->payment() . "\n";
$processor->stock();
$processor->persistence();
$processor->notification();
echo $processor->status();
echo '</pre>';
