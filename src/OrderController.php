<?php
require_once 'Order.php';

class OrderController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new Order();
    }

    public function createOrder() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['items']) || !isset($data['pickup_time'])) {
            http_response_code(400);
            echo json_encode(['error' => 'items and pickup_time required']);
            return;
        }
        $VIP = $data['VIP'] ?? false;
        $result = $this->orderModel->create($data['items'], $data['pickup_time'], $VIP);
        if (isset($result['error'])) {
            http_response_code(429);
        } else {
            http_response_code(201);
        }
        echo json_encode($result);
    }

    public function listActiveOrders() {
        $orders = $this->orderModel->getActiveOrders();
        echo json_encode($orders);
    }

    public function completeOrder($id) {
        $count = $this->orderModel->completeOrder($id);
        if ($count > 0) {
            http_response_code(200);
            echo json_encode(['success' => true]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Order not found']);
        }
    }
}
