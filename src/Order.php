<?php

namespace App;

use InvalidArgumentException;

class Order
{
    private string $customerName;
    private string $customerType;
    private array  $products;
    private string $paymentType;

    public function __construct(string $customerName, string $customerType, array $products, string $paymentType)
    {
        if (trim($customerName) === '') {
            throw new InvalidArgumentException('Customer name cannot be empty.');
        }

        if (trim($customerType) === '') {
            throw new InvalidArgumentException('Customer type cannot be empty.');
        }

        if (trim($paymentType) === '') {
            throw new InvalidArgumentException('Payment type cannot be empty.');
        }

        if (empty($products)) {
            throw new InvalidArgumentException('An order must contain at least one product.');
        }

        foreach ($products as $product) {
            if (!isset($product['name'], $product['price'], $product['quantity'])) {
                throw new InvalidArgumentException('Each product must have a name, price, and quantity.');
            }

            if ($product['price'] < 0) {
                throw new InvalidArgumentException("Product price cannot be negative: {$product['price']}");
            }

            if ($product['quantity'] <= 0) {
                throw new InvalidArgumentException("Product quantity must be greater than zero: {$product['quantity']}");
            }
        }

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