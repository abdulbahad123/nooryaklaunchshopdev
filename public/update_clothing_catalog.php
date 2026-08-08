<?php
set_time_limit(300);

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "<h2>LaunchShop: Clothing Catalog Update Script</h2>";

// Helper functions for safe DB inserts
function safeInsert(string $table, array $data) {
    $columns = Schema::getColumnListing($table);
    $filtered = array_intersect_key($data, array_flip($columns));
    return DB::table($table)->insertGetId($filtered);
}

function safeInsertNoId(string $table, array $data) {
    $columns = Schema::getColumnListing($table);
    $filtered = array_intersect_key($data, array_flip($columns));
    DB::table($table)->insert($filtered);
}

// 1. Resolve Target User ID
$targetUsername = 'clothing';
$targetUser = DB::table('users')->where('username', $targetUsername)->first();
if (!$targetUser) {
    die("<h3 style='color:red;'>Error: Target user '{$targetUsername}' not found.</h3>");
}
$uid = $targetUser->id;
echo "<p>Resolved User '{$targetUsername}' ID: <strong>{$uid}</strong></p>";

// 2. Resolve English and Arabic Language IDs
$langs = DB::table('user_languages')->where('user_id', $uid)->get();
$enLangId = null;
$arLangId = null;

foreach ($langs as $lang) {
    if ($lang->code === 'en') {
        $enLangId = $lang->id;
    } elseif ($lang->code === 'ar') {
        $arLangId = $lang->id;
    }
}

if (!$enLangId) {
    die("<h3 style='color:red;'>Error: English language record not found for user.</h3>");
}
echo "<p>Resolved Language IDs - English: <strong>{$enLangId}</strong> | Arabic: <strong>" . ($arLangId ?? 'None') . "</strong></p>";

// 3. Define the new categories
$categoriesData = [
    'dresses-gowns' => [
        'image' => 'cat_dresses.png',
        'en' => ['name' => 'Dresses & Gowns', 'slug' => 'dresses-gowns'],
        'ar' => ['name' => 'فساتين وعبايات', 'slug' => 'فساتين-وعبايات']
    ],
    'tops-shirts' => [
        'image' => 'cat_tops.png',
        'en' => ['name' => 'Tops & Shirts', 'slug' => 'tops-shirts'],
        'ar' => ['name' => 'قمصان وتيشرتات', 'slug' => 'قمصان-وتيشرتات']
    ],
    'outerwear' => [
        'image' => 'cat_outerwear.png',
        'en' => ['name' => 'Outerwear', 'slug' => 'outerwear'],
        'ar' => ['name' => 'ملابس خارجية', 'slug' => 'ملابس-خارجية']
    ],
    'ethnic-wear' => [
        'image' => 'cat_ethnic.png',
        'en' => ['name' => 'Ethnic Wear', 'slug' => 'ethnic-wear'],
        'ar' => ['name' => 'ملابس تقليدية', 'slug' => 'ملابس-تقليدية']
    ],
    'summer-wear' => [
        'image' => 'cat_summer.png',
        'en' => ['name' => 'Summer Wear', 'slug' => 'summer-wear'],
        'ar' => ['name' => 'ملابس صيفية', 'slug' => 'ملابس-صيفية']
    ]
];

