<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User\UserItem;
use App\Models\User\UserItemContent;
use App\Models\User\UserItemImage;

$cilantro = UserItemContent::where('title', 'like', '%Cilantro%')->first();
echo "--- Cilantro ---\n";
if ($cilantro) {
    echo "Item ID: " . $cilantro->item_id . "\n";
    $item = UserItem::find($cilantro->item_id);
    echo "Thumbnail: " . ($item->thumbnail ?? 'N/A') . "\n";
    $sliders = UserItemImage::where('item_id', $cilantro->item_id)->get();
    echo "Slider count in DB: " . $sliders->count() . "\n";
    foreach ($sliders as $s) {
        echo " - Image: " . $s->image . "\n";
        $sliderPath = public_path('assets/front/img/user/items/slider-images/' . $s->image);
        $thumbPath  = public_path('assets/front/img/user/items/thumbnail/' . $s->image);
        echo "   Exists in slider-images? " . (file_exists($sliderPath) ? "YES" : "NO") . " ($sliderPath)\n";
        echo "   Exists in thumbnail? " . (file_exists($thumbPath) ? "YES" : "NO") . " ($thumbPath)\n";
    }
} else {
    echo "Cilantro not found.\n";
}

$broccoli = UserItemContent::where('title', 'like', '%Broccoli%')->first();
echo "\n--- Broccoli ---\n";
if ($broccoli) {
    echo "Item ID: " . $broccoli->item_id . "\n";
    $item = UserItem::find($broccoli->item_id);
    echo "Thumbnail: " . ($item->thumbnail ?? 'N/A') . "\n";
    $sliders = UserItemImage::where('item_id', $broccoli->item_id)->get();
    echo "Slider count in DB: " . $sliders->count() . "\n";
    foreach ($sliders as $s) {
        echo " - Image: " . $s->image . "\n";
        $sliderPath = public_path('assets/front/img/user/items/slider-images/' . $s->image);
        $thumbPath  = public_path('assets/front/img/user/items/thumbnail/' . $s->image);
        echo "   Exists in slider-images? " . (file_exists($sliderPath) ? "YES" : "NO") . " ($sliderPath)\n";
        echo "   Exists in thumbnail? " . (file_exists($thumbPath) ? "YES" : "NO") . " ($thumbPath)\n";
    }
} else {
    echo "Broccoli not found.\n";
}
