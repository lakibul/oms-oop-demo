<?php
namespace App;

use App\Order;

class Customer
{
    public string $name;
    public string $type;
    public array  $orders;

    public function __construct(string $name, string $type, array $orders = [])
    {
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
}