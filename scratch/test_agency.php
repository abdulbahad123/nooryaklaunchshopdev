<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$_SERVER['HTTP_HOST'] = 'checkout.youverse.in';
$agency = getAgencyFromHost('checkout.youverse.in');
echo "Agency name: " . ($agency ? ($agency->name ?? 'Found object') : 'NULL') . "\n";
if ($agency) {
    echo "Agency custom_domain: " . ($agency->custom_domain ?? 'N/A') . "\n";
    echo "Agency hero_title: " . ($agency->hero_title ?? 'N/A') . "\n";
}
