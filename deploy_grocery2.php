<?php
// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

echo "<pre>";
$sourceUser = User::where('username', 'grocery')->first();
if (!$sourceUser) {
    die("Error: Source template user 'grocery' not found on live database!\n");
}

$targetUsername = 'ecomgrocery';
$targetEmail = 'ecomgrocery@example.com';

$targetUser = User::where('username', $targetUsername)->first();
if (!$targetUser) {
    $targetUser = $sourceUser->replicate();
    $targetUser->username = $targetUsername;
    $targetUser->email = $targetEmail;
    $targetUser->password = Hash::make('password123');
    $targetUser->first_name = 'Ecom Grocery';
    $targetUser->last_name = 'Template';
    $targetUser->shop_name = 'Ecom Grocery';
    $targetUser->template_img = 'grocery2.png';
    $targetUser->preview_template = 1;
    $targetUser->featured = 1;
    $targetUser->status = 1;
    $targetUser->online_status = 1;
    $targetUser->save();
    echo "Created live template user '{$targetUsername}' with ID: {$targetUser->id}\n";
} else {
    echo "User '{$targetUsername}' already exists with ID: {$targetUser->id}\n";
}
// 1. Copy user_languages first and build mapping for foreign keys
DB::table('user_languages')->where('user_id', $targetUser->id)->delete();
$sourceLangs = DB::table('user_languages')->where('user_id', $sourceUser->id)->orderBy('id', 'asc')->get();
$langMap = [];
foreach ($sourceLangs as $sl) {
    $arr = (array)$sl;
    $oldId = $arr['id'];
    unset($arr['id']);
    $arr['user_id'] = $targetUser->id;
    $newId = DB::table('user_languages')->insertGetId($arr);
    $langMap[$oldId] = $newId;
}
echo "Copied " . count($langMap) . " user_languages with mapped IDs.\n";

$targetDefaultLangId = DB::table('user_languages')
    ->where('user_id', $targetUser->id)
    ->where('is_default', 1)
    ->value('id') ?? reset($langMap) ?? 1;

// 2. Copy user_item_categories explicitly and build $catMap
DB::table('user_item_categories')->where('user_id', $targetUser->id)->delete();
$sourceCats = DB::table('user_item_categories')->where('user_id', $sourceUser->id)->get();
$catMap = [];
foreach ($sourceCats as $cat) {
    $arr = (array)$cat;
    $oldCatId = $arr['id'];
    unset($arr['id']);
    $arr['user_id'] = $targetUser->id;
    if (isset($arr['language_id']) && isset($langMap[$arr['language_id']])) {
        $arr['language_id'] = $langMap[$arr['language_id']];
    }
    $newCatId = DB::table('user_item_categories')->insertGetId($arr);
    $catMap[$oldCatId] = $newCatId;
}
echo "Copied " . count($catMap) . " user_item_categories with mapped IDs.\n";

// 3. Copy user_items explicitly and build $itemMap
DB::table('user_items')->where('user_id', $targetUser->id)->delete();
$sourceItems = DB::table('user_items')->where('user_id', $sourceUser->id)->get();
$itemMap = [];
foreach ($sourceItems as $it) {
    $arr = (array)$it;
    $oldItemId = $arr['id'];
    unset($arr['id']);
    $arr['user_id'] = $targetUser->id;
    $newItemId = DB::table('user_items')->insertGetId($arr);
    $itemMap[$oldItemId] = $newItemId;
}
echo "Copied " . count($itemMap) . " user_items with mapped IDs.\n";

// 4. Copy user_item_contents explicitly with mapped item_id, category_id, language_id
DB::table('user_item_contents')->where('user_id', $targetUser->id)->delete();
$sourceItemContents = DB::table('user_item_contents')->where('user_id', $sourceUser->id)->get();
$contentCount = 0;
foreach ($sourceItemContents as $ic) {
    $arr = (array)$ic;
    unset($arr['id']);
    $arr['user_id'] = $targetUser->id;
    if (isset($arr['item_id']) && isset($itemMap[$arr['item_id']])) {
        $arr['item_id'] = $itemMap[$arr['item_id']];
    }
    if (isset($arr['category_id']) && isset($catMap[$arr['category_id']])) {
        $arr['category_id'] = $catMap[$arr['category_id']];
    }
    if (isset($arr['language_id']) && isset($langMap[$arr['language_id']])) {
        $arr['language_id'] = $langMap[$arr['language_id']];
    }
    DB::table('user_item_contents')->insert($arr);
    $contentCount++;
}
echo "Copied {$contentCount} user_item_contents with mapped foreign keys.\n";

// 5. Copy remaining related tables dynamically
$tables = DB::select('SHOW TABLES');
$dbName = env('DB_DATABASE');
$keyName = "Tables_in_" . $dbName;
$skipTables = ['users', 'memberships', 'user_custom_domains', 'user_languages', 'user_item_categories', 'user_items', 'user_item_contents'];

