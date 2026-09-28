I need you to perform a complete, production-safe fix for the product image slider and product visibility issue in my Laravel application.

IMPORTANT CONTEXT:

I have a large existing image directory:

public/assets/front/img/user/items/slider-images/

This directory already contains approximately 7,206 product slider images.

These images are already generated/stored by the application.

DO NOT:
- delete these images
- regenerate these images
- rename these images
- move these images
- recreate the slider-images directory
- replace existing database image records unnecessarily
- change the image storage architecture
- change the existing image naming system

The problem is that some existing products/images are not displayed correctly even though the image files already exist.

The goal is to correctly connect the EXISTING database records to the EXISTING image files.

==================================================
CURRENT MODELS
==================================================

UserItem model:

class UserItem extends Model
{
    protected $guarded = [];
    protected $table = 'user_items';

    public function itemContents()
    {
        return $this->hasMany(UserItemContent::class, 'item_id', 'id');
    }

    public function sliders()
    {
        return $this->hasMany(UserItemImage::class, 'item_id', 'id');
    }

    public function getThumbnailUrlAttribute()
    {
        return user_item_image_url($this->thumbnail, 'thumbnail');
    }

    public function variations()
    {
        return $this->hasMany(UserItemVariation::class, 'item_id');
    }

    public function currency()
    {
        return $this->belongsTo(UserCurrency::class);
    }
}

UserItemImage model:

class UserItemImage extends Model
{
    protected $table = 'user_item_images';

    protected $guarded = [];

    public function item()
    {
        return $this->belongsTo(UserItem::class, 'item_id', 'id');
    }

    public function getImageUrlAttribute()
    {
        return user_item_image_url($this->image, 'slider');
    }
}

These relationships appear conceptually correct.

DO NOT change the foreign key relationship unless inspection of the actual database schema proves it is wrong.

==================================================
VERY IMPORTANT ROOT-CAUSE CHECK #1
==================================================

Inspect ShopController carefully.

There is a query similar to:

UserItem::join(
    'user_item_contents',
    'user_items.id',
    '=',
    'user_item_contents.item_id'
)
->leftJoin(...)
...
->select(
    'user_items.*',
    'user_item_contents.*',
    'user_item_categories.*',
    ...
)

THIS MUST BE FIXED.

Do NOT select:

'user_items.*',
'user_item_contents.*'

together when hydrating a UserItem model.

The duplicate `id` column can cause Eloquent's UserItem model to contain the wrong ID.

The critical rule is:

$userItem->id MUST ALWAYS be the ID from:

user_items.id

It must NEVER become:

user_item_contents.id

because the slider relationship depends on:

user_items.id
    ↓
user_item_images.item_id

For example:

user_items:

id = 25

user_item_contents:

id = 101
item_id = 25

The UserItem model MUST contain:

$item->id = 25

NOT:

$item->id = 101

Use explicit column selection.

For example:

->select([
    'user_items.*',

    'user_item_contents.id as content_id',
    'user_item_contents.item_id as content_item_id',
    'user_item_contents.language_id as content_language_id',
    'user_item_contents.title',
    'user_item_contents.slug as product_slug',
    'user_item_contents.summary',
    'user_item_contents.description',
    'user_item_contents.category_id',
    'user_item_contents.subcategory_id',

    'user_item_categories.name as category_name',
    'user_item_categories.slug as category_slug',
])

Add any additional content fields required by the existing Blade templates, but NEVER allow content.id to overwrite user_items.id.

Apply this fix to EVERY query in ShopController that hydrates UserItem and currently selects both:

user_items.*
user_item_contents.*

This includes the fallback query.

Do not only fix the first query.

==================================================
VERY IMPORTANT ROOT-CAUSE CHECK #2
==================================================

Inspect the productDetails() method.

The product detail page must ultimately load:

UserItemContent
    ↓
item_id
    ↓
UserItem
    ↓
sliders
    ↓
UserItemImage

Make sure the final UserItem is the correct one.

