<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$user = DB::table('users')->where('username', 'sameer')->first();
if ($user) {
    echo "User: " . $user->username . " (ID: " . $user->id . ")\n";
    $bs = DB::table('user_basic_settings')->where('user_id', $user->id)->first();
    if ($bs) {
        echo "Theme: " . $bs->theme . "\n";
        echo "tab_section: " . $bs->tab_section . "\n";
        echo "featured_section: " . $bs->featured_section . "\n";
        echo "top_rated_section: " . $bs->top_rated_section . "\n";
    }
} else {
    echo "User sameer not found.\n";
}
