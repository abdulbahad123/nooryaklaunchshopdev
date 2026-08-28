<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$templateUserIds = DB::table('users')->where('preview_template', 1)->pluck('id')->toArray();
$memberships = DB::table('memberships')->whereIn('user_id', $templateUserIds)->get();

echo "Found " . count($memberships) . " membership records for template users.\n";
foreach ($memberships as $m) {
    echo "ID: {$m->id} | User ID: {$m->user_id} | Status: {$m->status} | Start: {$m->start_date} | Expire: {$m->expire_date}\n";
}