Prefer eager loading:

UserItemContent::with([
    'item',
    'item.sliders',
    'variations'
])

The final product must have:

$product->item->id

equal to:

user_items.id

and NOT:

user_item_contents.id.

Do not accidentally replace the correct product with a different language/content row.

Preserve the existing:
- language logic
- variations
- reviews
- category
- related products
- pricing
- flash sales
- stock
- wishlist
- cart
- SEO
- existing theme behavior

==================================================
VERY IMPORTANT ROOT-CAUSE CHECK #3
==================================================

Inspect the actual `user_item_images` table data.

For a product:

user_items.id = 25

the image query MUST effectively be:

SELECT *
FROM user_item_images
WHERE item_id = 25;

Then every returned `image` value must correspond to an existing file in:

public/assets/front/img/user/items/slider-images/

Example:

user_items:

id = 25

user_item_images:

id    item_id    image
501   25         abc123.jpg
502   25         def456.jpg
503   25         xyz789.jpg

Physical files:

public/assets/front/img/user/items/slider-images/abc123.jpg
public/assets/front/img/user/items/slider-images/def456.jpg
public/assets/front/img/user/items/slider-images/xyz789.jpg

The application must display all three.

DO NOT assume the filename is the product ID.

The `image` database field is the image filename/path reference.

==================================================
VERY IMPORTANT ROOT-CAUSE CHECK #4
==================================================

Inspect the existing `user_item_image_url()` helper.

DO NOT replace it with hardcoded URLs unless absolutely necessary.

The existing helper is responsible for resolving the existing image files.

Verify that:

user_item_image_url($image, 'slider')

correctly resolves files from:

public/assets/front/img/user/items/slider-images/

Also verify:
- filename is not accidentally modified
- URL encoding is correct
- uppercase/lowercase behavior is not causing a mismatch
- `.jpg`, `.jpeg`, `.png`, `.webp` etc. are handled correctly
- missing files get the existing fallback
- valid existing files return the correct public URL

Do not change the image naming convention.

==================================================
VERY IMPORTANT ROOT-CAUSE CHECK #5
==================================================

Inspect the product detail Blade.

The slider currently builds a `$slidesList`.

The desired behavior is:

1. Get the product thumbnail.
2. Get ALL `$product->item->sliders`.
3. Ignore empty image values.
4. Ignore `noimage.jpg`.
5. Convert each database filename using:
   user_item_image_url($image, 'slider')
6. Remove duplicate URLs.
7. Keep the existing thumbnail as the first image where appropriate.
8. If no valid image exists, use the existing placeholder.
9. Use the SAME `$slidesList` for:
   - thumbnail slider
   - main product slider
   - zoom image

Do NOT query the database repeatedly from inside Blade if `item.sliders` is already eager-loaded.

Keep the existing slider HTML/classes/JavaScript compatibility.

Do not redesign the slider.

==================================================
VERY IMPORTANT PRODUCT VISIBILITY ISSUE
==================================================

Some products are also not appearing in the shop.

Inspect the ShopController query.

It currently uses an INNER JOIN similar to:

->join(
    'user_item_contents',
    'user_items.id',
    '=',
    'user_item_contents.item_id'
)

and:

->where('user_item_contents.language_id', $uLang)

Determine whether a valid product is being excluded because it does not have a matching current-language content row.

Do NOT blindly change this to a LEFT JOIN.

First inspect the existing language/content architecture.

The correct behavior should be:

- active products appear according to the application's intended language behavior
- current-language content is preferred
- existing fallback behavior should continue to work if the application supports it
- products must not be duplicated
- category filtering must continue working
- subcategory filtering must continue working
- keyword search must continue working
- price filtering must continue working
- flash-sale filtering must continue working
- sorting must continue working
- pagination must continue working

If a language fallback is required, implement it without creating duplicate product rows.

==================================================
IMAGE DIRECTORY REQUIREMENT
==================================================

The existing physical directory is:

