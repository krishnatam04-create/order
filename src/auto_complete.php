<?php

require_once 'database.php'; 

$order = new Order();

$order = new Order();
$order->autoCompleteOrders();

echo "Auto-complete run successfully.\n";

//C:\wamp64\bin\php\php7.4.33\php.exe C:\wamp64\www\order_test\auto_complete.php
