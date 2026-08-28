<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$templateUsers = DB::table('users')->where('preview_template', 1)->get();
echo "Found " . count($templateUsers) . " template users in local DB.\n";
foreach ($templateUsers as $u) {
    echo "ID: {$u->id} | Username: {$u->username} | Email: {$u->email}\n";
}
