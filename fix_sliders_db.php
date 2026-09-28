<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User\HeroSlider;
use App\Models\User\UserItem;
use App\Models\User\UserItemImage;
use App\Models\User\BasicSetting;
use Illuminate\Support\Facades\DB;

echo "=== FIXING SLIDERS IN DATABASE ===\n";

// 1. Fix empty HeroSlider images
$heroSliders = HeroSlider::all();
$fixedHeroCount = 0;
$defaultHeroImages = [
    'homeslide1.png',
    'homeslide2.png',
    'homeslide3.png'
];

foreach ($heroSliders as $index => $hs) {
    if (empty($hs->img)) {
        // Pick a default image based on index or theme
        $userTheme = DB::table('user_basic_settings')->where('user_id', $hs->user_id)->value('theme');
        $imgName = $defaultHeroImages[$index % count($defaultHeroImages)];
        
        if ($userTheme === 'furniture') {
            $imgName = 'homeslide1.png';
        } elseif ($userTheme === 'kids') {
            $imgName = 'homeslide2.png';
        }

        $hs->img = $imgName;
        $hs->save();
        $fixedHeroCount++;
        echo "Fixed HeroSlider ID {$hs->id} (User {$hs->user_id}): assigned '{$imgName}'\n";
    }
}
echo "Total HeroSliders fixed: {$fixedHeroCount}\n";

// 2. Fix missing UserItemImage records for UserItems
$itemsWithoutSliders = UserItem::whereNotIn('id', function($q) {
    $q->select('item_id')->from('user_item_images');
})->get();

$fixedItemImageCount = 0;
foreach ($itemsWithoutSliders as $item) {
    $imgToUse = !empty($item->thumbnail) ? $item->thumbnail : 'placeholder.png';
    UserItemImage::create([
        'item_id' => $item->id,
        'image' => $imgToUse
    ]);
    $fixedItemImageCount++;
}
echo "Total UserItems assigned slider images: {$fixedItemImageCount}\n";

echo "\n=== FINAL DATABASE VERIFICATION ===\n";
echo "HeroSlider count: " . HeroSlider::count() . " (Empty images: " . HeroSlider::whereNull('img')->orWhere('img', '')->count() . ")\n";
echo "UserItem count: " . UserItem::count() . "\n";
echo "UserItemImage count: " . UserItemImage::count() . "\n";
echo "UserItems without sliders: " . UserItem::whereNotIn('id', function($q) { $q->select('item_id')->from('user_item_images'); })->count() . "\n";
echo "=== COMPLETED SUCCESSFULLY ===\n";
