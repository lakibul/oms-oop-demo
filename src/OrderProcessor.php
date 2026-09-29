<?php

class OrderProcessor
{
    public string $customerName;
    public string $customerType;
    public array  $products;
    public string $paymentType;
    
    public function __construct(string $customerName, string $customerType, array $products, string $paymentType)
    {
        $this->customerName = $customerName;
        $this->customerType = $customerType;
        $this->products = $products;
        $this->paymentType = $paymentType;
    }

    public function calculateTotal(): float
    {
        // Logic to calculate the total amount for the order
        $total = 0;
        foreach ($this->products as $product) {
            $total += $product['price'] * $product['quantity'];
        }
        return $total;
    }
    
}

$order = new OrderProcessor(
    "John Doe",
    "Regular",
    [
        ['name' => 'Product 1', 'price' => 10.0, 'quantity' => 2],
        ['name' => 'Product 2', 'price' => 15.0, 'quantity' => 1],
    ],
    "Credit Card"
);
echo "Total Amount: " . $order->calculateTotal();