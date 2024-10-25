<?php

class Order {
    public function calculateTotal(array $items) {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function saveToDatabase(array $orderData) {
        // Conexão ao banco de dados e salvamento do pedido
        echo "Salvando pedido no banco de dados.\n";
    }

    public function sendEmailNotification() {
        // Enviar email notificando o cliente sobre o pedido
        echo "Enviando notificação por email.\n";
    }
}

// Exemplo de uso
$order = new Order();
$items = [
    ['price' => 10, 'quantity' => 2],
    ['price' => 15, 'quantity' => 1]
];
$total = $order->calculateTotal($items);
$order->saveToDatabase(['total' => $total]);
$order->sendEmailNotification();
