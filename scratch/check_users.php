<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$users = User::select('id', 'username', 'email', 'shop_name', 'first_name', 'status', 'preview_template', 'created_at')->orderBy('id', 'desc')->take(20)->get();

echo "Total Users in users table: " . User::count() . "\n\n";
echo sprintf("%-5s | %-15s | %-30s | %-20s | %-6s | %-10s\n", "ID", "Username", "Email", "Shop Name", "Status", "Preview");
echo str_repeat("-", 95) . "\n";

foreach ($users as $u) {
    echo sprintf("%-5d | %-15s | %-30s | %-20s | %-6d | %-10d\n", 
        $u->id, 
        $u->username ?: '(EMPTY)', 
        $u->email ?: '(EMPTY)', 
        $u->shop_name ?: '(EMPTY)', 
        $u->status, 
        $u->preview_template
    );
}
