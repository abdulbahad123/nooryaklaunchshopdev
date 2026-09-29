<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeedWhitelabelAgencyUserItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:whitelabel-agency-user-items 
                            {--db= : Target specific database name}
                            {--sql= : Path to user_item_images.sql file (defaults to user_item_images.sql in root)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Wipe existing user_item_images and import fresh data from user_item_images.sql across agency databases';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $sqlPath = $this->option('sql') ?: base_path('user_item_images.sql');
        if (!file_exists($sqlPath)) {
            $this->error("SQL file not found at {$sqlPath}");
            return Command::FAILURE;
        }

        $this->info("Loading SQL seed data from: {$sqlPath}");
        $sqlContent = file_get_contents($sqlPath);

        // Extract insert statements
        preg_match_all('/INSERT INTO `user_item_images` [^;]+;/s', $sqlContent, $insertMatches);
        $insertStatements = $insertMatches[0] ?? [];

        if (empty($insertStatements)) {
            $this->error("No valid INSERT INTO `user_item_images` statements found in {$sqlPath}");
            return Command::FAILURE;
        }

        $this->info("Found " . count($insertStatements) . " INSERT statement block(s) in SQL file.");

        $targetDbArg = $this->option('db');
        $tenantDbs   = $targetDbArg ? [$targetDbArg] : $this->discoverTenantDatabases();
        $this->info('Targeting ' . count($tenantDbs) . ' database(s): ' . implode(', ', $tenantDbs));
        $this->newLine();

        $totalWiped = 0;
        $totalImported = 0;

        foreach ($tenantDbs as $dbName) {
            $this->line(" Processing DB: <fg=cyan>{$dbName}</>");

            try {
                DB::purge('mysql');
                config(['database.connections.mysql.database' => $dbName]);
                DB::reconnect('mysql');
                DB::connection('mysql')->getPdo();
            } catch (\Throwable $e) {
                $this->warn("   Cannot connect to {$dbName}: " . $e->getMessage());
                continue;
            }

            if (!Schema::hasTable('user_item_images')) {
                $this->line("   Skipping (user_item_images table missing)");
                continue;
            }

            try {
                // 1. Delete/Wipe existing user_item_images data
                $deleted = DB::table('user_item_images')->delete();
                $totalWiped += $deleted;

                // 2. Import new user_item_images data from SQL file
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                foreach ($insertStatements as $stmt) {
                    DB::statement($stmt);
                }
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');

                $newCount = DB::table('user_item_images')->count();
                $totalImported += $newCount;

                $this->line("   Wiped existing: {$deleted} rows  |  Imported new: {$newCount} rows");
            } catch (\Throwable $e) {
                $this->warn("   Error processing {$dbName}: " . $e->getMessage());
            }
        }

        // Restore default connection
        $origDb = env('DB_DATABASE', config('database.connections.mysql.database'));
        try {
            DB::purge('mysql');
            config(['database.connections.mysql.database' => $origDb]);
            DB::reconnect('mysql');
        } catch (\Throwable $e) {}

        $this->newLine();
        $this->info("COMPLETED SUCCESSFULLY!");
        $this->info("Total rows wiped: {$totalWiped}  |  Total rows imported: {$totalImported}");

        return Command::SUCCESS;
    }

    /**
     * Discover all launchshop databases.
     */
    protected function discoverTenantDatabases(): array
    {
        $dbs = [];

        try {
            $rows = DB::select(
                "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA
                 WHERE SCHEMA_NAME NOT IN ('information_schema','mysql','performance_schema','sys')
                 AND (SCHEMA_NAME LIKE '%launchsh%' OR SCHEMA_NAME LIKE '%webs%')"
            );
            foreach ($rows as $r) {
                if (!empty($r->SCHEMA_NAME)) {
                    $dbs[] = $r->SCHEMA_NAME;
                }
            }
        } catch (\Throwable $e) {}

        $defaultDb = env('DB_DATABASE');
        if ($defaultDb) {
            $dbs[] = $defaultDb;
        }

        $mainDb = env('LAUNCHSHOP_MAIN_DB');
        if ($mainDb) {
            $dbs[] = $mainDb;
        }

        return array_values(array_unique(array_filter($dbs)));
    }
}
