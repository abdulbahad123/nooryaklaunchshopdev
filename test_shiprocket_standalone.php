<?php

$email = 'abdulbahad.dev+api@gmail.com';
$password = 'a66b794532ea986038b8e53d06e0e782';

// 1. Login to Shiprocket
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://apiv2.shiprocket.in/v1/external/auth/login");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['email' => $email, 'password' => $password]));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
if ($response === false) {
    echo "CURL Error: " . curl_error($ch) . "\n";
}

echo "Login Response with $email / [HIDDEN_PASSWORD]:\n";
echo $response . "\n\n";

$data = json_decode($response, true);
$token = $data['token'] ?? '';

if (!$token) {
    echo "No token received. Exiting.\n";
    exit;
}

echo "Token received successfully! Authentication works!\n";
echo "Fetching pickup locations to verify full API access...\n";

// 2. Fetch Pickup Locations
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://apiv2.shiprocket.in/v1/external/settings/company/pickup");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $token));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$pickupResponse = curl_exec($ch);

echo "Pickup Response:\n";
echo substr($pickupResponse, 0, 500) . "...\n\n";

$pickupData = json_decode($pickupResponse, true);
$pickupLocation = 'Primary';
$addresses = [];
if (isset($pickupData['data']['data']) && is_array($pickupData['data']['data'])) {
    $addresses = $pickupData['data']['data'];
} elseif (isset($pickupData['data']['shipping_address']) && is_array($pickupData['data']['shipping_address'])) {
    $addresses = $pickupData['data']['shipping_address'];
} elseif (isset($pickupData['data']) && is_array($pickupData['data'])) {
    $addresses = $pickupData['data'];
}

$hasPickupAddress = false;
if (count($addresses) > 0) {
    foreach ($addresses as $addr) {
        if (!empty($addr['pickup_location'])) {
            $pickupLocation = $addr['pickup_location'];
            $hasPickupAddress = true;
            break;
        }
    }
}

if (!$hasPickupAddress) {
    echo "No pickup location found in account. You MUST set a pickup location in Shiprocket dashboard before orders can be created.\n";
    exit;
}

echo "Using Pickup Location: $pickupLocation\n\n";
echo "SUCCESS! The API user works and has access to Shiprocket settings.\n";
