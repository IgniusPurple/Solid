<?php

interface OrderStorage {
    public function save(array $orderData);
}

class DatabaseStorage implements OrderStorage {
    public function save(array $orderData) {
        // Conexão ao banco de dados e salvamento do pedido
        echo "Salvando pedido no banco de dados.\n";
    }
}

interface NotificationService {
    public function notify();
}

class EmailNotification implements NotificationService {
    public function notify() {
        // Enviar email notificando o cliente sobre o pedido
        echo "Enviando notificação por email.\n";
    }
}

class OrderCalculator {
    public function calculateTotal(array $items) {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}

class Order {
    private $storage;
    private $notification;
    private $calculator;

    public function __construct(OrderStorage $storage, NotificationService $notification, OrderCalculator $calculator) {
        $this->storage = $storage;
        $this->notification = $notification;
        $this->calculator = $calculator;
    }

    public function process(array $items) {
        $total = $this->calculator->calculateTotal($items);
        $this->storage->save(['total' => $total]);
        $this->notification->notify();
    }
}

// Exemplo de uso
$storage = new DatabaseStorage();
$notification = new EmailNotification();
$calculator = new OrderCalculator();
$order = new Order($storage, $notification, $calculator);
$items = [
    ['price' => 10, 'quantity' => 2],
    ['price' => 15, 'quantity' => 1]
];
$order->process($items);