// 4. Define the 8 premium clothing products
// Added flash sale properties to the first 3 products
$productsData = [
    [
        'category_slug' => 'dresses-gowns',
        'image_key' => 'maxi_dress',
        'price' => 49.00,
        'flash' => 1,
        'flash_amount' => 30, // 30% off
        'start_date' => '2026-01-01',
        'start_time' => '00:00',
        'end_date' => '2030-12-31',
        'end_time' => '23:59',
        'en' => [
            'title' => 'Elegant Floral Maxi Dress',
            'slug' => 'elegant-floral-maxi-dress',
            'summary' => 'A beautiful, premium, elegant floral maxi dress in pastel colors.',
            'description' => 'This elegant floral maxi dress is crafted from lightweight, breathable fabric, making it perfect for summer garden parties, beachside strolls, or formal evening gatherings. Designed with a flattering A-line silhouette and delicate straps, it offers both exceptional style and all-day comfort.'
        ],
        'ar' => [
            'title' => 'فستان ماكسي أنيق بزهور',
            'slug' => 'فستان-ماكسي-أنيق-بزهور',
            'summary' => 'فستان ماكسي طويل أنيق ومميز بنقشات الزهور وألوان الباستيل الناعمة.',
            'description' => 'صُنع هذا الفستان الماكسي الأنيق من قماش خفيف الوزن يسمح بمرور الهواء، مما يجعله مثاليًا لحفلات الصيف أو النزهات الشاطئية أو المناسبات المسائية. يتميز بقصة مريحة وأحزمة كتف رقيقة تمنحك الأناقة والراحة طوال اليوم.'
        ]
    ],
    [
        'category_slug' => 'dresses-gowns',
        'image_key' => 'evening_gown',
        'price' => 89.00,
        'flash' => 1,
        'flash_amount' => 25, // 25% off
        'start_date' => '2026-01-01',
        'start_time' => '00:00',
        'end_date' => '2030-12-31',
        'end_time' => '23:59',
        'en' => [
            'title' => 'Classic Emerald Satin Evening Gown',
            'slug' => 'classic-emerald-satin-evening-gown',
            'summary' => 'An elegant emerald green satin evening gown with a luxurious finish.',
            'description' => 'Exude elegance at your next formal event with this classic emerald green evening gown. Tailored from premium high-sheen satin, this gown features a subtle wrap design, a draped neckline, and a structured fit that flatters every silhouette.'
        ],
        'ar' => [
            'title' => 'فستان سهرة ساتان زمردي كلاسيكي',
            'slug' => 'فستان-سهرة-ساتان-زمردي-كلاسيكي',
            'summary' => 'فستان سهرة ساتان أنيق بلون أخضر زمردي مع لمسة نهائية فاخرة.',
            'description' => 'تألقي بالأناقة في مناسبتك الرسمية القادمة مع فستان السهرة الزمردي الكلاسيكي المصنوع من الساتان الفاخر ذو اللمعان العالي، والذي يتميز بتصميم ملفوف وفتحة رقبة انسيابية تضفي جاذبية خالدة.'
        ]
    ],
    [
        'category_slug' => 'tops-shirts',
        'image_key' => 'linen_shirt',
        'price' => 35.00,
        'flash' => 1,
        'flash_amount' => 20, // 20% off
        'start_date' => '2026-01-01',
        'start_time' => '00:00',
        'end_date' => '2030-12-31',
        'end_time' => '23:59',
        'en' => [
            'title' => 'Premium Linen Button-Down Shirt',
            'slug' => 'premium-linen-button-down-shirt',
            'summary' => 'A classic white linen button-down shirt for men with a relaxed fit.',
            'description' => 'Crafted from 100% premium Belgian linen, this button-down shirt offers ultimate breathability and classic style for warm weather. Designed with a clean classic collar and buttoned cuffs, it easily transitions from smart casual to relaxed weekend wear.'
        ],
        'ar' => [
            'title' => 'قميص كتان فاخر بأزرار',
            'slug' => 'قميص-كتان-فاخر-بأزرار',
            'summary' => 'قميص كتان أبيض كلاسيكي للرجال بقصة مريحة وجذابة.',
            'description' => 'صُنع هذا القميص من الكتان البلجيكي الفاخر 100٪، مما يوفر تهوية ممتازة وأناقة كلاسيكية للطقس الدافئ. صُمم بياقة كلاسيكية نظيفة وأكمام بأزرار، ويمكن ارتداؤه بسهولة للعمل أو عطلة نهاية الأسبوع.'
        ]
    ],
    [
        'category_slug' => 'tops-shirts',
        'image_key' => 'crewneck_tshirt',
        'price' => 19.00,
        'en' => [
            'title' => 'Classic Cotton Crewneck T-Shirt',
            'slug' => 'classic-cotton-crewneck-t-shirt',
            'summary' => 'A premium heavyweight cotton crewneck t-shirt in grey melange.',
            'description' => 'An everyday wardrobe essential, this classic crewneck t-shirt is built from heavyweight combed organic cotton. It features a reinforced ribbed collar, double-stitched hems, and a modern tailored fit that holds its shape wash after wash.'
        ],
        'ar' => [
            'title' => 'تي شيرت قطني كلاسيكي بياقة دائرية',
            'slug' => 'تي-شيرت-قطني-كلاسيكي-بياقة-دائرية',
            'summary' => 'تي شيرت قطني فاخر بياقة دائرية ولون رمادي ميلانج عصري.',
            'description' => 'هذا التي شيرت الكلاسيكي بياقة دائرية مصنوع من القطن العضوي الممشط الثقيل، ويتميز بياقة مقواة وحواف مزدوجة الخياطة وقصة حديثة مريحة تحافظ على شكلها بعد الغسيل المتكرر.'
        ]
    ],
    [
        'category_slug' => 'outerwear',
        'image_key' => 'knit_cardigan',
        'price' => 59.00,
        'en' => [
            'title' => 'Vintage Knit Cardigan Sweater',
            'slug' => 'vintage-knit-cardigan-sweater',
            'summary' => 'A cozy knit cardigan sweater in soft cream color with premium buttons.',
            'description' => 'Wrap yourself in cozy warmth with this vintage-inspired knit cardigan. Made from a premium soft wool blend, it features an elegant cable-knit pattern, tortoiseshell button details, and front pockets. An ideal layering piece for chilly days.'
        ],
        'ar' => [
            'title' => 'كارديجان صوف كلاسيكي محبوك',
            'slug' => 'كارديجان-صوف-كلاسيكي-محبوك',
            'summary' => 'كارديجان صوف دافئ ومريح بلون كريمي ناعم مع أزرار فاخرة.',
            'description' => 'احصلي على الدفء والراحة مع هذا الكارديجان الصوفي الكلاسيكي المصنوع من مزيج صوف ناعم فاخر، ويتميز بنقشة محبوكة أنيقة وأزرار صدفية وجيوب أمامية.'
        ]
    ],
    [
        'category_slug' => 'outerwear',
        'image_key' => 'denim_jacket',
        'price' => 65.00,
        'en' => [
            'title' => 'Classic Denim Jean Jacket',
            'slug' => 'classic-denim-jean-jacket',
            'summary' => 'A classic blue denim jacket with heavy-duty metal buttons.',
            'description' => 'This classic denim jacket is made from durable 100% cotton indigo denim that breaks in beautifully over time. Featuring button-flap chest pockets, welt hand pockets, and adjustable waist tabs, it is the ultimate layering essential.'
        ],
        'ar' => [
            'title' => 'سترة جينز كلاسيكية',
            'slug' => 'سترة-جينز-كلاسيكية',
            'summary' => 'سترة جينز زرقاء كلاسيكية مع أزرار معدنية متينة.',
            'description' => 'صُنعت سترة الجينز الكلاسيكية هذه من القطن المتين بنسبة 100٪ باللون النيلي، وتتميز بجيوب صدر ذات أزرار وجيوب جانبية مريحة لإطلالة عصرية يومية.'
        ]
    ],
    [
        'category_slug' => 'ethnic-wear',
        'image_key' => 'cotton_kurta',
        'price' => 45.00,
        'en' => [
            'title' => 'Elegant Embroidered Cotton Kurta',
            'slug' => 'elegant-embroidered-cotton-kurta',
            'summary' => 'An elegant embroidered pastel blue cotton kurta for women.',
            'description' => 'Celebrate traditional elegance with this beautifully crafted cotton kurta. Styled with intricate hand-embroidery along the neckline and cuffs, this pastel blue tunic is tailored from breathable slub cotton, offering a relaxed and airy fit.'
        ],
        'ar' => [
            'title' => 'قميص كورتا قطني مطرز أنيق',
            'slug' => 'قميص-كورتا-قطني-مطرز-أنيق',
            'summary' => 'قميص كورتا نسائي أنيق مطرز بلون أزرق باستيل ناعم ومريح.',
            'description' => 'احتفلي بالأناقة التقليدية مع هذا الكورتا القطني المصمم بتطريز يدوي معقد على طول الياقة والأكمام من القطن المريح عالي الجودة.'
        ]
    ],
    [
        'category_slug' => 'summer-wear',
        'image_key' => 'silk_saree',
        'price' => 120.00,
        'en' => [
            'title' => 'Luxurious Royal Silk Saree',
            'slug' => 'luxurious-royal-silk-saree',
            'summary' => 'A luxurious crimson red silk saree with gold zari embroidery.',
            'description' => 'Add regal splendor to your wardrobe with this gorgeous royal silk saree. Crafted from authentic pure Banarasi silk, it features stunning gold zari border work and floral motifs, offering an elegant drape for weddings and festive occasions.'
        ],
        'ar' => [
            'title' => 'ساري حرير ملكي فاخر',
            'slug' => 'ساري-حرير-ملكي-فاخر',
            'summary' => 'ساري حرير فاخر بلون أحمر قرمزي وتطريز زاري ذهبي ملكي.',
            'description' => 'أضيفي فخامة ملكية إلى خزانة ملابسك مع هذا الساري الحريري الفاخر المصنوع من الحرير الطبيعي، ويتميز بتطريزات ذهبية مذهلة على الحواف، وهو مثالي لحفلات الزفاف والمناسبات الخاصة.'
        ]
    ]
];

