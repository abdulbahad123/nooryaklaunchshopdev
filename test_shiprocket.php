<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User\UserOrder;
use App\Models\User\UserShippingGateway;

$order = UserOrder::where('id', 2555)->orWhere('id', '>', 0)->orderBy('id', 'desc')->first();

if (!$order) {
    echo "No order found.\n";
    exit;
}

echo "Testing order ID: " . $order->id . " - " . $order->order_number . "\n";

// Ensure shiprocket is active for this user
$gateway = UserShippingGateway::where('user_id', $order->user_id)
    ->where('keyword', 'shiprocket')
    ->first();

if (!$gateway) {
    echo "Shiprocket gateway not configured for user " . $order->user_id . "\n";
    exit;
}

$info = json_decode($gateway->information, true);
echo "Shiprocket Email: " . ($info['shiprocket_email'] ?? 'N/A') . "\n";
echo "Shiprocket Password: " . ($info['shiprocket_password'] ?? 'N/A') . "\n";

// Force trigger shiprocket create
try {
    $controller = app(\App\Http\Controllers\User\ShippingGatewayController::class);
    // Fake request or just call the protected method via reflection if possible, but it's easier to hit the bulk process
    
    // Instead of reflection, let's call bulkOrderProcessing in ItemOrderController
    $itemController = app(\App\Http\Controllers\User\ItemOrderController::class);
    
    $request = new \Illuminate\Http\Request();
    $request->merge([
        'order_status' => 'processing',
        'ids' => [$order->id]
    ]);
    
    echo "Triggering bulkOrderProcessing to 'processing' to trigger shiprocket...\n";
    $result = $itemController->bulkOrderProcessing($request);
    echo "Bulk processing result: \n";
    print_r($result);
    
    // Check if order got updated tracking
    $order = $order->fresh();
    echo "\nAfter processing:\n";
    echo "Tracking Number: " . $order->tracking_number . "\n";
    echo "Courier Name: " . $order->courier_name . "\n";
    echo "Tracking URL: " . $order->tracking_url . "\n";

} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
