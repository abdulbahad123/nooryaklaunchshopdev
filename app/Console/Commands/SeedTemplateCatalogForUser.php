<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\User\AboutUs;
use App\Models\User\AboutUsFeatures;
use App\Models\User\BasicSetting as UserBasicSetting;
use App\Models\User\AdditionalSection;
use App\Models\User\AdditionalSectionContent;
use App\Models\User\Banner;
use App\Models\User\BasicExtende;
use App\Models\User\CallToAction;
use App\Models\User\CounterInformation;
use App\Models\User\CounterSection;
use App\Models\User\Faq;
use App\Models\User\HeroSlider;
use App\Models\User\HowitWorkSection;
use App\Models\User\UserContact;
use App\Models\User\UserCurrency;
use App\Models\User\UserFeature;
use App\Models\User\UserFooter;
use App\Models\User\UserHeader;
use App\Models\User\UserHeading;
use App\Models\User\UserItem;
use App\Models\User\UserItemCategory;
use App\Models\User\UserItemContent;
use App\Models\User\UserItemImage;
use App\Models\User\UserItemSubCategory;
use App\Models\User\Language as UserLanguage;
use App\Models\User\ProductHeroSlider;
use App\Models\User\ProductVariation;
use App\Models\User\ProductVariationContent;
use App\Models\User\ProductVariantOption;
use App\Models\User\ProductVariantOptionContent;
use App\Models\User\SEO;
use App\Models\User\StaticHeroSection;
use App\Models\User\Tab;
use App\Models\User\Testimonial;
use App\Models\User\UserSection;
use App\Models\User\UserShopSetting;
use App\Models\User\UserUlink;
use App\Models\User\UserMenu;
use App\Models\User\Blog as UserBlog;
use App\Models\User\BlogCategory as UserBlogCategory;
use App\Models\User\BlogContent as UserBlogContent;
use App\Models\VariantContent;
use App\Models\VariantOptionContent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedTemplateCatalogForUser extends Command
{
    protected $signature = 'template:seed-user
                            {user? : Target user id, username, email, or shop_name}
                            {--source= : Template source username}
                            {--list : List recent users to help find the right one}
                            {--force : Seed even if target already has categories or products}';

    protected $description = 'Seed full template storefront data for an existing user tenant';

    public function handle()
    {
        // --list flag: show recent users to help identify the right one
        if ($this->option('list')) {
            $users = \App\Models\User::orderBy('id', 'desc')->take(20)->get(['id', 'username', 'email', 'shop_name', 'status']);
            $this->table(['ID', 'Username', 'Email', 'Shop Name', 'Status'], $users->map(fn($u) => [
                $u->id, $u->username, $u->email, $u->shop_name, $u->status
            ]));
            return self::SUCCESS;
        }

        $targetArg = (string) $this->argument('user');
        if (empty($targetArg)) {
            $this->error('Please provide a user argument (id, username, email, or shop_name), or use --list to see all users.');
            return self::FAILURE;
        }
        $sourceOption = $this->option('source');
        $force = (bool) $this->option('force');

        $targetUser = $this->resolveUser($targetArg);
        if (empty($targetUser)) {
            $this->error('Target user not found. Use id, username, or email.');
            return self::FAILURE;
        }

        $themeSourceMap = [
            'electronics'  => 'electi',
            'electi'       => 'electi',
            'fashion'      => 'fashclo',
            'fashclo'      => 'fashclo',
            'furniture'    => 'furial',
            'furial'       => 'furial',
            'grocery'      => 'ecomgrocery',
            'grocery2'     => 'ecomgrocery',
            'ecomgrocery'  => 'ecomgrocery',
            'vegetables'   => 'ecomgrocery',
            'kids'         => 'kidsfa',
            'kidsfa'       => 'kidsfa',
            'manti'        => 'manti',
            'multipurpose' => 'manti',
            'pet'          => 'petrashop',
            'petrashop'    => 'petrashop',
            'skinflow'     => 'skinflow',
            'beauty'       => 'skinflow',
            'jewellery'    => 'jewellery',
            'clothing'     => 'clothing',
        ];

        $templateUser = null;

        // Respect an explicit source when provided; otherwise infer from the tenant theme.
        if (!empty($sourceOption)) {
            $cleanSource = strtolower(trim($sourceOption));
            $mappedSource = $themeSourceMap[$cleanSource] ?? $cleanSource;
            $templateUser = User::where('username', $mappedSource)
                ->orWhere('username', $cleanSource)
                ->orWhere('shop_name', 'like', "%{$cleanSource}%")
                ->first();
        }

        if (empty($templateUser)) {
            $theme = UserBasicSetting::where('user_id', $targetUser->id)->value('theme');

            if (!empty($theme)) {
                $cleanTheme = strtolower(trim($theme));
                $mappedThemeSource = $themeSourceMap[$cleanTheme] ?? $cleanTheme;
                $templateUser = User::where('username', $mappedThemeSource)
                    ->orWhere('username', $cleanTheme)
                    ->orWhere('shop_name', 'like', "%{$cleanTheme}%")
                    ->first();
            }
        }

        if (empty($templateUser)) {
            $templateUser = User::whereIn('username', ['ecomgrocery', 'manti', 'electi', 'fashclo', 'furial', 'kidsfa', 'petrashop', 'skinflow', 'jewellery', 'clothing'])
                ->orWhere('shop_name', 'like', '%Grocery%')
                ->orWhere('template_serial_number', '>', 0)
                ->first();
        }

        if (empty($templateUser)) {
            $this->error('Template source user not found.');
            return self::FAILURE;
        }

        if ((int) $templateUser->id === (int) $targetUser->id) {
            $this->error('Template user and target user cannot be the same.');
            return self::FAILURE;
        }

        $hasItems = UserItem::where('user_id', $targetUser->id)->exists();
        $hasCategories = UserItemCategory::where('user_id', $targetUser->id)->exists();

        if (!$force && ($hasItems || $hasCategories)) {
            $this->warn('Target user already has categories/products. Use --force to seed anyway.');
            return self::INVALID;
        }

        $defaultCurrencyId = UserCurrency::where('user_id', $targetUser->id)
            ->where('is_default', 1)
            ->value('id');

        if (empty($defaultCurrencyId)) {
            $defaultCurrencyId = UserCurrency::where('user_id', $targetUser->id)->value('id');
        }

        if (empty($defaultCurrencyId)) {
            $curr = UserCurrency::create([
                'text' => 'INR',
                'symbol' => '₹',
                'value' => '1',
                'is_default' => 1,
                'text_position' => 'left',
                'symbol_position' => 'left',
                'user_id' => $targetUser->id,
            ]);
            $defaultCurrencyId = $curr->id;
        }

        $targetEnLangs = UserLanguage::where('user_id', $targetUser->id)->where('code', 'en')->get();
        if ($targetEnLangs->count() > 1) {
            $firstEn = $targetEnLangs->first();
            UserLanguage::where('user_id', $targetUser->id)->where('code', 'en')->where('id', '!=', $firstEn->id)->delete();
        }

        $targetEnLang = UserLanguage::where('user_id', $targetUser->id)->where('code', 'en')->first();
        if ($targetEnLang) {
            UserLanguage::where('user_id', $targetUser->id)->where('id', '!=', $targetEnLang->id)->update(['is_default' => 0]);
            $targetEnLang->is_default = 1;
            $targetEnLang->save();
        }

        $languageMap = $this->buildLanguageMap($templateUser->id, $targetUser->id);

        $targetDefaultLangId = UserLanguage::where('user_id', $targetUser->id)->where('code', 'en')->value('id')
            ?? (UserLanguage::where('user_id', $targetUser->id)->where('is_default', 1)->value('id')
            ?? (UserLanguage::where('user_id', $targetUser->id)->value('id') ?? 1));

        $package = \App\Http\Helpers\UserPermissionHelper::currentPackagePermission($targetUser->id);
        if (empty($package)) {
            $package = \App\Http\Helpers\UserPermissionHelper::currPackageOrPending($targetUser->id);
        }
        if (empty($package)) {
            $latestMembership = \App\Models\Membership::where('user_id', $targetUser->id)->orderBy('id', 'DESC')->first();
            if ($latestMembership) {
                $package = \App\Models\Package::find($latestMembership->package_id);
            }
        }
        $categoriesLimit = (!empty($package) && is_numeric($package->categories_limit)) ? (int)$package->categories_limit : 999999;
        $subcategoriesLimit = (!empty($package) && is_numeric($package->subcategories_limit)) ? (int)$package->subcategories_limit : 999999;
        $productLimit = (!empty($package) && is_numeric($package->product_limit)) ? (int)$package->product_limit : 999999;

        DB::transaction(function () use ($templateUser, $targetUser, $defaultCurrencyId, $languageMap, $targetDefaultLangId, $categoriesLimit, $subcategoriesLimit, $productLimit) {
            // Delete target user's existing catalog assets/slider images first to prevent orphaned records or constraints
            if (\Illuminate\Support\Facades\Schema::hasTable('user_items')) {
                $targetItemIds = DB::table('user_items')->where('user_id', $targetUser->id)->pluck('id')->toArray();
                if (!empty($targetItemIds) && \Illuminate\Support\Facades\Schema::hasTable('user_item_images')) {
                    DB::table('user_item_images')->whereIn('item_id', $targetItemIds)->delete();
                }
            }

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
                'user_shipping_charges',
                'user_offline_gateways'
            ];

            foreach ($tablesToDelete as $table) {
                if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
                    DB::table($table)->where('user_id', $targetUser->id)->delete();
                }
            }

            $categoryMap = [];
            $subcategoryMap = [];
            $variantContentMap = [];
            $variantOptionMap = [];
            $variationMap = [];
            $variationOptionMap = [];
            $itemMap = [];

            $sourceCategories = UserItemCategory::where('user_id', $templateUser->id)->orderBy('id')->get();
            $categoriesCloned = 0;
            foreach ($sourceCategories->groupBy('unique_id') as $categoryGroup) {
                if ($categoriesCloned >= $categoriesLimit) {
                    break;
                }
                $newUniqueId = uniqid();
                foreach ($categoryGroup as $sourceCategory) {
                    $targetLangId = $this->resolveTargetLangId($sourceCategory->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $newCategory = $sourceCategory->replicate();
                    $newCategory->user_id = $targetUser->id;
                    $newCategory->unique_id = $newUniqueId;
                    $newCategory->language_id = $targetLangId;
                    $newCategory->status = 1;
                    $newCategory->image = $this->duplicateAsset($sourceCategory->image, 'assets/front/img/user/items/categories/');
                    $newCategory->category_background_image = $this->duplicateAsset($sourceCategory->category_background_image, 'assets/front/img/user/items/category_background/');
                    $this->safeSave($newCategory);

                    $categoryMap[$sourceCategory->id] = $newCategory->id;
                }
                $categoriesCloned++;
            }

            $sourceSubcategories = UserItemSubCategory::where('user_id', $templateUser->id)->orderBy('id')->get();
            $subcategoriesCloned = 0;
            foreach ($sourceSubcategories->groupBy('unique_id') as $subcategoryGroup) {
                if ($subcategoriesCloned >= $subcategoriesLimit) {
                    break;
                }
                $hasValidCategory = false;
                foreach ($subcategoryGroup as $sourceSubcategory) {
                    if (isset($categoryMap[$sourceSubcategory->category_id])) {
                        $hasValidCategory = true;
                        break;
                    }
                }
                if (!$hasValidCategory) {
                    continue;
                }

                $newUniqueId = uniqid();
                foreach ($subcategoryGroup as $sourceSubcategory) {
                    if (isset($categoryMap[$sourceSubcategory->category_id])) {
                        $targetLangId = $this->resolveTargetLangId($sourceSubcategory->language_id ?? null, $languageMap, $targetDefaultLangId);
                        if (is_null($targetLangId)) {
                            continue;
                        }
                        $newSubcategory = $sourceSubcategory->replicate();
                        $newSubcategory->user_id = $targetUser->id;
                        $newSubcategory->unique_id = $newUniqueId;
                        $newSubcategory->language_id = $targetLangId;
                        $newSubcategory->category_id = $categoryMap[$sourceSubcategory->category_id] ?? $sourceSubcategory->category_id;
                        $this->safeSave($newSubcategory);

                        $subcategoryMap[$sourceSubcategory->id] = $newSubcategory->id;
                    }
                }
                $subcategoriesCloned++;
            }

            $sourceVariantContents = VariantContent::where('user_id', $templateUser->id)->orderBy('id')->get();
            foreach ($sourceVariantContents as $sourceVariantContent) {
                $targetLangId = $this->resolveTargetLangId($sourceVariantContent->language_id ?? null, $languageMap, $targetDefaultLangId);
                if (is_null($targetLangId)) {
                    continue;
                }
                $newVariantContent = $sourceVariantContent->replicate();
                $newVariantContent->user_id = $targetUser->id;
                $newVariantContent->language_id = $targetLangId;
                $newVariantContent->category_id = $categoryMap[$sourceVariantContent->category_id] ?? $sourceVariantContent->category_id;
                $newVariantContent->sub_category_id = $subcategoryMap[$sourceVariantContent->sub_category_id] ?? $sourceVariantContent->sub_category_id;
                $this->safeSave($newVariantContent);

                $variantContentMap[$sourceVariantContent->id] = $newVariantContent->id;
            }

            $sourceVariantOptions = VariantOptionContent::where('user_id', $templateUser->id)->orderBy('id')->get();
            foreach ($sourceVariantOptions as $sourceVariantOption) {
                $targetLangId = $this->resolveTargetLangId($sourceVariantOption->language_id ?? null, $languageMap, $targetDefaultLangId);
                if (is_null($targetLangId)) {
                    continue;
                }
                $newVariantOption = $sourceVariantOption->replicate();
                $newVariantOption->user_id = $targetUser->id;
                $newVariantOption->language_id = $targetLangId;
                $this->safeSave($newVariantOption);

                $variantOptionMap[$sourceVariantOption->id] = $newVariantOption->id;
            }

            $sourceItems = UserItem::where('user_id', $templateUser->id)->orderBy('id')->get();
            $productsCloned = 0;
            foreach ($sourceItems as $sourceItem) {
                if ($productsCloned >= $productLimit) {
                    break;
                }
                $itemContents = UserItemContent::where('item_id', $sourceItem->id)->get();

                $newItem = $sourceItem->replicate();
                $newItem->user_id = $targetUser->id;
                $newItem->currency_id = $defaultCurrencyId;
                $newItem->thumbnail = $this->duplicateAsset($sourceItem->thumbnail, 'assets/front/img/user/items/thumbnail/');
                $newItem->download_file = $this->duplicateAsset($sourceItem->download_file, storage_path('digital_products/'), true);
                $this->safeSave($newItem);
                $itemMap[$sourceItem->id] = $newItem->id;

                $sourceImages = UserItemImage::where('item_id', $sourceItem->id)->get();
                if ($sourceImages->isEmpty()) {
                    try {
                        $mainDb = env('LAUNCHSHOP_MAIN_DB', env('DB_DATABASE', env('CPANEL_USER', 'nooryak') . '_launchshop'));
                        $sourceImages = DB::table("{$mainDb}.user_item_images")
                            ->where('item_id', $sourceItem->id)
                            ->get();
                    } catch (\Throwable $e) {
                        // ignore
                    }
                }

                foreach ($sourceImages as $sourceImage) {
                    UserItemImage::create([
                        'item_id' => $newItem->id,
                        'image' => $this->duplicateAsset($sourceImage->image, 'assets/front/img/user/items/slider-images/'),
                    ]);
                }

                foreach ($itemContents as $sourceContent) {
                    $targetLangId = $this->resolveTargetLangId($sourceContent->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $newContent = $sourceContent->replicate();
                    $newContent->user_id = $targetUser->id;
                    $newContent->item_id = $newItem->id;
                    $newContent->language_id = $targetLangId;
                    $newContent->category_id = $categoryMap[$sourceContent->category_id] ?? (!empty($categoryMap) ? reset($categoryMap) : null);
                    $newContent->subcategory_id = $subcategoryMap[$sourceContent->subcategory_id] ?? $sourceContent->subcategory_id;
                    $this->safeSave($newContent);
                }

                foreach (ProductVariation::where('item_id', $sourceItem->id)->get() as $sourceVariation) {
                    $newVariation = $sourceVariation->replicate();
                    $newVariation->user_id = $targetUser->id;
                    $newVariation->item_id = $newItem->id;
                    $this->safeSave($newVariation);

                    $variationMap[$sourceVariation->id] = $newVariation->id;
                }

                foreach (ProductVariationContent::where('item_id', $sourceItem->id)->get() as $sourceVariationContent) {
                    $targetLangId = $this->resolveTargetLangId($sourceVariationContent->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $newVariationContent = $sourceVariationContent->replicate();
                    $newVariationContent->user_id = $targetUser->id;
                    $newVariationContent->item_id = $newItem->id;
                    $newVariationContent->language_id = $targetLangId;
                    $newVariationContent->product_variation_id = $variationMap[$sourceVariationContent->product_variation_id] ?? $sourceVariationContent->product_variation_id;
                    $newVariationContent->variation_name = $variantContentMap[$sourceVariationContent->variation_name] ?? $sourceVariationContent->variation_name;
                    $this->safeSave($newVariationContent);
                }

                foreach (ProductVariantOption::where('item_id', $sourceItem->id)->get() as $sourceVariationOption) {
                    $newVariationOption = $sourceVariationOption->replicate();
                    $newVariationOption->user_id = $targetUser->id;
                    $newVariationOption->item_id = $newItem->id;
                    $newVariationOption->product_variation_id = $variationMap[$sourceVariationOption->product_variation_id] ?? $sourceVariationOption->product_variation_id;
                    $newVariationOption->save();

                    $variationOptionMap[$sourceVariationOption->id] = $newVariationOption->id;
                }

                foreach (ProductVariantOptionContent::where('item_id', $sourceItem->id)->get() as $sourceVariationOptionContent) {
                    $targetLangId = $this->resolveTargetLangId($sourceVariationOptionContent->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $newVariationOptionContent = $sourceVariationOptionContent->replicate();
                    $newVariationOptionContent->user_id = $targetUser->id;
                    $newVariationOptionContent->item_id = $newItem->id;
                    $newVariationOptionContent->language_id = $targetLangId;
                    $newVariationOptionContent->product_variant_option_id = $variationOptionMap[$sourceVariationOptionContent->product_variant_option_id] ?? $sourceVariationOptionContent->product_variant_option_id;
                    $newVariationOptionContent->option_name = $variantOptionMap[$sourceVariationOptionContent->option_name] ?? $sourceVariationOptionContent->option_name;
                    $newVariationOptionContent->save();
                }
                $productsCloned++;
            }

            $sourceShopSetting = UserShopSetting::where('user_id', $templateUser->id)->first();
            $existingShopSetting = UserShopSetting::where('user_id', $targetUser->id)->first();

            if (!empty($sourceShopSetting)) {
                // Update the existing shop setting created during registration rather than inserting a duplicate
                if ($existingShopSetting) {
                    $replicated = $sourceShopSetting->replicate();
                    $existingShopSetting->fill($replicated->toArray());
                    $existingShopSetting->user_id = $targetUser->id;
                    $this->safeSave($existingShopSetting);
                } else {
                    $newShopSetting = $sourceShopSetting->replicate();
                    $newShopSetting->user_id = $targetUser->id;
                    $this->safeSave($newShopSetting);
                }
            }

            // Guarantee a shop setting row always exists — the storefront crashes without it.
            $finalShopSetting = UserShopSetting::where('user_id', $targetUser->id)->first();
            if (empty($finalShopSetting)) {
                $fallback = new UserShopSetting();
                $fallback->user_id        = $targetUser->id;
                $fallback->catalog_mode   = 0;
                $fallback->item_rating_system = 1;
                $fallback->top_rated_count    = 5;
                $fallback->top_selling_count  = 5;
                $fallback->flash_item_count   = 8;
                $fallback->latest_item_count  = 8;
                $this->safeSave($fallback);
            }

            // Copy logo, favicon, breadcrumb, base_color from the template's basic settings
            // into the target user's existing user_basic_settings row.
            $templateBasicSetting = UserBasicSetting::where('user_id', $templateUser->id)->first();
            $targetBasicSetting   = UserBasicSetting::firstOrCreate(['user_id' => $targetUser->id]);
            if ($templateBasicSetting && $targetBasicSetting) {
                $targetBasicSetting->logo       = $this->duplicateAsset($templateBasicSetting->logo,      'assets/front/img/user/');
                $targetBasicSetting->favicon    = $this->duplicateAsset($templateBasicSetting->favicon,   'assets/front/img/user/');
                $targetBasicSetting->preloader  = $this->duplicateAsset($templateBasicSetting->preloader, 'assets/front/img/user/');
                $targetBasicSetting->breadcrumb = $this->duplicateAsset($templateBasicSetting->breadcrumb, 'assets/front/img/user/breadcrumb/');
                // NOTE: Do NOT copy the template's theme here — the correct theme was
                // already set by resolveLaunchshopTheme() during checkout. Overwriting it
                // here would replace the customer's chosen theme (e.g. electronics) with
                // the template source user's own theme (often grocery/vegetables).
                if (!empty($templateBasicSetting->base_color)) {
                    $targetBasicSetting->base_color = $templateBasicSetting->base_color;
                }

                // Copy section visibility settings
                $sectionFields = [
                    'featured_section', 'slider_section', 'video_banner_section', 'right_banner_section',
                    'category_section', 'categoryProduct_section', 'flash_section', 'cta_section_status',
                    'footer_section', 'copyright_section', 'tab_section', 'newsletter_section',
                    'latest_product_section', 'left_banner_section', 'banners_section', 'middle_banner_section',
                    'top_rated_section', 'top_selling_section', 'featuers_section', 'hero_section',
                    'about_info_section', 'about_features_section', 'about_counter_section', 'about_testimonial_section',
                    'bottom_middle_banner_section', 'top_middle_banner_section', 'top_right_banner_section',
                    'bottom_left_banner_section', 'middle_right_banner_section', 'bottom_right_banner_section'
                ];
                foreach ($sectionFields as $field) {
                    if (isset($templateBasicSetting->$field)) {
                        $targetBasicSetting->$field = $templateBasicSetting->$field;
                    }
                }

                $this->safeSave($targetBasicSetting);
            }

            foreach (UserSection::where('user_id', $templateUser->id)->get() as $sourceSection) {
                $targetLangId = $this->resolveTargetLangId($sourceSection->language_id ?? null, $languageMap, $targetDefaultLangId);
                if (is_null($targetLangId)) {
                    continue;
                }
                $newSection = $sourceSection->replicate();
                $newSection->user_id = $targetUser->id;
                $newSection->language_id = $targetLangId;
                $this->safeSave($newSection);
            }

            foreach (SEO::where('user_id', $templateUser->id)->get() as $sourceSeo) {
                $targetLangId = $this->resolveTargetLangId($sourceSeo->language_id ?? null, $languageMap, $targetDefaultLangId);
                if (is_null($targetLangId)) {
                    continue;
                }
                $newSeo = $sourceSeo->replicate();
                $newSeo->user_id = $targetUser->id;
                $newSeo->language_id = $targetLangId;
                $this->safeSave($newSeo);
            }

            foreach (BasicExtende::where('user_id', $templateUser->id)->get() as $sourceBasicExtende) {
                $targetLangId = $this->resolveTargetLangId($sourceBasicExtende->language_id ?? null, $languageMap, $targetDefaultLangId);
                if (is_null($targetLangId)) {
                    continue;
                }
                $newBasicExtende = $sourceBasicExtende->replicate();
                $newBasicExtende->user_id = $targetUser->id;
                $newBasicExtende->language_id = $targetLangId;
                if (isset($sourceBasicExtende->hero_section_background_image)) {
                    $newBasicExtende->hero_section_background_image = $this->duplicateAsset($sourceBasicExtende->hero_section_background_image, 'assets/front/img/hero_slider/');
                }
                $this->safeSave($newBasicExtende);
            }

            if ($this->tableExists((new HeroSlider)->getTable())) {
                foreach (HeroSlider::where('user_id', $templateUser->id)->get() as $sourceHeroSlider) {
                    $targetLangId = $this->resolveTargetLangId($sourceHeroSlider->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $newHeroSlider = $sourceHeroSlider->replicate();
                    $newHeroSlider->user_id = $targetUser->id;
                    $newHeroSlider->language_id = $targetLangId;
                    $newHeroSlider->img = $this->duplicateAsset($sourceHeroSlider->img, 'assets/front/img/hero_slider/');
                    $this->safeSave($newHeroSlider);
                }
            }

            if ($this->tableExists((new Banner)->getTable())) {
                foreach (Banner::where('user_id', $templateUser->id)->get() as $sourceBanner) {
                    $targetLangId = $this->resolveTargetLangId($sourceBanner->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $newBanner = $sourceBanner->replicate();
                    $newBanner->user_id = $targetUser->id;
                    $newBanner->language_id = $targetLangId;
                    $newBanner->banner_img = $this->duplicateAsset($sourceBanner->banner_img, 'assets/front/img/user/banners/');
                    $this->safeSave($newBanner);
                }
            }

            if ($this->tableExists((new Tab)->getTable())) {
                foreach (Tab::where('user_id', $templateUser->id)->get() as $sourceTab) {
                    $targetLangId = $this->resolveTargetLangId($sourceTab->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $newTab = $sourceTab->replicate();
                    $newTab->user_id = $targetUser->id;
                    $newTab->language_id = $targetLangId;
                    $newTab->image = $this->duplicateAsset($sourceTab->image, 'assets/front/img/user/items/tabs/');
                    if (isset($sourceTab->products)) {
                        $newTab->products = $this->mapSerializedIds($sourceTab->products, $itemMap);
                    }
                    $this->safeSave($newTab);
                }
            }

            if ($this->tableExists((new ProductHeroSlider)->getTable())) {
                foreach (ProductHeroSlider::where('user_id', $templateUser->id)->get() as $sourceProductHeroSlider) {
                    $newProductHeroSlider = $sourceProductHeroSlider->replicate();
                    $newProductHeroSlider->user_id = $targetUser->id;
                    $mappedProds = $this->mapSerializedIds($sourceProductHeroSlider->products, $itemMap);
                    $decodedMapped = json_decode((string) $mappedProds, true);
                    if (empty($decodedMapped) && !empty($itemMap)) {
                        $mappedProds = json_encode(array_values($itemMap));
                    }
                    $newProductHeroSlider->products = $mappedProds;
                    $this->safeSave($newProductHeroSlider);
                }

                $existingProductSlider = ProductHeroSlider::where('user_id', $targetUser->id)->first();
                if (empty($existingProductSlider) && !empty($itemMap)) {
                    $newProductHeroSlider = new ProductHeroSlider();
                    $newProductHeroSlider->user_id = $targetUser->id;
                    $newProductHeroSlider->products = json_encode(array_values($itemMap));
                    $this->safeSave($newProductHeroSlider);
                }
            }

            if ($this->tableExists((new HowitWorkSection)->getTable())) {
                foreach (HowitWorkSection::where('user_id', $templateUser->id)->get() as $sourceHowItWorkSection) {
                    $targetLangId = $this->resolveTargetLangId($sourceHowItWorkSection->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $newHowItWorkSection = $sourceHowItWorkSection->replicate();
                    $newHowItWorkSection->user_id = $targetUser->id;
                    $newHowItWorkSection->language_id = $targetLangId;
                    $this->safeSave($newHowItWorkSection);
                }
            }

            if ($this->tableExists((new CounterInformation)->getTable())) {
                foreach (CounterInformation::where('user_id', $templateUser->id)->get() as $sourceCounterInformation) {
                    $targetLangId = $this->resolveTargetLangId($sourceCounterInformation->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $newCounterInformation = $sourceCounterInformation->replicate();
                    $newCounterInformation->user_id = $targetUser->id;
                    $newCounterInformation->language_id = $targetLangId;
                    $this->safeSave($newCounterInformation);
                }
            }

            if ($this->tableExists((new CounterSection)->getTable())) {
                foreach (CounterSection::where('user_id', $templateUser->id)->get() as $sourceCounterSection) {
                    $targetLangId = $this->resolveTargetLangId($sourceCounterSection->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $newCounterSection = $sourceCounterSection->replicate();
                    $newCounterSection->user_id = $targetUser->id;
                    $newCounterSection->language_id = $targetLangId;
                    $newCounterSection->image = $this->duplicateAsset($sourceCounterSection->image, 'assets/front/img/user/about/');
                    $this->safeSave($newCounterSection);
                }
            }

            if ($this->tableExists((new CallToAction)->getTable())) {
                foreach (CallToAction::where('user_id', $templateUser->id)->get() as $sourceCallToAction) {
                    $targetLangId = $this->resolveTargetLangId($sourceCallToAction->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $newCallToAction = $sourceCallToAction->replicate();
                    $newCallToAction->user_id = $targetUser->id;
                    $newCallToAction->language_id = $targetLangId;
                    $newCallToAction->side_image = $this->duplicateAsset($sourceCallToAction->side_image, 'assets/front/img/cta/');
                    $newCallToAction->background_image = $this->duplicateAsset($sourceCallToAction->background_image, 'assets/front/img/cta/');
                    $this->safeSave($newCallToAction);
                }
            }

            if ($this->tableExists((new Testimonial)->getTable())) {
                foreach (Testimonial::where('user_id', $templateUser->id)->get() as $sourceTestimonial) {
                    $targetLangId = $this->resolveTargetLangId($sourceTestimonial->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $newTestimonial = $sourceTestimonial->replicate();
                    $newTestimonial->user_id = $targetUser->id;
                    $newTestimonial->language_id = $targetLangId;
                    $newTestimonial->image = $this->duplicateAsset($sourceTestimonial->image, 'assets/front/img/testimonials/');
                    $this->safeSave($newTestimonial);
                }
            }

            if ($this->tableExists((new UserFooter)->getTable())) {
                foreach (UserFooter::where('user_id', $templateUser->id)->get() as $sourceFooter) {
                    $langId = $this->resolveTargetLangId($sourceFooter->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($langId)) {
                        continue;
                    }
                    $existingFooter = UserFooter::where('user_id', $targetUser->id)
                        ->where('language_id', $langId)
                        ->first();
                    if ($existingFooter) {
                        $existingFooter->footer_text = $sourceFooter->footer_text;
                        $existingFooter->useful_links_title = $sourceFooter->useful_links_title;
                        $existingFooter->copyright_text = $sourceFooter->copyright_text;
                        $existingFooter->footer_logo = $this->duplicateAsset($sourceFooter->footer_logo, 'assets/front/img/footer/');
                        $existingFooter->background_image = $this->duplicateAsset($sourceFooter->background_image, 'assets/front/img/footer/');
                        $this->safeSave($existingFooter);
                    } else {
                        $newFooter = $sourceFooter->replicate();
                        $newFooter->user_id = $targetUser->id;
                        $newFooter->language_id = $langId;
                        $newFooter->footer_logo = $this->duplicateAsset($sourceFooter->footer_logo, 'assets/front/img/footer/');
                        $newFooter->background_image = $this->duplicateAsset($sourceFooter->background_image, 'assets/front/img/footer/');
                        $this->safeSave($newFooter);
                    }
                }
            }

            if ($this->tableExists((new UserUlink)->getTable())) {
                foreach (UserUlink::where('user_id', $templateUser->id)->get() as $sourceUlink) {
                    $targetLangId = $this->resolveTargetLangId($sourceUlink->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $newUlink = $sourceUlink->replicate();
                    $newUlink->user_id = $targetUser->id;
                    $newUlink->language_id = $targetLangId;
                    $this->safeSave($newUlink);
                }
            }

            if ($this->tableExists((new UserMenu)->getTable())) {
                foreach (UserMenu::where('user_id', $templateUser->id)->get() as $sourceMenu) {
                    $langId = $this->resolveTargetLangId($sourceMenu->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($langId)) {
                        continue;
                    }
                    $existingMenu = UserMenu::where('user_id', $targetUser->id)
                        ->where('language_id', $langId)
                        ->first();
                    if ($existingMenu) {
                        $existingMenu->menus = $sourceMenu->menus;
                        $this->safeSave($existingMenu);
                    } else {
                        $newMenu = $sourceMenu->replicate();
                        $newMenu->user_id = $targetUser->id;
                        $newMenu->language_id = $langId;
                        $this->safeSave($newMenu);
                    }
                }
            }

            // StaticHeroSection — used by pet and jewellery themes
            if ($this->tableExists((new StaticHeroSection)->getTable())) {
                foreach (StaticHeroSection::where('user_id', $templateUser->id)->get() as $source) {
                    $targetLangId = $this->resolveTargetLangId($source->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $new = $source->replicate();
                    $new->user_id = $targetUser->id;
                    $new->language_id = $targetLangId;
                    $new->background_image = $this->duplicateAsset($source->background_image, 'assets/front/img/hero_slider/');
                    $new->hero_image = $this->duplicateAsset($source->hero_image, 'assets/front/img/hero_slider/');
                    $this->safeSave($new);
                }
            }

            // AboutUs — used by the About page
            if ($this->tableExists((new AboutUs)->getTable())) {
                foreach (AboutUs::where('user_id', $templateUser->id)->get() as $source) {
                    $targetLangId = $this->resolveTargetLangId($source->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $new = $source->replicate();
                    $new->user_id = $targetUser->id;
                    $new->language_id = $targetLangId;
                    $new->image = $this->duplicateAsset($source->image, 'assets/front/img/user/about/');
                    $this->safeSave($new);
                }
            }

            // AboutUsFeatures — used by the About page
            if ($this->tableExists((new AboutUsFeatures)->getTable())) {
                foreach (AboutUsFeatures::where('user_id', $templateUser->id)->get() as $source) {
                    $targetLangId = $this->resolveTargetLangId($source->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $new = $source->replicate();
                    $new->user_id = $targetUser->id;
                    $new->language_id = $targetLangId;
                    $this->safeSave($new);
                }
            }

            // UserContact — used by the Contact page
            if ($this->tableExists((new UserContact)->getTable())) {
                foreach (UserContact::where('user_id', $templateUser->id)->get() as $source) {
                    $targetLangId = $this->resolveTargetLangId($source->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $new = $source->replicate();
                    $new->user_id = $targetUser->id;
                    $new->language_id = $targetLangId;
                    $this->safeSave($new);
                }
            }

            // Faq — used by the FAQ page
            if ($this->tableExists((new Faq)->getTable())) {
                foreach (Faq::where('user_id', $templateUser->id)->get() as $source) {
                    $targetLangId = $this->resolveTargetLangId($source->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $new = $source->replicate();
                    $new->user_id = $targetUser->id;
                    $new->language_id = $targetLangId;
                    $this->safeSave($new);
                }
            }

            // UserFeature — used by features section
            if ($this->tableExists((new UserFeature)->getTable())) {
                foreach (UserFeature::where('user_id', $templateUser->id)->get() as $source) {
                    $targetLangId = $this->resolveTargetLangId($source->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $new = $source->replicate();
                    $new->user_id = $targetUser->id;
                    $new->language_id = $targetLangId;
                    $this->safeSave($new);
                }
            }

            // UserHeader — used by header section
            if ($this->tableExists((new UserHeader)->getTable())) {
                foreach (UserHeader::where('user_id', $templateUser->id)->get() as $source) {
                    $targetLangId = $this->resolveTargetLangId($source->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $new = $source->replicate();
                    $new->user_id = $targetUser->id;
                    $new->language_id = $targetLangId;
                    $this->safeSave($new);
                }
            }

            // UserHeading — used for page headings
            if ($this->tableExists((new UserHeading)->getTable())) {
                foreach (UserHeading::where('user_id', $templateUser->id)->get() as $source) {
                    $targetLangId = $this->resolveTargetLangId($source->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $new = $source->replicate();
                    $new->user_id = $targetUser->id;
                    $new->language_id = $targetLangId;
                    $this->safeSave($new);
                }
            }

            $additionalSectionMap = [];
            if ($this->tableExists((new AdditionalSection)->getTable())) {
                foreach (AdditionalSection::where('user_id', $templateUser->id)->get() as $sourceAdditionalSection) {
                    $newAdditionalSection = $sourceAdditionalSection->replicate();
                    $newAdditionalSection->user_id = $targetUser->id;
                    $this->safeSave($newAdditionalSection);
                    $additionalSectionMap[$sourceAdditionalSection->id] = $newAdditionalSection->id;
                }
            }

            if (!empty($additionalSectionMap) && $this->tableExists((new AdditionalSectionContent)->getTable())) {
                foreach (AdditionalSectionContent::whereIn('addition_section_id', array_keys($additionalSectionMap))->get() as $sourceAdditionalSectionContent) {
                    $targetLangId = $this->resolveTargetLangId($sourceAdditionalSectionContent->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $newAdditionalSectionContent = $sourceAdditionalSectionContent->replicate();
                    $newAdditionalSectionContent->addition_section_id = $additionalSectionMap[$sourceAdditionalSectionContent->addition_section_id] ?? $sourceAdditionalSectionContent->addition_section_id;
                    $newAdditionalSectionContent->language_id = $targetLangId;
                    $this->safeSave($newAdditionalSectionContent);
                }
            }

            if ($this->tableExists((new \App\Models\User\UserShippingCharge)->getTable())) {
                foreach (\App\Models\User\UserShippingCharge::where('user_id', $templateUser->id)->get() as $sourceShipping) {
                    $targetLangId = $this->resolveTargetLangId($sourceShipping->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $newShipping = $sourceShipping->replicate();
                    $newShipping->user_id = $targetUser->id;
                    $newShipping->language_id = $targetLangId;
                    $newShipping->currency_id = $defaultCurrencyId;
                    $this->safeSave($newShipping);
                }
            }

            if ($this->tableExists('user_offline_gateways')) {
                foreach (\App\Models\User\UserOfflineGateway::where('user_id', $templateUser->id)->get() as $sourceGateway) {
                    $newGateway = $sourceGateway->replicate();
                    $newGateway->user_id = $targetUser->id;
                    $this->safeSave($newGateway);
                }
            }

            // ── Blog Categories ──────────────────────────────────────────────
            $blogCategoryMap = [];
            if ($this->tableExists('user_blog_categories')) {
                foreach (UserBlogCategory::where('user_id', $templateUser->id)->get() as $sourceBlogCat) {
                    $targetLangId = $this->resolveTargetLangId($sourceBlogCat->language_id ?? null, $languageMap, $targetDefaultLangId);
                    if (is_null($targetLangId)) {
                        continue;
                    }
                    $newBlogCat = $sourceBlogCat->replicate();
                    $newBlogCat->user_id = $targetUser->id;
                    $newBlogCat->language_id = $targetLangId;
                    $this->safeSave($newBlogCat);
                    $blogCategoryMap[$sourceBlogCat->id] = $newBlogCat->id;
                }
            }

            // ── Blogs and Blog Contents ──────────────────────────────────────
            if ($this->tableExists('user_blogs')) {
                $blogMap = [];
                foreach (UserBlog::where('user_id', $templateUser->id)->get() as $sourceBlog) {
                    $newBlog = $sourceBlog->replicate();
                    $newBlog->user_id = $targetUser->id;
                    $newBlog->blog_category_id = $blogCategoryMap[$sourceBlog->blog_category_id] ?? (!empty($blogCategoryMap) ? reset($blogCategoryMap) : null);
                    $newBlog->thumbnail = $this->duplicateAsset($sourceBlog->thumbnail, 'assets/front/img/user/blog/');
                    $this->safeSave($newBlog);
                    $blogMap[$sourceBlog->id] = $newBlog->id;
                }

                if (!empty($blogMap) && $this->tableExists('user_blog_contents')) {
                    foreach (UserBlogContent::whereIn('blog_id', array_keys($blogMap))->get() as $sourceBlogContent) {
                        $targetLangId = $this->resolveTargetLangId($sourceBlogContent->language_id ?? null, $languageMap, $targetDefaultLangId);
                        if (is_null($targetLangId)) {
                            continue;
                        }
                        $newBlogContent = $sourceBlogContent->replicate();
                        $newBlogContent->user_id = $targetUser->id;
                        $newBlogContent->blog_id = $blogMap[$sourceBlogContent->blog_id] ?? $sourceBlogContent->blog_id;
                        $newBlogContent->language_id = $targetLangId;
                        $this->safeSave($newBlogContent);
                    }
                }
            }
        });

        session()->forget('user_lang_' . $targetUser->username);

        $this->info('Template data seeded successfully for user: ' . $targetUser->username);
        return self::SUCCESS;
    }

    /**
     * Check whether a table exists in the live DB.
     * Results are cached per-request to avoid repeated INFORMATION_SCHEMA lookups.
     */
    private array $tableExistsCache = [];

    private function tableExists(string $table): bool
    {
        if (!isset($this->tableExistsCache[$table])) {
            $this->tableExistsCache[$table] = \Illuminate\Support\Facades\Schema::hasTable($table);
        }
        return $this->tableExistsCache[$table];
    }

    /**
     * Save a model after stripping any attributes whose columns don't exist
     * in the live DB. This makes seeding resilient to schema version mismatches.
     */
    private function safeSave(\Illuminate\Database\Eloquent\Model $model): void
    {
        $table = $model->getTable();
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing($table);
        $attrs = $model->getAttributes();
        $filtered = array_intersect_key($attrs, array_flip($columns));
        $model->setRawAttributes($filtered);
        $model->save();
    }

    private function resolveUser(string $value): ?User
    {
        if (ctype_digit($value)) {
            return User::find((int) $value);
        }

        return User::where('username', $value)
            ->orWhere('email', $value)
            ->orWhere('shop_name', $value)
            ->first();
    }

    private function buildLanguageMap(int $sourceUserId, int $targetUserId): array
    {
        $sourceLangs = UserLanguage::where('user_id', $sourceUserId)->get();
        $targetLangs = UserLanguage::where('user_id', $targetUserId)->get();

        $targetDefaultLang = $targetLangs->where('is_default', 1)->first() ?? $targetLangs->first();
        $targetDefaultLangId = $targetDefaultLang ? $targetDefaultLang->id : 1;

        $targetLangByCode = [];
        foreach ($targetLangs as $tLang) {
            $code = strtolower(trim($tLang->code ?? 'en'));
            if (!empty($code)) {
                $targetLangByCode[$code] = (int) $tLang->id;
            }
        }

        $map = [];
        foreach ($sourceLangs as $sLang) {
            $code = strtolower(trim($sLang->code ?? 'en'));
            if ($code === 'en' || $sLang->is_default == 1) {
                $map[(int) $sLang->id] = (int) $targetDefaultLangId;
            } else {
                if (count($targetLangs) > 1 && isset($targetLangByCode[$code])) {
                    $map[(int) $sLang->id] = $targetLangByCode[$code];
                } else {
                    $map[(int) $sLang->id] = null;
                }
            }
        }

        return $map;
    }

    private function resolveTargetLangId(?int $sourceLangId, array $languageMap, int $targetDefaultLangId): ?int
    {
        if (is_null($sourceLangId)) {
            return $targetDefaultLangId;
        }

        if (array_key_exists($sourceLangId, $languageMap)) {
            return $languageMap[$sourceLangId];
        }

        return $targetDefaultLangId;
    }

    private function mapSerializedIds($serialized, array $idMap)
    {
        $decoded = json_decode((string) $serialized, true);
        if (!is_array($decoded)) {
            return $serialized;
        }

        $mapped = [];
        foreach ($decoded as $oldId) {
            if (isset($idMap[$oldId])) {
                $mapped[] = $idMap[$oldId];
            }
        }

        return json_encode($mapped);
    }

    private function duplicateAsset($filename, $directory, $isStorageDirectory = false)
    {
        if (empty($filename)) {
            return null;
        }

        $sourcePath = $isStorageDirectory
            ? rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename
            : public_path(trim($directory, '/\\') . DIRECTORY_SEPARATOR . $filename);

        if (!file_exists($sourcePath)) {
            // Source file missing on filesystem — return original filename so DB retains image reference
            return $filename;
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $newFilename = uniqid() . ($extension ? '.' . $extension : '');
        $destinationPath = $isStorageDirectory
            ? rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $newFilename
            : public_path(trim($directory, '/\\') . DIRECTORY_SEPARATOR . $newFilename);

        @mkdir(dirname($destinationPath), 0775, true);
        if (!@copy($sourcePath, $destinationPath)) {
            return $filename;
        }

        return $newFilename;
    }
}
