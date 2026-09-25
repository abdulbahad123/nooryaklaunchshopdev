<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$info = json_encode([
    'key'      => 'rzp_test_T9UaATIMf1qeO8',
    'secret'   => 'BQ9Z865NgRQrrIMCusfzmskZ',
    'currency' => 'INR',
    'status'   => 1
]);

$localDbs = ['nooryak_ps_launchshop', 'nooryak_ps_youverse_website_buil', 'nooryak_ps_funkiddoz_website_buil'];

foreach ($localDbs as $db) {
    try {
        \Illuminate\Support\Facades\DB::purge('mysql');
        config([
            'database.connections.mysql.database' => $db,
            'database.connections.mysql.username' => 'root',
            'database.connections.mysql.password' => '',
        ]);
        \Illuminate\Support\Facades\DB::reconnect('mysql');

        if (\Illuminate\Support\Facades\Schema::hasTable('payment_gateways')) {
            \Illuminate\Support\Facades\DB::table('payment_gateways')
                ->where('keyword', 'razorpay')
                ->orWhere('name', 'Razorpay')
                ->update(['information' => $info, 'status' => 1]);
            echo "Updated payment_gateways in DB '{$db}'.\n";
        }
        if (\Illuminate\Support\Facades\Schema::hasTable('user_payment_gateways')) {
            \Illuminate\Support\Facades\DB::table('user_payment_gateways')
                ->where('keyword', 'razorpay')
                ->orWhere('name', 'Razorpay')
                ->update(['information' => $info, 'status' => 1]);
            echo "Updated user_payment_gateways in DB '{$db}'.\n";
        }
    } catch (\Throwable $e) {
        echo "DB '{$db}' update note: " . $e->getMessage() . "\n";
    }
}

echo "Razorpay credentials update script finished.\n";
