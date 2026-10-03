<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$itemContent = \App\Models\User\UserItemContent::where('slug', 'senior-care-lamb---veggies')->first();
if ($itemContent) {
    echo "Item Content ID: " . $itemContent->id . PHP_EOL;
    echo "Item ID: " . $itemContent->item_id . PHP_EOL;
    $userItem = \App\Models\User\UserItem::find($itemContent->item_id);
    if ($userItem) {
        echo "Thumbnail: " . $userItem->thumbnail . PHP_EOL;
        echo "Thumbnail URL (slider): " . user_item_image_url($userItem->thumbnail, 'slider') . PHP_EOL;
        echo "Thumbnail URL (thumbnail): " . user_item_image_url($userItem->thumbnail, 'thumbnail') . PHP_EOL;
        $sliders = \App\Models\User\UserItemImage::where('item_id', $userItem->id)->get();
        echo "Sliders count: " . $sliders->count() . PHP_EOL;
        foreach ($sliders as $s) {
            echo "  Slider img: " . $s->image . " => " . user_item_image_url($s->image, 'slider') . PHP_EOL;
        }
    }
} else {
    echo "Item content not found for slug senior-care-lamb---veggies" . PHP_EOL;
}
