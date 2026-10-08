<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-shiprocket-db', function() {
    try {
        $order = \App\Models\User\UserOrder::where('order_number', 'e7L21791374920')->first();
        if (!$order) {
            return "Order not found";
        }
        
        $gateway = \App\Models\User\UserShippingGateway::where('user_id', $order->user_id)->where('keyword', 'shiprocket')->first();
        if (!$gateway) {
            return "Shiprocket not enabled for user";
        }
        
        $info = json_decode($gateway->information, true);
        
        $output = "Order ID: " . $order->id . " Status: " . $order->order_status . "<br>";
        $output .= "Using Shiprocket Email: " . ($info['email'] ?? 'NOT SET') . "<br>";
        
        // Trigger shiprocket
        \App\Http\Controllers\User\ShippingGatewayController::createShiprocketOrder($order, $order->user_id);
        
        if (\Session::has('warning')) {
            $output .= "<br><b style='color:red'>WARNING: " . \Session::get('warning') . "</b>";
            \Session::forget('warning');
        } else {
            $output .= "<br><b style='color:green'>SUCCESS! Order created.</b>";
        }
        
        $order->refresh();
        $output .= "<br>Tracking URL: " . $order->tracking_url;
        
        return $output;
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});