foreach ($tables as $tableInfo) {
    $tableName = $tableInfo->$keyName;
    if (in_array($tableName, $skipTables)) {
        continue;
    }
    
    if (Schema::hasColumn($tableName, 'user_id')) {
        DB::table($tableName)->where('user_id', $targetUser->id)->delete();
        $records = DB::table($tableName)->where('user_id', $sourceUser->id)->get();
        $count = 0;
        foreach ($records as $record) {
            $array = (array)$record;
            unset($array['id']);
            $array['user_id'] = $targetUser->id;
            
            if (isset($array['language_id']) && isset($langMap[$array['language_id']])) {
                $array['language_id'] = $langMap[$array['language_id']];
            }
            if (isset($array['category_id']) && isset($catMap[$array['category_id']])) {
                $array['category_id'] = $catMap[$array['category_id']];
            }
            if (isset($array['item_id']) && isset($itemMap[$array['item_id']])) {
                $array['item_id'] = $itemMap[$array['item_id']];
            }
            
            DB::table($tableName)->insert($array);
            $count++;
        }
        if ($count > 0) {
            echo "Copied {$count} records for table: {$tableName}\n";
        }
    }
}

// Update settings to Ecom Grocery Theme (grocery2)
DB::table('user_basic_settings')->where('user_id', $targetUser->id)->update([
    'theme' => 'grocery2'
]);

// Update hero sliders to use the new banner image and reference title
DB::table('user_hero_sliders')->where('user_id', $targetUser->id)->delete();
DB::table('user_hero_sliders')->insert([
    'user_id' => $targetUser->id,
    'language_id' => $targetDefaultLangId,
    'img' => 'ecom_grocery_banner_clean.png',
    'subtitle' => 'Delicious Fruits from South Africa in our Grocery deals',
    'text' => 'Sign up for the daily newsletter',
    'btn_name' => 'Buy Now',
    'btn_url' => '/shop',
    'serial_number' => 1,
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s'),
]);

// Seed side promo banners in user_banners
DB::table('user_banners')->where('user_id', $targetUser->id)->delete();
DB::table('user_banners')->insert([
    [
        'user_id' => $targetUser->id,
        'language_id' => $targetDefaultLangId,
        'banner_img' => 'ecom_onion_promo.png',
        'title' => 'Everyday Fresh & Clean with Our Products',
        'banner_url' => '/shop',
        'serial_number' => 1,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ],
    [
        'user_id' => $targetUser->id,
        'language_id' => $targetDefaultLangId,
        'banner_img' => 'ecom_juice_promo.png',
        'title' => 'Everyday Fresh & Clean with Our Products',
        'banner_url' => '/shop',
        'serial_number' => 2,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]
]);

// Update products to use apples/veg thumbnails
$items = DB::table('user_items')->where('user_id', $targetUser->id)->get();
$i = 0;
$secondaryImages = [
    'ecom_organic_veg.png',
    'ecom_prod_tomatoes.png',
    'ecom_prod_red_onions.png',
    'ecom_prod_vanilla.png',
    'ecom_prod_thali.png',
    'ecom_prod_cauliflower.png',
    'ecom_organic_apples.png'
];
foreach ($items as $item) {
    $thumb = ($i % 2 === 0) ? 'ecom_organic_apples.png' : 'ecom_organic_veg.png';
    DB::table('user_items')->where('id', $item->id)->update([
        'thumbnail' => $thumb
    ]);
    
    // Seed secondary slider image in user_item_images
    DB::table('user_item_images')->where('item_id', $item->id)->delete();
    DB::table('user_item_images')->insert([
        'item_id' => $item->id,
        'image' => $secondaryImages[$i % count($secondaryImages)],
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);
    $i++;
}
echo "Assigned new banner, thumbnails, and dual slider images to live template user.\n";

// Set up active template membership
$firstPackage = DB::table('packages')->first();
if ($firstPackage) {
    DB::table('memberships')->where('user_id', $targetUser->id)->delete();
    DB::table('memberships')->insert([
        'price' => $firstPackage->price,
        'currency' => 'INR',
        'currency_symbol' => '₹',
        'payment_method' => 'Offline',
        'transaction_id' => 'template_' . uniqid(),
        'status' => 1,
        'is_trial' => 0,
        'trial_days' => 0,
        'package_id' => $firstPackage->id,
        'user_id' => $targetUser->id,
        'start_date' => date('Y-m-d'),
        'expire_date' => '2036-12-31',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);
    echo "Assigned active live membership.\n";
}

Artisan::call('view:clear');
echo "Cleared compiled view cache.\n";

echo "\nDeployment database seeding completed successfully!";
