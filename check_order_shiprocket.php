<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User\UserOrder;

$order = UserOrder::where('order_number', 'e7L21791374920')->first();
if (!$order) {
    echo "Order not found\n";
    exit;
}

echo "Order found: " . $order->id . " (" . $order->order_status . ")\n";
echo "Shipping Gateway: " . $order->shipping_gateway_keyword . "\n";
echo "Tracking URL: " . $order->tracking_url . "\n";

// Let's manually trigger the createShiprocketOrder for this order to see the exact error it returns.
echo "\n--- Re-triggering Shiprocket API for this order ---\n";
app(\App\Http\Controllers\User\ShippingGatewayController::class)::createShiprocketOrder($order, $order->user_id);

echo "\nDone. If there were errors, they were flashed to the session. Checking session...\n";
if (\Session::has('warning')) {
    echo "Session Warning: " . \Session::get('warning') . "\n";
}
if (\Session::has('success')) {
    echo "Session Success: " . \Session::get('success') . "\n";
}

$order->refresh();
echo "\nAfter triggering:\n";
echo "Shipping Gateway: " . $order->shipping_gateway_keyword . "\n";
echo "Tracking URL: " . $order->tracking_url . "\n";
