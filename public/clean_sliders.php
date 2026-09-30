<?php
/**
 * Clean invalid, broken, or dummy slider images from user_item_images across agency databases.
 * Access via browser: https://launchshop.funkidoz.in/clean_sliders.php
 */

define('LARAVEL_START', microtime(true));

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

header('Content-Type: text/html; charset=utf-8');
echo "<!DOCTYPE html><html><head><title>Clean Invalid Sliders</title><style>
body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #0f172a; color: #f8fafc; padding: 40px; line-height: 1.6; }
.card { background: #1e293b; border-radius: 12px; padding: 30px; max-width: 800px; margin: 0 auto; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1); }
h1 { color: #38bdf8; margin-top: 0; }
.success { color: #4ade80; font-weight: bold; }
.warning { color: #fbbf24; }
.error { color: #f87171; }
pre { background: #090d16; padding: 15px; border-radius: 8px; overflow-x: auto; color: #a7f3d0; font-size: 13px; }
</style></head><body><div class='card'><h1>🧹 Clean Invalid Product Sliders</h1><pre>";

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
} catch (\Throwable $e) {}

$dummyImages = [
    'fa6f4603b445de7eceaa9a5f5307cc600d4a199e.png',
    '29c6d979278edc97f36301b51f73b4feeff7d18f.png',
    'e2cf46a7b5dbe2044369be7ffbdc87195b2f14f2.png',
    'ec9865898fe0aa6a81fece45b43989975774dc96.png',
    'abb4859aab9e3612fd6e175ab080642324f48a6e.png',
];

$sliderPath = public_path('assets/front/img/user/items/slider-images/');

foreach ($targetDatabases as $dbName) {
    try {
        echo "Cleaning database: {$dbName}...\n";
        DB::statement("USE `{$dbName}`");

        // 1. Remove dummy seed image filenames
        $deletedDummy = DB::table('user_item_images')->whereIn('image', $dummyImages)->delete();

        // 2. Remove orphaned records where item_id doesn't exist in user_items
        $deletedOrphans = DB::statement("
            DELETE img FROM `user_item_images` img 
            LEFT JOIN `user_items` i ON img.item_id = i.id 
            WHERE i.id IS NULL
        ");

        // 3. Remove records pointing to non-existent image files on disk (if directory exists)
        $deletedBroken = 0;
        if (is_dir($sliderPath)) {
            $allImgs = DB::table('user_item_images')->get();
            foreach ($allImgs as $row) {
                if (empty($row->image) || (!file_exists($sliderPath . $row->image) && !file_exists(public_path('assets/front/img/user/items/thumbnail/' . $row->image)))) {
                    DB::table('user_item_images')->where('id', $row->id)->delete();
                    $deletedBroken++;
                }
            }
        }

        echo "  [OK] Deleted {$deletedDummy} dummy images, {$deletedBroken} missing/broken images.\n";
        echo "----------------------------------------\n";
    } catch (\Throwable $e) {
        echo "  [ERROR] DB {$dbName}: " . htmlspecialchars($e->getMessage()) . "\n";
    }
}

try {
    DB::statement("USE `{$currentDb}`");
} catch (\Throwable $e) {}

echo "</pre><h2 class='success'>✅ CLEANUP COMPLETE! Broken/invalid thumbnails have been removed. Products without extra sliders now display cleanly.</h2>";
echo "<p><a href='/' style='color:#38bdf8;'>Back to Home Page</a></p>";
echo "</div></body></html>";