// 5. Copy generated images from source to public assets
echo "<h3>Step 1: Copying new product and category images...</h3>";
$imageSourceDir = 'C:/Users/samir/.gemini/antigravity-ide/brain/c2ac7673-7e34-429a-87db-b2de09e9c2b3/';
$imageDestThumbnailDir = __DIR__ . '/assets/front/img/user/items/thumbnail/';
$imageDestSliderDir = __DIR__ . '/assets/front/img/user/items/slider-images/';
$imageDestCategoryDir = __DIR__ . '/assets/front/img/user/items/categories/';

@mkdir($imageDestThumbnailDir, 0775, true);
@mkdir($imageDestSliderDir, 0775, true);
@mkdir($imageDestCategoryDir, 0775, true);

// Map image keys to their specific generated filenames in the brain folder
$imageFileMap = [
    // Product images
    'thumbnail/maxi_dress.png' => 'maxi_dress_1780407302539.png',
    'slider-images/maxi_dress.png' => 'maxi_dress_1780407302539.png',
    'thumbnail/evening_gown.png' => 'evening_gown_1780407321435.png',
    'slider-images/evening_gown.png' => 'evening_gown_1780407321435.png',
    'thumbnail/linen_shirt.png' => 'linen_shirt_1780407337923.png',
    'slider-images/linen_shirt.png' => 'linen_shirt_1780407337923.png',
    'thumbnail/crewneck_tshirt.png' => 'crewneck_tshirt_1780407354623.png',
    'slider-images/crewneck_tshirt.png' => 'crewneck_tshirt_1780407354623.png',
    'thumbnail/knit_cardigan.png' => 'knit_cardigan_1780407371926.png',
    'slider-images/knit_cardigan.png' => 'knit_cardigan_1780407371926.png',
    'thumbnail/denim_jacket.png' => 'denim_jacket_1780407392158.png',
    'slider-images/denim_jacket.png' => 'denim_jacket_1780407392158.png',
    'thumbnail/cotton_kurta.png' => 'cotton_kurta_1780407407788.png',
    'slider-images/cotton_kurta.png' => 'cotton_kurta_1780407407788.png',
    'thumbnail/silk_saree.png' => 'silk_saree_1780407422547.png',
    'slider-images/silk_saree.png' => 'silk_saree_1780407422547.png',
    
    // Category images
    'categories/cat_dresses.png' => 'cat_dresses_1780408515101.png',
    'categories/cat_tops.png' => 'cat_tops_1780408534896.png',
    'categories/cat_outerwear.png' => 'cat_outerwear_1780408552618.png',
    'categories/cat_ethnic.png' => 'cat_ethnic_1780408567761.png',
    'categories/cat_summer.png' => 'cat_summer_1780408587210.png'
];

