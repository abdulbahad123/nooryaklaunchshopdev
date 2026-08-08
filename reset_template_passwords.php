<?php
/**
 * Reset Template Passwords Script
 * Run this file with: php reset_template_passwords.php
 * 
 * This will reset all template account passwords to: password123
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Hash;
use App\Models\User;

echo "🔄 Resetting template passwords...\n\n";

$newPassword = 'password123';
$hashedPassword = Hash::make($newPassword);

$templates = User::where('preview_template', 1)->get();

foreach ($templates as $template) {
    $template->password = $hashedPassword;
    $template->save();
    
    echo "✅ Reset password for: {$template->username} ({$template->email})\n";
}

echo "\n✨ Done! All template passwords are now: password123\n";
echo "\n📝 Template Login Credentials:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

$templates = User::where('preview_template', 1)
    ->orderBy('template_serial_number', 'ASC')
    ->get();

foreach ($templates as $i => $template) {
    echo ($i + 1) . ". {$template->shop_name}\n";
    echo "   Email: {$template->email}\n";
    echo "   Password: password123\n";
    echo "   Username: {$template->username}\n\n";
}

echo "🌐 Login URL: " . url('/login') . "\n";
