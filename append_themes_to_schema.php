<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$templateUsers = DB::table('users')->where('preview_template', 1)->get();
if ($templateUsers->isEmpty()) {
    echo "No template users found.\n";
    exit(1);
}

$userIds = $templateUsers->pluck('id')->toArray();
$inList  = implode(',', array_map('intval', $userIds));

$tablesToExport = [
    'users'                           => "id IN ({$inList})",
    'user_basic_settings'             => "user_id IN ({$inList})",
    'user_basic_extendes'             => "user_id IN ({$inList})",
    'user_additional_sections'        => "user_id IN ({$inList})",
    'user_additional_section_contents'=> "user_id IN ({$inList})",
    'user_counter_information'        => "user_id IN ({$inList})",
    'user_counter_sections'           => "user_id IN ({$inList})",
    'user_email_templates'            => "user_id IN ({$inList})",
    'user_blogs'                      => "user_id IN ({$inList})",
    'user_faqs'                       => "user_id IN ({$inList})",
    'user_languages'                  => "user_id IN ({$inList})",
    'user_headings'                   => "user_id IN ({$inList})",
    'user_seos'                       => "user_id IN ({$inList})",
];

$sqlDump = "\n\n-- ========================================================\n";
$sqlDump .= "-- SEEDED TEMPLATE USERS & THEMES (preview_template = 1)\n";
$sqlDump .= "-- Automatically included so every new agency DB has 11 themes\n";
$sqlDump .= "-- ========================================================\n\n";
$sqlDump .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

foreach ($tablesToExport as $table => $whereClause) {
    try {
        if (!Schema::hasTable($table)) {
            continue;
        }

        $rows = DB::table($table)->whereRaw($whereClause)->get();
        if ($rows->isEmpty()) {
            continue;
        }

        $sqlDump .= "-- Data for {$table}\n";

        foreach ($rows as $row) {
            $array = (array)$row;
            $cols  = '`' . implode('`, `', array_keys($array)) . '`';

            $vals = array_map(function($val) {
                if ($val === null) return 'NULL';
                return DB::connection()->getPdo()->quote((string)$val);
            }, array_values($array));

            $valStr = implode(', ', $vals);
            $sqlDump .= "INSERT IGNORE INTO `{$table}` ({$cols}) VALUES ({$valStr});\n";
        }

        $sqlDump .= "\n";
        echo "Exported " . count($rows) . " rows from {$table}\n";
    } catch (\Throwable $e) {
        echo "Skipped {$table}: " . $e->getMessage() . "\n";
    }
}

$sqlDump .= "SET FOREIGN_KEY_CHECKS=1;\n";

$targetSchemaFile = 'd:/xamp/htdocs/Sass_admin/database/schema/launchshop_clean_template.sql';
file_put_contents($targetSchemaFile, $sqlDump, FILE_APPEND);

echo "Successfully appended template themes data to {$targetSchemaFile}!\n";
