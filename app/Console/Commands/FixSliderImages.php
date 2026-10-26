<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Http\Helpers\ItemImageSqlHelper;

class FixSliderImages extends Command
{
    protected $signature   = 'fix:sliders';
    protected $description = 'Backfill full multi-image sliders from master SQL/catalog across ALL tenant launchshop databases';

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
                if (!Schema::hasTable('user_items') || !Schema::hasTable('user_item_images')) {
                    $this->line("   Skipping (missing user_items or user_item_images table)");
                    continue;
                }
            } catch (\Throwable $e) {
                $this->warn("   Table check failed: " . $e->getMessage());
                continue;
            }

            [$fixed, $skipped] = $this->fixSlidersInCurrentDb();
            $totalFixed   += $fixed;
            $totalSkipped += $skipped;
            $this->line("   Fixed items with multi-image sliders: {$fixed}  |  Skipped: {$skipped}");
        }

        // Restore original DB
        try {
            $origDb = env('DB_DATABASE');
            DB::purge('mysql');
            config(['database.connections.mysql.database' => $origDb]);
            DB::reconnect('mysql');
        } catch (\Throwable $e) {}

        $this->newLine();
        $this->info("ALL DONE! Total items upgraded to full sliders: {$totalFixed}  |  Total skipped: {$totalSkipped}");

        return Command::SUCCESS;
    }

    private function fixSlidersInCurrentDb(): array
    {
        $fixed   = 0;
        $skipped = 0;

        try {
            if (!Schema::hasTable('user_items') || !Schema::hasTable('user_item_images')) {
                return [0, 0];
            }

            // Get all items in user_items
            $allItems = DB::table('user_items')
                ->whereNotNull('thumbnail')
                ->where('thumbnail', '!=', '')
                ->where('thumbnail', '!=', 'noimage.jpg')
                ->select('id', 'thumbnail', 'user_id')
                ->get();

            // Find image counts per item
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

                // If item has <= 1 slider image, or slider images are just noimage.jpg
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
                        $fixed++;
                    } elseif ($imgCount === 0 && !empty($item->thumbnail)) {
                        // Custom item with no images: insert clean thumbnail
                        DB::table('user_item_images')->insert([
                            'item_id'    => $item->id,
                            'image'      => basename(ltrim($item->thumbnail, '/')),
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                        $fixed++;
                    } else {
                        $skipped++;
                    }
                }
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
                 AND (SCHEMA_NAME LIKE '%launchsh%' OR SCHEMA_NAME LIKE '%webs%')"
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
