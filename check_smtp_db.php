<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$results = DB::table('basic_settings')->where('smtp_host', 'like', '%metroshop.in%')->get();
echo "Global settings with metroshop: " . $results->count() . "\n";

$results2 = DB::table('user_email_settings')->where('smtp_host', 'like', '%metroshop.in%')->get();
echo "User settings with metroshop: " . $results2->count() . "\n";

$results3 = DB::table('user_basic_settings')->where('smtp_host', 'like', '%metroshop.in%')->get();
echo "User basic settings with metroshop: " . $results3->count() . "\n";