foreach ($imageFileMap as $relPath => $filename) {
    $srcPath = $imageSourceDir . $filename;
    $dstPath = __DIR__ . '/assets/front/img/user/items/' . $relPath;

    if (file_exists($srcPath)) {
        copy($srcPath, $dstPath);
        echo "Successfully copied image: <strong>{$relPath}</strong><br>";
    } else {
        echo "<span style='color:orange;'>Warning: Source image {$srcPath} not found. Setting DB name for {$relPath} anyway.</span><br>";
    }
}

// 6. Database Cleanup
echo "<h3>Step 2: Cleaning up existing catalog items for 'clothing'...</h3>";
$oldItemIds = DB::table('user_items')->where('user_id', $uid)->pluck('id')->toArray();
if (!empty($oldItemIds)) {
    DB::table('user_item_images')->whereIn('item_id', $oldItemIds)->delete();
    DB::table('user_item_contents')->whereIn('item_id', $oldItemIds)->delete();
    DB::table('product_variations')->whereIn('item_id', $oldItemIds)->delete();
    DB::table('product_variation_contents')->whereIn('item_id', $oldItemIds)->delete();
    DB::table('product_variant_options')->whereIn('item_id', $oldItemIds)->delete();
    DB::table('product_variant_option_contents')->whereIn('item_id', $oldItemIds)->delete();
    DB::table('user_items')->where('user_id', $uid)->delete();
}
DB::table('user_item_categories')->where('user_id', $uid)->delete();
DB::table('user_item_sub_categories')->where('user_id', $uid)->delete();
echo "Cleanup completed.<br>";

