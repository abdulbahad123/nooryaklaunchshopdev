<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User\UserOrder;
use App\Models\User\UserShippingGateway;
use App\Http\Controllers\User\ShippingGatewayController;

class TestShiprocketCommand extends Command
{
    protected $signature = 'test:shiprocket {order}';
    protected $description = 'Test Shiprocket order creation';

    public function handle()
    {
        $orderNum = $this->argument('order');
        $order = UserOrder::where('order_number', $orderNum)->first();
        
        if (!$order) {
            $this->error("Order $orderNum not found.");
            return;
        }

        $gateway = UserShippingGateway::where('user_id', $order->user_id)->where('keyword', 'shiprocket')->first();
        if (!$gateway) {
            $this->error("Shiprocket gateway not found for user.");
            return;
        }

        $info = json_decode($gateway->information, true);
        $this->info("Using Shiprocket Email: " . ($info['email'] ?? 'none'));

        $this->info("Triggering Shiprocket API...");
        ShippingGatewayController::createShiprocketOrder($order, $order->user_id);

        if (\Session::has('warning')) {
            $this->error("WARNING: " . \Session::get('warning'));
        } else {
            $this->info("SUCCESS! Order created.");
        }

        $order->refresh();
        $this->info("Tracking URL: " . $order->tracking_url);
    }
}
