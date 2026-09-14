<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('https://checkout.youverse.in/', 'GET');
$route = app('router')->getRoutes()->match($request);

echo "Matched Route Name: " . $route->getName() . "\n";
echo "Matched Controller Action: " . $route->getActionName() . "\n";
