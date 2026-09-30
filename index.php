<?php

require __DIR__ . '/vendor/autoload.php';

use App\Order;
use App\VipCustomer;
use App\RegularCustomer;


// One order for the regular customer
$regularOrder = new Order(
    "John Smith",
    "Regular",
    [
        ['name' => 'Widget', 'price' => 20.0, 'quantity' => 3],
        ['name' => 'Gadget', 'price' => 50.0, 'quantity' => 1],
    ],
    "PayPal"
);

// A separate order for the VIP customer
$vipOrder = new Order(
    "Alice Johnson",
    "VIP",
    [
        ['name' => 'Premium Widget', 'price' => 40.0, 'quantity' => 2],
    ],
    "Credit Card"
);

echo '<pre>';
echo "-------------------------\n";
echo "Order initiates:\n";
echo "-------------------------\n";
echo "Total: " . $regularOrder->calculateTotal() . "\n";
echo $regularOrder->payment() . "\n";
$regularOrder->stock();
$regularOrder->persistence();
$regularOrder->notification();
echo $regularOrder->status() . "\n";
echo "-------------------------\n";

echo "Order for Regular Customer:\n";
echo "-------------------------\n";
$regularCustomer = new RegularCustomer("John Smith");
$regularCustomer->addOrder($regularOrder);
echo "Customer Name: " . $regularCustomer->getName() . "\n";
echo "Total Orders: " . $regularCustomer->getOrderCount() . "\n";
$totalSpent = $regularCustomer->getTotalSpent();
echo "Total Spent: $" . number_format($totalSpent, 2) . "\n";
echo "-------------------------\n";

echo "Order for VIP Customer:\n";
echo "-------------------------\n";
$vipCustomer = new VipCustomer("Alice Johnson");
$vipCustomer->addOrder($vipOrder);
echo "Customer Name: " . $vipCustomer->getName() . "\n";
echo "Total Orders: " . $vipCustomer->getOrderCount() . "\n";
$totalSpent = $vipCustomer->getTotalSpent();
echo "Total Spent: $" . number_format($totalSpent, 2) . "\n";
echo '</pre>';