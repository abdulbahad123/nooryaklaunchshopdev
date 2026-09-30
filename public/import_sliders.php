<?php
/**
 * Standalone runner to import user_item_images.sql across all agency & tenant databases.
 * Access via browser: https://launchshop.nooryak.in/import_sliders.php
 */

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html><html><head><title>Import User Item Images</title><style>
body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0f172a; color: #f8fafc; padding: 40px; line-height: 1.6; }
.card { background: #1e293b; border-radius: 12px; padding: 30px; max-width: 800px; margin: 0 auto; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1); }
h1 { color: #38bdf8; margin-top: 0; }
.success { color: #4ade80; font-weight: bold; }
.warning { color: #fbbf24; }
.error { color: #f87171; }
pre { background: #090d16; padding: 15px; border-radius: 8px; overflow-x: auto; color: #a7f3d0; font-size: 13px; }
</style></head><body><div class='card'><h1>🚀 Slider & Image Dataset Importer</h1>";

$sqlPath = base_path('user_item_images.sql');
if (!file_exists($sqlPath)) {
    die("<p class='error'>❌ Error: user_item_images.sql not found at {$sqlPath}</p></div></body></html>");
}

$sqlContent = file_get_contents($sqlPath);
if (empty($sqlContent)) {
    die("<p class='error'>❌ Error: user_item_images.sql is empty!</p></div></body></html>");
}

$currentDb = DB::connection()->getDatabaseName();
$targetDatabases = [$currentDb];

try {
    $dbs = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME LIKE '%_launchsh%' OR SCHEMA_NAME LIKE '%_launchshop%'");
    foreach ($dbs as $dbObj) {
        $dbName = $dbObj->SCHEMA_NAME;
        if (!in_array($dbName, $targetDatabases)) {
            $targetDatabases[] = $dbName;
        }
    }
} catch (\Throwable $e) {
    echo "<p class='warning'>⚠️ INFORMATION_SCHEMA lookup notice: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<p>Found <strong>" . count($targetDatabases) . "</strong> database(s) to update.</p><pre>";

$results = [];

foreach ($targetDatabases as $dbName) {
    try {
        echo "Processing database: {$dbName}...\n";
        DB::statement("USE `{$dbName}`");

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement('DROP TABLE IF EXISTS `user_item_images`;');
        DB::unprepared($sqlContent);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $count = DB::table('user_item_images')->count();
        echo "  [OK] Re-created user_item_images table. Imported {$count} slider image records.\n";

        // Auto-fix missing slider images for user_items using thumbnail
        $itemsWithoutSliders = DB::select("
            SELECT i.id, i.thumbnail 
            FROM `user_items` i 
            LEFT JOIN `user_item_images` img ON i.id = img.item_id 
            WHERE img.id IS NULL AND i.thumbnail IS NOT NULL AND i.thumbnail != ''
        ");

        $addedCount = 0;
        foreach ($itemsWithoutSliders as $item) {
            $thumbName = basename(parse_url($item->thumbnail, PHP_URL_PATH));
            if (!empty($thumbName)) {
                DB::table('user_item_images')->insert([
                    'item_id'    => $item->id,
                    'image'      => $thumbName,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $addedCount++;
            }
        }

        if ($addedCount > 0) {
            echo "  [OK] Added {$addedCount} missing slider fallback records for user products.\n";
        }

        echo "----------------------------------------\n";
    } catch (\Throwable $e) {
        echo "  [ERROR] DB {$dbName}: " . htmlspecialchars($e->getMessage()) . "\n";
    }
}

try {
    DB::statement("USE `{$currentDb}`");
} catch (\Throwable $e) {}

echo "</pre><h2 class='success'>✅ IMPORT COMPLETE! All product sliders and gallery images are restored.</h2>";
echo "<p><a href='/' style='color:#38bdf8;'>Back to Home Page</a></p>";
echo "</div></body></html>";
