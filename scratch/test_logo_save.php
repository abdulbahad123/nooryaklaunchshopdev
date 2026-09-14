<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\WebsiteBuilder\WbLandingSetting;

WbLandingSetting::ensureColumnsExist();
$settings = WbLandingSetting::getSettings();
echo "WbLandingSetting columns ensured successfully! ID: " . $settings->id . "\n";
