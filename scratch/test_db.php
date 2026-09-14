<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $dbs = DB::select('SHOW DATABASES');
    echo "DATABASES:\n";
    foreach ($dbs as $db) {
        $val = array_values((array)$db)[0];
        echo " - " . $val . "\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
