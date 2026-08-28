<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$templateUsers = DB::table('users')->where('preview_template', 1)->get();
$userIds       = $templateUsers->pluck('id')->toArray();
$inList        = implode(',', array_map('intval', $userIds));

$memberships = DB::table('memberships')->whereIn('user_id', $userIds)->get();

$sqlDump  = "\n\n-- Data for memberships (Template user active memberships until 2036)\n";
$sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n";

foreach ($memberships as $row) {
    $array = (array)$row;
    $cols  = '`' . implode('`, `', array_keys($array)) . '`';

    $vals = array_map(function($val) {
        if ($val === null) return 'NULL';
        return DB::connection()->getPdo()->quote((string)$val);
    }, array_values($array));

    $valStr = implode(', ', $vals);
    $sqlDump .= "INSERT IGNORE INTO `memberships` ({$cols}) VALUES ({$valStr});\n";
}

$sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";

$targetSchemaFile = 'd:/xamp/htdocs/Sass_admin/database/schema/launchshop_clean_template.sql';
file_put_contents($targetSchemaFile, $sqlDump, FILE_APPEND);

echo "Exported " . count($memberships) . " membership records to {$targetSchemaFile}!\n";
