<?php
// 1. Read user_items from launchshop_clean_template.sql to get id => thumbnail
$cleanSql = file_get_contents('database/schema/launchshop_clean_template.sql');

// Extract INSERT INTO user_items
preg_match_all('/INSERT INTO `user_items` [^;]+;/s', $cleanSql, $itemInserts);
$idToThumb = [];
$thumbToId = [];

foreach ($itemInserts[0] as $insertStmt) {
    // Parse rows in INSERT statement
    preg_match_all('/\((\d+),\s*([^\)]+)\)/s', $insertStmt, $rows, PREG_SET_ORDER);
    // Let's refine matching for user_items values
}

// Alternatively, let's query the local database connection if available
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $items = DB::table('user_items')->get(['id', 'thumbnail', 'title']);
    echo "Local user_items count: " . count($items) . PHP_EOL;
    $sliderImages = DB::table('user_item_images')->get();
    echo "Local user_item_images count: " . count($sliderImages) . PHP_EOL;

    $itemSliderMap = [];
    foreach ($sliderImages as $img) {
        $itemSliderMap[$img->item_id][] = $img->image;
    }

    $mappedCount = 0;
    foreach ($items as $item) {
        if (isset($itemSliderMap[$item->id])) {
            $mappedCount++;
        }
    }
    echo "Mapped items count in local DB: " . $mappedCount . PHP_EOL;
} catch (\Throwable $e) {
    echo "Error querying DB: " . $e->getMessage() . PHP_EOL;
}
