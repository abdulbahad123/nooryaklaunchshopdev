<?php
/**
 * Debug script: Check which DB contains the funkiddoz.in custom_domain record
 * Run via CLI: php scratch/debug_funkiddoz.php
 */
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$domain = 'funkiddoz.in';

echo "=== Debugging custom domain: {$domain} ===\n\n";

// 1. Check current DB
$currentDb = config('database.connections.mysql.database');
echo "Current DB: {$currentDb}\n";

// 2. Check all DBs for this domain in wb_agency_settings
try {
    $allDbs = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME NOT IN ('information_schema', 'mysql', 'performance_schema', 'sys')");
    echo "All available databases:\n";
    foreach ($allDbs as $db) {
        echo "  - {$db->SCHEMA_NAME}\n";
    }
    echo "\n";
} catch (\Throwable $e) {
    echo "Error listing DBs: " . $e->getMessage() . "\n";
    $allDbs = [];
}

// 3. Check each DB for the domain
foreach ($allDbs as $dbObj) {
    $dbName = $dbObj->SCHEMA_NAME;
    try {
        DB::purge('mysql');
        config(['database.connections.mysql.database' => $dbName]);
        DB::reconnect('mysql');

        $hasTable = \Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings');
        if (!$hasTable) continue;

        $rows = DB::table('wb_agency_settings')
            ->whereNotNull('custom_domain')
            ->where('custom_domain', '!=', '')
            ->get(['id', 'customer_id', 'custom_domain', 'custom_domain_status']);

        foreach ($rows as $row) {
            $stored = strtolower(trim(preg_replace('#^https?://#', '', $row->custom_domain ?? '')));
            $stored = preg_replace('#^www\.#', '', $stored);
            $stored = explode('/', $stored)[0];
            $stored = preg_replace('/:\d+$/', '', $stored);

            if ($stored === $domain) {
                echo "✅ FOUND in DB [{$dbName}]:\n";
                echo "   wb_agency_settings.id = {$row->id}\n";
                echo "   customer_id = {$row->customer_id}\n";
                echo "   custom_domain = {$row->custom_domain}\n";
                echo "   custom_domain_status = {$row->custom_domain_status} " . ((int)$row->custom_domain_status === 1 ? '(Connected ✅)' : '(Pending/Rejected ❌)') . "\n";

                // Check the customer subdomain
                if ($row->customer_id) {
                    $customer = DB::table('wb_customers')->where('id', $row->customer_id)->first(['id', 'name', 'subdomain', 'email']);
                    if ($customer) {
                        echo "   Customer: {$customer->name} | subdomain: {$customer->subdomain} | email: {$customer->email}\n";
                    } else {
                        echo "   Customer not found for id: {$row->customer_id}\n";
                    }
                }
                echo "\n";
            }
        }
    } catch (\Throwable $e) {
        // DB not accessible
    }
}

// Restore original DB
DB::purge('mysql');
config(['database.connections.mysql.database' => $currentDb]);
DB::reconnect('mysql');

echo "=== Done ===\n";