// 7. Seed new categories
echo "<h3>Step 3: Seeding new categories with images...</h3>";
$categoryMap = []; // Maps slug -> target Category ID for English
$categoryArabMap = []; // Maps slug -> target Category ID for Arabic

foreach ($categoriesData as $slugKey => $data) {
    $uniqueId = uniqid();
    
    // Insert English Category
    $enCatId = safeInsert('user_item_categories', [
        'user_id' => $uid,
        'language_id' => $enLangId,
        'name' => $data['en']['name'],
        'slug' => $data['en']['slug'],
        'image' => $data['image'],
        'category_background_image' => $data['image'],
        'status' => 1,
        'is_featured' => 1,
        'unique_id' => $uniqueId,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    $categoryMap[$slugKey] = $enCatId;
    echo "Added category (EN): <strong>{$data['en']['name']}</strong> (ID: {$enCatId})<br>";

    // Insert Arabic Category (if exists)
    if ($arLangId) {
        $arCatId = safeInsert('user_item_categories', [
            'user_id' => $uid,
            'language_id' => $arLangId,
            'name' => $data['ar']['name'],
            'slug' => $data['ar']['slug'],
            'image' => $data['image'],
            'category_background_image' => $data['image'],
            'status' => 1,
            'is_featured' => 1,
            'unique_id' => $uniqueId,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $categoryArabMap[$slugKey] = $arCatId;
        echo "Added category (AR): <strong>{$data['ar']['name']}</strong> (ID: {$arCatId})<br>";
    }
}

// 8. Seed new products
echo "<h3>Step 4: Seeding new products...</h3>";
$defaultCurrency = DB::table('user_currencies')->where('user_id', $uid)->where('is_default', 1)->first();
$currencyId = $defaultCurrency ? $defaultCurrency->id : null;

foreach ($productsData as $product) {
    $slugKey = $product['category_slug'];
    $imageName = $product['image_key'] . '.png';

    // Build user_items data array dynamically with flash sale options
    $itemInsertData = [
        'user_id' => $uid,
        'currency_id' => $currencyId,
        'thumbnail' => $imageName,
        'current_price' => $product['price'],
        'previous_price' => $product['price'] + 15.00,
        'status' => 1,
        'is_featured' => 1,
        'type' => 'physical',
        'stock' => 100,
        'created_at' => now(),
        'updated_at' => now()
    ];

    if (isset($product['flash'])) {
        $itemInsertData['flash'] = $product['flash'];
        $itemInsertData['flash_amount'] = $product['flash_amount'];
        $itemInsertData['start_date'] = $product['start_date'];
        $itemInsertData['start_time'] = $product['start_time'];
        $itemInsertData['end_date'] = $product['end_date'];
        $itemInsertData['end_time'] = $product['end_time'];
    }

    // Insert parent item row
    $itemId = safeInsert('user_items', $itemInsertData);

    // Insert English Content
    $enCatId = $categoryMap[$slugKey] ?? null;
    safeInsertNoId('user_item_contents', [
        'user_id' => $uid,
        'item_id' => $itemId,
        'language_id' => $enLangId,
        'category_id' => $enCatId,
        'title' => $product['en']['title'],
        'slug' => $product['en']['slug'],
        'summary' => $product['en']['summary'],
        'description' => $product['en']['description'],
        'created_at' => now(),
        'updated_at' => now()
    ]);

    // Insert English slider images (3 images per product)
    safeInsertNoId('user_item_images', [
        'item_id' => $itemId,
        'image' => $imageName
    ]);
    safeInsertNoId('user_item_images', [
        'item_id' => $itemId,
        'image' => $product['image_key'] . '_2.png'
    ]);
    safeInsertNoId('user_item_images', [
        'item_id' => $itemId,
        'image' => $product['image_key'] . '_3.png'
    ]);
    
    echo "Added product (EN): <strong>{$product['en']['title']}</strong> (ID: {$itemId})<br>";

    // Insert Arabic Content (if language is active)
    if ($arLangId) {
        $arCatId = $categoryArabMap[$slugKey] ?? null;
        safeInsertNoId('user_item_contents', [
            'user_id' => $uid,
            'item_id' => $itemId,
            'language_id' => $arLangId,
            'category_id' => $arCatId,
            'title' => $product['ar']['title'],
            'slug' => $product['ar']['slug'],
            'summary' => $product['ar']['summary'],
            'description' => $product['ar']['description'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "Added product translation (AR): <strong>{$product['ar']['title']}</strong><br>";
    }
}

// 8b. Update User Contact Info
echo "<h3>Step 4.5: Updating contact information...</h3>";
$updatedContacts = DB::table('user_contacts')->where('user_id', $uid)->update([
    'contact_numbers' => '72007 70351',
    'contact_mails' => 'nooryaktechnologies@gmail.com',
    'contact_addresses' => 'Triplicane, Chennai, IND'
]);
echo "Updated {$updatedContacts} contact records.<br>";

// 9. Clear Laravel cache
echo "<h3>Step 5: Clearing cache...</h3>";
\Illuminate\Support\Facades\Artisan::call('view:clear');
\Illuminate\Support\Facades\Artisan::call('cache:clear');
echo "Cache cleared successfully!<br>";

echo "<h2 style='color:green;'>SUCCESS! All categories, products, and homepage/instagram images have been updated!</h2>";
