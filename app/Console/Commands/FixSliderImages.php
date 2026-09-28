<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixSliderImages extends Command
{
    protected $signature   = 'fix:sliders';
    protected $description = 'One-time fix: backfill slider images from thumbnails across ALL tenant launchshop databases';

    public function handle(): int
    {
        $this->info('Discovering all tenant launchshop databases...');

        $tenantDbs = $this->getAllLaunchshopDbs();
        $this->info('Found ' . count($tenantDbs) . ' database(s): ' . implode(', ', $tenantDbs));
        $this->newLine();

        $totalFixed   = 0;
        $totalSkipped = 0;

        foreach ($tenantDbs as $dbName) {
            $this->line("── Processing DB: <fg=cyan>{$dbName}</>");

            try {
                // Switch to this tenant DB
                DB::purge('mysql');
                config(['database.connections.mysql.database' => $dbName]);
                DB::reconnect('mysql');
                DB::connection('mysql')->getPdo();
            } catch (\Throwable $e) {
                $this->warn("   Cannot connect to {$dbName}: " . $e->getMessage());
                continue;
            }

            // Skip if this DB doesn't have user_item_images (not a launchshop DB)
            try {
                $hasTables = DB::select("SHOW TABLES LIKE 'user_item_images'");
                if (empty($hasTables)) {
                    $this->line("   Skipping (no user_item_images table)");
                    continue;
                }
            } catch (\Throwable $e) {
                $this->warn("   Table check failed: " . $e->getMessage());
                continue;
            }

            [$fixed, $skipped] = $this->fixSlidersInCurrentDb();
            $totalFixed   += $fixed;
            $totalSkipped += $skipped;
            $this->line("   Fixed: {$fixed}  |  Skipped: {$skipped}");
        }

        // Restore original DB
        try {
            $origDb = env('DB_DATABASE');
            DB::purge('mysql');
            config(['database.connections.mysql.database' => $origDb]);
            DB::reconnect('mysql');
        } catch (\Throwable $e) {}

        $this->newLine();
        $this->info("ALL DONE! Total fixed: {$totalFixed}  |  Total skipped (no thumbnail): {$totalSkipped}");

        return Command::SUCCESS;
    }

    private function fixSlidersInCurrentDb(): array
    {
        $fixed   = 0;
        $skipped = 0;

        try {
            // Step 1: Items where ALL slider images are noimage.jpg
            $noImageItems = DB::table('user_item_images')
                ->select('item_id',
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(image = "noimage.jpg") as noimage_count'))
                ->groupBy('item_id')
                ->havingRaw('total = noimage_count')
                ->pluck('item_id')
                ->toArray();

            foreach ($noImageItems as $itemId) {
                $item = DB::table('user_items')->where('id', $itemId)->first();
                if (!$item || empty($item->thumbnail) || $item->thumbnail === 'noimage.jpg') {
                    $skipped++;
                    continue;
                }
                DB::table('user_item_images')->where('item_id', $itemId)->delete();
                DB::table('user_item_images')->insert([
                    'item_id'    => $itemId,
                    'image'      => 'thumbnail/' . $item->thumbnail,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $fixed++;
            }

            // Step 2: Items with NO slider record at all
            $noSliderItems = DB::table('user_items')
                ->leftJoin('user_item_images', 'user_items.id', '=', 'user_item_images.item_id')
                ->whereNull('user_item_images.item_id')
                ->whereNotNull('user_items.thumbnail')
                ->where('user_items.thumbnail', '!=', '')
                ->where('user_items.thumbnail', '!=', 'noimage.jpg')
                ->select('user_items.id', 'user_items.thumbnail')
                ->get();

            foreach ($noSliderItems as $item) {
                DB::table('user_item_images')->insert([
                    'item_id'    => $item->id,
                    'image'      => 'thumbnail/' . $item->thumbnail,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $fixed++;
            }
        } catch (\Throwable $e) {
            $this->warn("   Error: " . $e->getMessage());
        }

        return [$fixed, $skipped];
    }

    private function getAllLaunchshopDbs(): array
    {
        $dbs = [];

        // Get all databases from INFORMATION_SCHEMA that look like launchshop tenant DBs
        try {
            $rows = DB::select(
                "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA
                 WHERE SCHEMA_NAME NOT IN ('information_schema','mysql','performance_schema','sys')
                 AND SCHEMA_NAME LIKE '%launchsh%'"
            );
            foreach ($rows as $r) {
                if (!empty($r->SCHEMA_NAME)) {
                    $dbs[] = $r->SCHEMA_NAME;
                }
            }
        } catch (\Throwable $e) {
            $this->warn('INFORMATION_SCHEMA query failed: ' . $e->getMessage());
        }

        // Also include the current default DB
        $current = config('database.connections.mysql.database');
        if ($current) {
            $dbs[] = $current;
        }

        // Also try known DB names from env
        $known = array_filter([
            env('DB_DATABASE'),
            env('LAUNCHSHOP_MAIN_DB'),
        ]);
        foreach ($known as $k) {
            $dbs[] = $k;
        }

        return array_values(array_unique(array_filter($dbs)));
    }
}
