<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User\HeroSlider;
use App\Models\User\UserItem;
use App\Models\User\UserItemImage;
use App\Models\User\ProductHeroSlider;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "--- DATABASE CHECK ---\n";
echo "HeroSlider count: " . HeroSlider::count() . "\n";
echo "ProductHeroSlider count: " . ProductHeroSlider::count() . "\n";
echo "UserItem count: " . UserItem::count() . "\n";
echo "UserItemImage count: " . UserItemImage::count() . "\n";

echo "\n--- USERS BREAKDOWN ---\n";
$users = User::all();
foreach ($users as $u) {
    $heroCount = HeroSlider::where('user_id', $u->id)->count();
    $itemCount = UserItem::where('user_id', $u->id)->count();
    $itemIds = UserItem::where('user_id', $u->id)->pluck('id');
    $imgCount = UserItemImage::whereIn('item_id', $itemIds)->count();
    echo "User ID {$u->id} ({$u->username}): HeroSliders = {$heroCount}, Items = {$itemCount}, ItemImages = {$imgCount}\n";
}

echo "\n--- HERO SLIDERS DATA ---\n";
foreach (HeroSlider::all() as $hs) {
    echo "ID: {$hs->id}, UserID: {$hs->user_id}, LangID: {$hs->language_id}, Title: {$hs->title}, Image: {$hs->img}\n";
}

echo "\n--- CHECKING PRODUCT SLIDER IMAGES IN FOLDER ---\n";
$sliderDir = public_path('assets/front/img/user/items/slider-images');
if (file_exists($sliderDir)) {
    $files = array_diff(scandir($sliderDir), array('.', '..'));
    echo "Files in assets/front/img/user/items/slider-images: " . count($files) . "\n";
} else {
    echo "Directory assets/front/img/user/items/slider-images does not exist!\n";
}

$heroDir = public_path('assets/front/img/hero_slider');
if (file_exists($heroDir)) {
    $files = array_diff(scandir($heroDir), array('.', '..'));
    echo "Files in assets/front/img/hero_slider: " . count($files) . "\n";
} else {
    echo "Directory assets/front/img/hero_slider does not exist!\n";
}
