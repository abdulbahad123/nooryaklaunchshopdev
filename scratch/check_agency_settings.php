<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $rows = DB::table('wb_agency_settings')->get();
    echo "Found " . count($rows) . " rows in wb_agency_settings:\n";
    foreach ($rows as $r) {
        echo "ID: {$r->id} | CustomerID: {$r->customer_id} | CustomDomain: '{$r->custom_domain}' | Status: '{$r->custom_domain_status}' (type: " . gettype($r->custom_domain_status) . ")\n";
    }
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
