<?php

// Read user_item_images.sql
$sqlContent = file_get_contents('user_item_images.sql');

// Extract all (id, item_id, image, created_at, updated_at) tuples
preg_match_all('/\((\d+),\s*(\d+),\s*\'([^\']+)\',\s*\'([^\']+)\',\s*\'([^\']+)\'\)/', $sqlContent, $matches, PREG_SET_ORDER);

echo "Found " . count($matches) . " image rows." . PHP_EOL;

// Group slider images by item_id
$itemSliderMap = []; // item_id => [image1, image2, ...]
foreach ($matches as $m) {
    $itemId = (int)$m[2];
    $img = $m[3];
    if (!isset($itemSliderMap[$itemId])) {
        $itemSliderMap[$itemId] = [];
    }
    // Avoid duplicate images for the same item
    if (!in_array($img, $itemSliderMap[$itemId])) {
        $itemSliderMap[$itemId][] = $img;
    }
}

echo "Unique items with slider images: " . count($itemSliderMap) . PHP_EOL;

// 1. Create database/seeds/whitelabel_agency_user_items.sql
$sqlHeader = "-- Whitelabel Agency User Item Images Master Seed\n";
$sqlHeader .= "-- Auto-generated for Agency Launchshop Product Seeding & Backfill\n\n";
$sqlHeader .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

$sqlRows = [];
foreach ($matches as $m) {
    $id = (int)$m[1];
    $itemId = (int)$m[2];
    $img = addslashes($m[3]);
    $createdAt = $m[4];
    $updatedAt = $m[5];
    $sqlRows[] = "({$id}, {$itemId}, '{$img}', '{$createdAt}', '{$updatedAt}')";
}

// Chunk INSERT statements into blocks of 200
$chunkSize = 200;
$chunks = array_chunk($sqlRows, $chunkSize);
$sqlBody = "";
foreach ($chunks as $chunk) {
    $sqlBody .= "INSERT IGNORE INTO `user_item_images` (`id`, `item_id`, `image`, `created_at`, `updated_at`) VALUES\n" . implode(",\n", $chunk) . ";\n\n";
}
$sqlBody .= "SET FOREIGN_KEY_CHECKS=1;\n";

file_put_contents('database/seeds/whitelabel_agency_user_items.sql', $sqlHeader . $sqlBody);
echo "Created database/seeds/whitelabel_agency_user_items.sql successfully." . PHP_EOL;

// Save itemSliderMap as PHP export string for embedded use in Artisan Command
$phpExport = var_export($itemSliderMap, true);
file_put_contents('scratch/item_slider_map.php', "<?php\nreturn " . $phpExport . ";\n");
echo "Saved item slider map PHP file." . PHP_EOL;
