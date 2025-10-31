<?php
require_once '../src/OrderController.php';

$controller = new OrderController();
header('Content-Type: application/json');

// Get the path after index.php
$uri = $_SERVER['REQUEST_URI'];
//echo $uri;die;
// Remove query string
$uri = explode('?', $uri)[0];

// Remove folder and index.php prefix
$base = '/order_test/public/index.php';
if (strpos($uri, $base) === 0) {
    $uri = substr($uri, strlen($base));
}
 
$uri = rtrim($uri, '/');
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/orders' && $method === 'POST') {
    $controller->createOrder();
} elseif ($uri === '/orders/active' && $method === 'GET') {
    $controller->listActiveOrders();
} elseif (preg_match('#/orders/(\d+)/complete#', $uri, $matches) && $method === 'POST') {
    $controller->completeOrder($matches[1]);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}
