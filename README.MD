# Exemplo de Aplicação de Princípios SOLID em PHP

Este exemplo demonstra como aplicar os princípios SOLID a uma classe PHP que representa um pedido de produtos. Inicialmente, a classe possui várias responsabilidades que podem ser otimizadas seguindo esses princípios. Abaixo está o código original, o código refatorado com SOLID, e explicações sobre as mudanças realizadas.

---

## Código Original (sem SOLID) 

```php
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
```

## **Problemas no Código Original** (app.php)


**1.    Responsabilidade Única** : A classe **Order** realiza cálculos, salva dados no banco de dados e envia notificações, o que viola o princípio da responsabilidade única.

**2.    Aberto/Fechado** : A classe **Order** precisa ser alterada caso haja mudança na forma de calcular o total ou de enviar notificações, o que viola o princípio de estar aberta para extensão, mas fechada para modificação.

**3.    Substituição de Liskov** : Não está claro no código, mas em casos mais complexos, a falta de aderência ao princípio pode gerar problemas em subclasses que deveriam ser intercambiáveis com a classe base.
    
**4.    Segregação de Interface** : A classe **Order** possui métodos que talvez nem todas as instâncias precisem usar. Isso pode ser problemático, pois força a implementação de métodos desnecessários em determinadas situações.

**5.    Inversão de Dependência** : **Order** depende diretamente de implementações específicas para armazenamento e notificação, dificultando a flexibilidade e manutenção do código.

## **Código Refatorado com SOLID** (app_solid.php)
```php
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
```
## **Explicação das Mudanças e Benefícios para a Qualidade do Código**

**1.	Responsabilidade Única (Single Responsibility Principle):** A classe ***Order*** agora apenas coordena o pedido, enquanto **OrderCalculator** cuida do cálculo, **DatabaseStorage** do armazenamento e **EmailNotification** da notificação. Isso torna cada classe responsável por uma única tarefa, facilitando o entendimento e a manutenção.

**2.	Aberto/Fechado (Open/Closed Principle):** **Order** pode agora ser estendida sem modificações. Para adicionar uma nova forma de notificação (ex.: SMS) ou armazenamento (ex.: API), basta criar novas classes que implementem **NotificationService** ou **OrderStorage**, sem alterar o código existente.

**3.	Substituição de Liskov (Liskov Substitution Principle):** Interfaces **OrderStorage** e **NotificationService** garantem que qualquer implementação dessas possa ser usada em Order sem problemas, mantendo o comportamento esperado. Isso proporciona flexibilidade e evita erros de substituição.

**4.	Segregação de Interface (Interface Segregation Principle):** Interfaces **OrderStorage** e **NotificationService** definem contratos específicos e independentes para cada responsabilidade, impedindo que classes dependam de métodos que não utilizam.

**5.	Inversão de Dependência (Dependency Inversion Principle)**: Order agora depende das abstrações **OrderStorage** e **NotificationService**, e não de classes concretas, permitindo substituição fácil por outras implementações (por exemplo, salvar em arquivo ou enviar SMS).


## **Benefícios Gerais**

Esse design modular permite que os componentes sejam testados independentemente, aumenta a reutilização, facilita a manutenção e a extensão do sistema, além de permitir a troca de implementações com menos risco de efeitos colaterais.