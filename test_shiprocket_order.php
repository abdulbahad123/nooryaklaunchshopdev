<?php

$email = 'wezan.bahad@gmail.com';
$password = 't3H#WmI6hjzzwnZW*#dV8^WPP6VZn2$k';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://apiv2.shiprocket.in/v1/external/auth/login");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['email' => $email, 'password' => $password]));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$data = json_decode($response, true);
$token = $data['token'] ?? '';

if (!$token) {
    echo "Shiprocket Auth Response:\n" . $response . "\n";
    echo "Auth failed.\n"; exit;
}

echo "Authenticated. Attempting to create a mock order...\n";

$orderData = [
    'order_id' => 'TEST-' . time(),
    'order_date' => date('Y-m-d H:i'),
    'pickup_location' => 'Home',
    
    'billing_customer_name' => 'John',
    'billing_last_name' => 'Doe',
    'billing_address' => '123 Test St',
    'billing_address_2' => '',
    'billing_city' => 'Mumbai',
    'billing_pincode' => '400001',
    'billing_state' => 'Maharashtra',
    'billing_country' => 'India',
    'billing_email' => 'test@example.com',
    'billing_phone' => '9360157880',
    
    'shipping_is_billing' => false,
    'shipping_customer_name' => 'John',
    'shipping_last_name' => 'Doe',
    'shipping_address' => '123 Test St',
    'shipping_city' => 'Mumbai',
    'shipping_pincode' => '400001',
    'shipping_state' => 'Maharashtra',
    'shipping_country' => 'India',
    'shipping_email' => 'test@example.com',
    'shipping_phone' => '9360157880',

    'order_items' => [
        [
            'name' => 'Test Product',
            'sku' => 'TEST-SKU',
            'units' => 1,
            'selling_price' => 100,
        ]
    ],
    'payment_method' => 'Prepaid',
    'sub_total' => 100,
    'length' => 10,
    'breadth' => 10,
    'height' => 10,
    'weight' => 0.5,
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://apiv2.shiprocket.in/v1/external/orders/create/adhoc");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $token));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
echo "Shiprocket Response:\n" . $response . "\n";
