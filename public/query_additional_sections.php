<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$user = DB::table('users')->where('username', 'clothing')->first();
if ($user) {
    echo "User: " . $user->username . " (ID: " . $user->id . ")\n";
    $sections = DB::table('user_additional_sections')->where('user_id', $user->id)->get();
    foreach ($sections as $sec) {
        echo "Section ID: " . $sec->id . ", Name: " . $sec->section_name . ", Position: " . $sec->possition . "\n";
        $content = DB::table('user_additional_section_contents')->where('addition_section_id', $sec->id)->get();
        foreach ($content as $c) {
            echo "  Language ID: " . $c->language_id . ", Title: " . $c->section_name . "\n";
            echo "  Content Snippet: " . substr(strip_tags($c->content), 0, 100) . "\n";
        }
    }
} else {
    echo "User clothing not found.\n";
}
