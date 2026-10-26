<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Http\Helpers\ItemImageSqlHelper;

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
        $sqlPath = $this->option('sql');
        if (!$sqlPath) {
            $candidates = [
                base_path('user_item_images.sql'),
                database_path('seeds/reset_and_import_user_item_images.sql'),
                database_path('seeds/whitelabel_agency_user_items.sql'),
            ];
            foreach ($candidates as $cand) {
                if (file_exists($cand)) {
                    $sqlPath = $cand;
                    break;
                }
            }
        }

        if (!$sqlPath || !file_exists($sqlPath)) {
            $this->error("SQL seed file not found. Tried candidates: user_item_images.sql, database/seeds/reset_and_import_user_item_images.sql");
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

                // 3. Automatically backfill multi-image sliders for all user_items in this database
                $backfilled = $this->backfillUserItemSliders();
                $this->line("   Wiped existing: {$deleted} rows  |  Imported master: {$newCount} rows  |  Backfilled user items: {$backfilled} items");
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
     * Backfill multi-image sliders for all user_items in the current database.
     */
    protected function backfillUserItemSliders(): int
    {
        $backfilled = 0;
        try {
            if (!Schema::hasTable('user_items') || !Schema::hasTable('user_item_images')) {
                return 0;
            }

            $allItems = DB::table('user_items')
                ->whereNotNull('thumbnail')
                ->where('thumbnail', '!=', '')
                ->where('thumbnail', '!=', 'noimage.jpg')
                ->select('id', 'thumbnail', 'user_id')
                ->get();

            $counts = DB::table('user_item_images')
                ->whereNotNull('image')
                ->where('image', '!=', '')
                ->where('image', '!=', 'noimage.jpg')
                ->select('item_id', DB::raw('COUNT(DISTINCT image) as total'))
                ->groupBy('item_id')
                ->pluck('total', 'item_id')
                ->toArray();

            $now = now();
            foreach ($allItems as $item) {
                $imgCount = $counts[$item->id] ?? 0;
                if ($imgCount <= 1) {
                    $resolvedImages = ItemImageSqlHelper::resolveSliderImagesForItem($item);
                    if (!empty($resolvedImages) && count($resolvedImages) > 1) {
                        DB::table('user_item_images')->where('item_id', $item->id)->delete();
                        $inserts = [];
                        foreach ($resolvedImages as $img) {
                            $inserts[] = [
                                'item_id'    => $item->id,
                                'image'      => basename(ltrim($img, '/')),
                                'created_at' => $now,
                                'updated_at' => $now,
                            ];
                        }
                        DB::table('user_item_images')->insert($inserts);
                        $backfilled++;
                    }
                }
            }
        } catch (\Throwable $e) {
            $this->warn("   Backfill warning: " . $e->getMessage());
        }

        return $backfilled;
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
