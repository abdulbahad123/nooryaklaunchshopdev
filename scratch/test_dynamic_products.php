<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$agency = getAgencyFromHost('checkout.youverse.in');
echo "Agency: " . ($agency ? $agency->name : 'NULL') . "\n";
if ($agency) {
    echo "Purchased Products Count: " . count($agency->purchased_products ?? []) . "\n";
    foreach (($agency->purchased_products ?? []) as $p) {
        $pName = is_object($p) ? $p->name : $p['name'];
        $pUrl  = is_object($p) ? $p->url : $p['url'];
        echo "  - Product: {$pName} | URL: {$pUrl}\n";
    }
}
