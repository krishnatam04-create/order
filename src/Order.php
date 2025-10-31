<?php
require_once 'database.php';

class Order {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function create($items, $pickup_time, $VIP = false) {
        $activeCount = $this->getActiveCount();

        if (!$VIP && $activeCount >= KITCHEN_CAPACITY) {
            $nextTime = $this->suggestNextPickupTime();
            return ['error' => 'Kitchen is full', 'next_available' => $nextTime];
        }

        $stmt = $this->pdo->prepare("INSERT INTO orders (items, pickup_time, VIP) VALUES (:items, :pickup_time, :VIP)");
        $stmt->execute([
            ':items' => json_encode($items),
            ':pickup_time' => $pickup_time,
            ':VIP' => $VIP ? 1 : 0
        ]);
        $id = $this->pdo->lastInsertId();
        return ['id' => $id];
    }

    public function getActiveOrders() {
        $stmt = $this->pdo->query("SELECT * FROM orders WHERE status='active' ORDER BY VIP DESC, created_at ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function completeOrder($id) {
        $stmt = $this->pdo->prepare("UPDATE orders SET status='completed' WHERE id=:id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }

    public function getActiveCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as cnt FROM orders WHERE status='active'");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['cnt'];
    }

    private function suggestNextPickupTime() {
        $stmt = $this->pdo->query("SELECT MAX(pickup_time) as last_pickup FROM orders WHERE status='active'");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $last = $row['last_pickup'] ?? date('Y-m-d H:i:s');
        $time = date('Y-m-d H:i:s', strtotime($last) + 15*60);
        return $time;
    }

    public function autoCompleteOrders() {
        $stmt = $this->pdo->prepare("
            UPDATE orders
            SET status='completed'
            WHERE status='active'
            AND TIMESTAMPDIFF(SECOND, created_at, NOW()) >= :seconds
        ");
        $stmt->execute([':seconds' => AUTO_COMPLETE_SECONDS]);
    } 
}
