<?php
// Set execution time limit to 5 minutes just in case
set_time_limit(300);

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

echo "<h2>LaunchShop: Clothing Template Database Configurator</h2>";

// 1. Resolve source and target user IDs dynamically by username
$sourceUsername = 'fashclo';
$targetUsername = 'clothing';

$sourceUser = DB::table('users')->where('username', $sourceUsername)->first();
if (!$sourceUser) {
    die("<h3 style='color:red;'>Error: Source template user '{$sourceUsername}' not found in database.</h3>");
}
$sourceUid = $sourceUser->id;

$targetUser = DB::table('users')->where('username', $targetUsername)->first();
if (!$targetUser) {
    die("<h3 style='color:red;'>Error: Target template user '{$targetUsername}' not found in database.</h3>");
}
$targetUid = $targetUser->id;

echo "<p>Resolved Source User '{$sourceUsername}' ID: <strong>{$sourceUid}</strong></p>";
echo "<p>Resolved Target User '{$targetUsername}' ID: <strong>{$targetUid}</strong></p>";

// 1.5. Clean up old tenant data to prevent duplicates / mixed catalog items
echo "<h3>Step 0.5: Cleaning up existing catalog and settings...</h3>";
$tablesToDelete = [
    'user_item_categories',
    'user_item_sub_categories',
    'variant_contents',
    'variant_option_contents',
    'product_variation_contents',
    'product_variant_option_contents',
    'product_variant_options',
    'product_variations',
    'user_item_contents',
    'user_items',
    'user_sections',
    'user_seos',
    'user_basic_extendes',
    'user_hero_sliders',
    'user_banners',
    'user_tabs',
    'user_product_hero_sliders',
    'user_howit_work_sections',
    'user_counter_information',
    'user_counter_sections',
    'user_call_to_actions',
    'user_about_testimonials',
    'user_ulinks',
    'static_hero_sections',
    'user_about_us',
    'user_about_us_features',
    'user_contacts',
    'user_faqs',
    'user_features',
    'user_headers',
    'user_headings',
    'user_menus'
];

if (Schema::hasTable('user_items')) {
    $targetItemIds = DB::table('user_items')->where('user_id', $targetUid)->pluck('id')->toArray();
    if (!empty($targetItemIds) && Schema::hasTable('user_item_images')) {
        DB::table('user_item_images')->whereIn('item_id', $targetItemIds)->delete();
    }
}

foreach ($tablesToDelete as $table) {
    if (Schema::hasTable($table)) {
        DB::table($table)->where('user_id', $targetUid)->delete();
    }
}
echo "Cleaned up old records.<br>";

// 2. Copy foundational tables
echo "<h3>Step 1: Copying foundational tables...</h3>";

$tablesToCopy = [
    'user_languages',
    'user_currencies',
    'user_payment_gateways',
    'user_socials'
];

foreach ($tablesToCopy as $table) {
    DB::table($table)->where('user_id', $targetUid)->delete();
    $rows = DB::table($table)->where('user_id', $sourceUid)->get();
    foreach ($rows as $row) {
        $data = (array) $row;
        unset($data['id']);
        $data['user_id'] = $targetUid;
        if (array_key_exists('created_at', $data)) {
            $data['created_at'] = now();
        }
        if (array_key_exists('updated_at', $data)) {
            $data['updated_at'] = now();
        }
        DB::table($table)->insert($data);
    }
    $count = DB::table($table)->where('user_id', $targetUid)->count();
    echo "Table '$table': Copied $count records.<br>";
}

// 3. Run template seeder artisan command
echo "<h3>Step 2: Seeding template catalog...</h3>";
try {
    Artisan::call('template:seed-user', [
        'user' => $targetUsername,
        '--source' => $sourceUsername,
        '--force' => true
    ]);
    $output = Artisan::output();
    echo "<pre>" . htmlentities($output) . "</pre>";
} catch (\Throwable $th) {
    echo "<div style='color:red;'>Error seeding: " . $th->getMessage() . "</div><br>";
}

// 3.5 Seed/Update static hero sections for clothing user
echo "<h3>Step 2.5: Seeding static hero section...</h3>";
try {
    $srcHeroBg = __DIR__ . '/assets/user-front/images/fashion/banner/banner-1.jpg';
    $dstHeroBg = __DIR__ . '/assets/front/img/hero-section/clothing_hero_bg.jpg';
    if (file_exists($srcHeroBg)) {
        @mkdir(dirname($dstHeroBg), 0775, true);
        @copy($srcHeroBg, $dstHeroBg);
        echo "Copied fashion banner to hero-section background image.<br>";
    } else {
        echo "Warning: fashion banner not found at $srcHeroBg<br>";
    }

    $clothingLangs = DB::table('user_languages')->where('user_id', $targetUid)->get();
    foreach ($clothingLangs as $lang) {
        $title = ($lang->code === 'ar') ? 'أناقة <em>مُعاد تعريفها</em> لأجلك' : 'Style <em>Redefined</em> For You';
        $subtitle = ($lang->code === 'ar') ? 'وصول موسم جديد' : 'New Season Arrivals';
        $btnText = ($lang->code === 'ar') ? 'تسوق الآن' : 'Shop Now';
        $btnUrl = '/' . $targetUsername . '/shop';

        DB::table('static_hero_sections')->updateOrInsert(
            ['user_id' => $targetUid, 'language_id' => $lang->id],
            [
                'title' => $title,
                'subtitle' => $subtitle,
                'button_text' => $btnText,
                'button_url' => $btnUrl,
                'background_image' => 'clothing_hero_bg.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        echo "Seeded static hero section for language: {$lang->code}<br>";
    }
} catch (\Throwable $th) {
    echo "<div style='color:red;'>Error seeding static hero: " . $th->getMessage() . "</div><br>";
}


// 4. Clear cache
echo "<h3>Step 3: Clearing cache...</h3>";
Artisan::call('view:clear');
Artisan::call('cache:clear');
echo "Cache cleared successfully!<br>";

echo "<h2 style='color:green;'>SUCCESS! You can now visit your live preview at /{$targetUsername}</h2>";
