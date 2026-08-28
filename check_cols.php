<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "basic_settings cols: " . implode(', ', \Schema::getColumnListing('basic_settings')) . "\n";
echo "user_basic_settings cols (if exists): " . (\Schema::hasTable('user_basic_settings') ? implode(', ', \Schema::getColumnListing('user_basic_settings')) : 'N/A') . "\n";
echo "user_languages cols (if exists): " . (\Schema::hasTable('user_languages') ? implode(', ', \Schema::getColumnListing('user_languages')) : 'N/A') . "\n";

$tables = \DB::select('SHOW TABLES');
$tblKey = 'Tables_in_' . env('DB_DATABASE');
foreach ($tables as $t) {
    $name = $t->$tblKey;
    if (str_contains($name, 'user') || str_contains($name, 'setting') || str_contains($name, 'theme')) {
        echo "Table: {$name}\n";
    }
}
