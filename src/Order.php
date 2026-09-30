<?php

namespace App;

class Order
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

    public function payment(): string
    {
        if ($this->paymentType === "Credit Card") {
            return "Processing payment via Credit Card.";
        } elseif ($this->paymentType === "PayPal") {
            return "Processing payment via PayPal.";
        } else {
            return "Payment method not supported.";
        }
    }

    public function stock()
    {
        //decrement stock of products
        foreach ($this->products as $product) {
            // Assuming we have a method to decrement stock
            $this->decrementStock($product['name'], $product['quantity']);
        }
    }

    private function decrementStock(string $productName, int $quantity)
    {
        echo "Decrementing stock for {$productName} by {$quantity} units.\n";
    }

    public function persistence() : void
    {
        // Logic to persist order data to a database or storage
        echo "Persisting order data for customer: {$this->customerName}.\n";
    }

    public function notification() : void
    {
        // Logic to send notification to the customer
        echo "Sending notification to {$this->customerName} about the order.\n";
    }

    public function status(): string
    {
        return "Order is being processed.";
    }

}