public/assets/front/img/user/items/slider-images/

It contains thousands of existing images.

Treat this directory as the SOURCE OF TRUTH for physical files.

Do not delete or regenerate files.

Instead, audit the connection between:

DATABASE
    ↓
user_items.id
    ↓
user_item_images.item_id
    ↓
user_item_images.image
    ↓
user_item_image_url()
    ↓
public/assets/front/img/user/items/slider-images/{filename}
    ↓
browser
    ↓
product slider

==================================================
DO NOT CONFUSE THESE IDs
==================================================

There are multiple IDs in this system.

Example:

user_items.id = 25

user_item_contents.id = 101

user_item_contents.item_id = 25

user_item_images.id = 501

user_item_images.item_id = 25

The correct relationship is:

user_items.id = 25
        ↓
user_item_images.item_id = 25

NOT:

user_item_contents.id = 101
        ↓
user_item_images.item_id = 101

This distinction is critical.

==================================================
TEST WITH REAL DATABASE DATA
==================================================

Do not only inspect the code.

Choose several real products from the database:

1. Product with multiple slider images.
2. Product with one slider image.
3. Product with no slider images.
4. Product whose images exist physically.
5. Product currently missing from the shop.
6. Product currently showing an incomplete slider.

For each product verify:

user_items.id

user_item_contents.item_id

user_item_images.item_id

user_item_images.image

and verify that the physical file exists at:

public/assets/front/img/user/items/slider-images/{image}

Document any mismatch found.

==================================================
EXPECTED CUSTOMER EXPERIENCE
==================================================

After the fix, a normal customer should experience:

SHOP PAGE:

Every valid active product appears according to the existing filters and language rules.

PRODUCT PAGE:

When the customer opens a product:

- correct product opens
- correct product thumbnail appears
- all existing slider images for that product appear
- thumbnails work
- clicking a thumbnail changes the main image
- zoom uses the correct image
- no image from another product appears
- missing image does not break the entire slider
- products with one image still work
- products with many images show all valid images
- products without slider images fall back to thumbnail/placeholder

The customer should NOT need to know anything about IDs, filenames, database records, or storage.

==================================================
DO NOT BREAK EXISTING FEATURES
==================================================

After the fix verify:

- product listing
- product details
- product images
- image slider
- zoom
- categories
- subcategories
- search
- price filtering
- sorting
- pagination
- reviews
- ratings
- stock
- variations
- flash sale
- discounts
- currency
- cart
- wishlist
- related products
- SEO
- language switching

Do not modify unrelated code.

==================================================
CACHE / DEPLOYMENT
==================================================

After making the code changes, determine whether Laravel caches need clearing.

If appropriate, use the project's normal commands such as:

php artisan optimize:clear

Do not run destructive commands.

Do not modify or delete the existing image directory.

==================================================
FINAL VALIDATION
==================================================

Before saying the issue is fixed, verify the actual rendered HTML/image URLs.

For at least one product with multiple images, confirm:

$product->item->id

is the correct user_items.id.

Confirm:

$product->item->sliders->count()

matches the number of valid image records for that item.

Confirm every generated image URL points to an existing file.

Confirm the browser can load every image.

Confirm the thumbnail slider and main slider contain the same images.

Also verify a second product to make sure images are not being mixed between products.

==================================================
FINAL RESPONSE
==================================================

After completing the fix, report:

1. Exact root cause(s) found.
2. Files changed.
3. What was changed in each file.
4. Whether any database changes were made.
5. Whether any existing images were modified.
6. Whether any existing images were deleted.
7. How the image flow now works.
8. Tests performed and their results.
9. Any Laravel cache command that needs to be run.

MOST IMPORTANT:

DO NOT delete, regenerate, rename, or move the existing 7,206 slider images.

The images already exist.

Fix the DATABASE → MODEL → IMAGE FILENAME → IMAGE URL → BROWSER SLIDER connection.

Do not patch only the visible symptom.
Find and fix the actual root cause.