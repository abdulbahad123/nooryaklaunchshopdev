<?php
/**
 * Backfill slider images for products that only have 'noimage.jpg' as their slider.
 * This sets the thumbnail as the slider image for such products.
 *
 * Place this file at: public/fix_sliders.php
 * Access it once at: https://your-domain.com/fix_sliders.php?key=fix123
 * Then DELETE this file immediately after.
 */

// Simple security key
if (($_GET['key'] ?? '') !== 'fix123') {
    die('Unauthorized');
}

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$pdo = DB::getPdo();

// Step 1: Find all items that ONLY have 'noimage.jpg' in user_item_images
$noImageItems = DB::table('user_item_images')
    ->select('item_id', DB::raw('COUNT(*) as total'), DB::raw('SUM(image = "noimage.jpg") as noimage_count'))
    ->groupBy('item_id')
    ->havingRaw('total = noimage_count') // all images are noimage.jpg
    ->pluck('item_id')
    ->toArray();

echo "<pre>";
echo "Items with only noimage.jpg as slider: " . count($noImageItems) . "\n\n";

$fixed = 0;
$skipped = 0;

foreach ($noImageItems as $itemId) {
    $item = DB::table('user_items')->where('id', $itemId)->first();
    if (!$item || empty($item->thumbnail) || $item->thumbnail === 'noimage.jpg') {
        $skipped++;
        continue;
    }

    // Replace the noimage.jpg entry with the actual thumbnail
    DB::table('user_item_images')->where('item_id', $itemId)->delete();
    DB::table('user_item_images')->insert([
        'item_id' => $itemId,
        'image' => 'thumbnail/' . $item->thumbnail,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    echo "Fixed item #{$itemId}: thumbnail/{$item->thumbnail}\n";
    $fixed++;
}

// Step 2: Find items with NO slider records at all
$noSliderItems = DB::table('user_items')
    ->leftJoin('user_item_images', 'user_items.id', '=', 'user_item_images.item_id')
    ->whereNull('user_item_images.item_id')
    ->where('user_items.thumbnail', '!=', '')
    ->whereNotNull('user_items.thumbnail')
    ->select('user_items.id', 'user_items.thumbnail')
    ->get();

echo "\nItems with NO slider records: " . $noSliderItems->count() . "\n\n";

foreach ($noSliderItems as $item) {
    if (empty($item->thumbnail) || $item->thumbnail === 'noimage.jpg') {
        $skipped++;
        continue;
    }
    DB::table('user_item_images')->insert([
        'item_id' => $item->id,
        'image' => 'thumbnail/' . $item->thumbnail,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "Added slider for item #{$item->id}: thumbnail/{$item->thumbnail}\n";
    $fixed++;
}

echo "\n\nDONE! Fixed: {$fixed}, Skipped (no thumbnail): {$skipped}\n";
echo "\n⚠️  DELETE THIS FILE IMMEDIATELY AFTER RUNNING!\n";
