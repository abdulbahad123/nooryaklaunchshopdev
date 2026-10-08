<?php

$mysqli = new mysqli("localhost", "root", "", "nooryak_launchshopp");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$order_number = 'e7L21791374920';
$result = $mysqli->query("SELECT * FROM user_orders WHERE order_number = '$order_number'");

if ($result->num_rows == 0) {
    echo "Order not found\n";
    exit;
}

$order = $result->fetch_assoc();
echo "Order Status: " . $order['order_status'] . "\n";
echo "Shipping Gateway: " . $order['shipping_gateway_keyword'] . "\n";
echo "Tracking URL: " . $order['tracking_url'] . "\n";
echo "User ID: " . $order['user_id'] . "\n";

$user_id = $order['user_id'];
$gw_result = $mysqli->query("SELECT * FROM user_shipping_gateways WHERE user_id = $user_id AND keyword = 'shiprocket'");
if ($gw_result->num_rows > 0) {
    $gw = $gw_result->fetch_assoc();
    $info = json_decode($gw['information'], true);
    echo "Shiprocket Credentials in DB:\n";
    echo "Email: " . ($info['email'] ?? 'NOT SET') . "\n";
} else {
    echo "Shiprocket gateway not found in DB for this user.\n";
}

$mysqli->close();
