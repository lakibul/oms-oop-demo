<?php
namespace App;

use App\Order;
use InvalidArgumentException;

abstract class Customer
{
    private string $name;
    private string $type;
    private array  $orders;

    public function __construct(string $name, string $type, array $orders = [])
    {
        if (trim($name) === '') {
            throw new InvalidArgumentException('Customer name cannot be empty.');
        }

        if (trim($type) === '') {
            throw new InvalidArgumentException('Customer type cannot be empty.');
        }

        foreach ($orders as $order) {
            if (!$order instanceof Order) {
                throw new InvalidArgumentException('Every order must be an instance of Order.');
            }
        }

        $this->name = $name;
        $this->type = $type;
        $this->orders = $orders;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addOrder(Order $order): void
    {
        $this->orders[] = $order;
    }

    public function getOrders(): array
    {
        return $this->orders;
    }

    public function getOrderCount(): int
    {
        return count($this->orders);
    }

    public function getTotalSpent(): float
    {
        $total = 0;
        foreach ($this->orders as $order) {
            $total += $order->calculateTotal();
        }
        return $total;
    }

    abstract public function getDiscount(): float;
}