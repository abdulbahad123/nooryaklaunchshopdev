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
            'electronics' => 'electi',
            'fashion' => 'fashclo',
            'furniture' => 'furial',
            'grocery' => 'grocery',
            'kids' => 'kidsfa',
            'manti' => 'manti',
            'pet' => 'petrashop',
            'skinflow' => 'skinflow',
            'jewellery' => 'jewellery',
            'vegetables' => 'grocery',
            'clothing' => 'clothing',
        ];

        $templateUser = null;

        // Respect an explicit source when provided; otherwise infer from the tenant theme.
        if (!empty($sourceOption)) {
            $templateUser = User::where('username', $sourceOption)->first();
        }

        if (empty($templateUser)) {
            $theme = UserBasicSetting::where('user_id', $targetUser->id)->value('theme');

            if (!empty($theme) && isset($themeSourceMap[$theme])) {
                $templateUser = User::where('username', $themeSourceMap[$theme])->first();
            }
        }

        if (empty($templateUser)) {
            $templateUser = User::where('shop_name', 'Grocery Shop')
                ->orWhere('template_serial_number', 2)
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
            $this->error('Target user has no default currency.');
            return self::FAILURE;
        }

        $languageMap = $this->buildLanguageMap($templateUser->id, $targetUser->id);

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

        DB::transaction(function () use ($templateUser, $targetUser, $defaultCurrencyId, $languageMap, $categoriesLimit, $subcategoriesLimit, $productLimit) {
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
                'user_headings'
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
                    $newCategory = $sourceCategory->replicate();
                    $newCategory->user_id = $targetUser->id;
                    $newCategory->unique_id = $newUniqueId;
                    $newCategory->language_id = $languageMap[$sourceCategory->language_id] ?? $sourceCategory->language_id;
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
                        $newSubcategory = $sourceSubcategory->replicate();
                        $newSubcategory->user_id = $targetUser->id;
                        $newSubcategory->unique_id = $newUniqueId;
                        $newSubcategory->language_id = $languageMap[$sourceSubcategory->language_id] ?? $sourceSubcategory->language_id;
                        $newSubcategory->category_id = $categoryMap[$sourceSubcategory->category_id] ?? $sourceSubcategory->category_id;
                        $this->safeSave($newSubcategory);

                        $subcategoryMap[$sourceSubcategory->id] = $newSubcategory->id;
                    }
                }
                $subcategoriesCloned++;
            }

            $sourceVariantContents = VariantContent::where('user_id', $templateUser->id)->orderBy('id')->get();
            foreach ($sourceVariantContents as $sourceVariantContent) {
                $newVariantContent = $sourceVariantContent->replicate();
                $newVariantContent->user_id = $targetUser->id;
                $newVariantContent->language_id = $languageMap[$sourceVariantContent->language_id] ?? $sourceVariantContent->language_id;
                $newVariantContent->category_id = $categoryMap[$sourceVariantContent->category_id] ?? $sourceVariantContent->category_id;
                $newVariantContent->sub_category_id = $subcategoryMap[$sourceVariantContent->sub_category_id] ?? $sourceVariantContent->sub_category_id;
                $this->safeSave($newVariantContent);

                $variantContentMap[$sourceVariantContent->id] = $newVariantContent->id;
            }

            $sourceVariantOptions = VariantOptionContent::where('user_id', $templateUser->id)->orderBy('id')->get();
            foreach ($sourceVariantOptions as $sourceVariantOption) {
                $newVariantOption = $sourceVariantOption->replicate();
                $newVariantOption->user_id = $targetUser->id;
                $newVariantOption->language_id = $languageMap[$sourceVariantOption->language_id] ?? $sourceVariantOption->language_id;
                $this->safeSave($newVariantOption);

                $variantOptionMap[$sourceVariantOption->id] = $newVariantOption->id;
            }

            $sourceItems = UserItem::where('user_id', $templateUser->id)->orderBy('id')->get();
            $productsCloned = 0;
            foreach ($sourceItems as $sourceItem) {
                if ($productsCloned >= $productLimit) {
                    break;
                }

                // Verify if the product's categories are cloned
                $itemContents = UserItemContent::where('item_id', $sourceItem->id)->get();
                $hasValidCategory = true;
                foreach ($itemContents as $sourceContent) {
                    if (!empty($sourceContent->category_id) && !isset($categoryMap[$sourceContent->category_id])) {
                        $hasValidCategory = false;
                        break;
                    }
                }
                if (!$hasValidCategory) {
                    continue;
                }

                $newItem = $sourceItem->replicate();
                $newItem->user_id = $targetUser->id;
                $newItem->currency_id = $defaultCurrencyId;
                $newItem->thumbnail = $this->duplicateAsset($sourceItem->thumbnail, 'assets/front/img/user/items/thumbnail/');
                $newItem->download_file = $this->duplicateAsset($sourceItem->download_file, storage_path('digital_products/'), true);
                $this->safeSave($newItem);
                $itemMap[$sourceItem->id] = $newItem->id;

                foreach (UserItemImage::where('item_id', $sourceItem->id)->get() as $sourceImage) {
                    UserItemImage::create([
                        'item_id' => $newItem->id,
                        'image' => $this->duplicateAsset($sourceImage->image, 'assets/front/img/user/items/slider-images/'),
                    ]);
                }

                foreach ($itemContents as $sourceContent) {
                    $newContent = $sourceContent->replicate();
                    $newContent->user_id = $targetUser->id;
                    $newContent->item_id = $newItem->id;
                    $newContent->language_id = $languageMap[$sourceContent->language_id] ?? $sourceContent->language_id;
                    $newContent->category_id = $categoryMap[$sourceContent->category_id] ?? $sourceContent->category_id;
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
                    $newVariationContent = $sourceVariationContent->replicate();
                    $newVariationContent->user_id = $targetUser->id;
                    $newVariationContent->item_id = $newItem->id;
                    $newVariationContent->language_id = $languageMap[$sourceVariationContent->language_id] ?? $sourceVariationContent->language_id;
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
                    $newVariationOptionContent = $sourceVariationOptionContent->replicate();
                    $newVariationOptionContent->user_id = $targetUser->id;
                    $newVariationOptionContent->item_id = $newItem->id;
                    $newVariationOptionContent->language_id = $languageMap[$sourceVariationOptionContent->language_id] ?? $sourceVariationOptionContent->language_id;
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
            $targetBasicSetting   = UserBasicSetting::where('user_id', $targetUser->id)->first();
            if ($templateBasicSetting && $targetBasicSetting) {
                $targetBasicSetting->logo       = $this->duplicateAsset($templateBasicSetting->logo,      'assets/front/img/user/');
                $targetBasicSetting->favicon    = $this->duplicateAsset($templateBasicSetting->favicon,   'assets/front/img/user/');
                $targetBasicSetting->preloader  = $this->duplicateAsset($templateBasicSetting->preloader, 'assets/front/img/user/');
                $targetBasicSetting->breadcrumb = $this->duplicateAsset($templateBasicSetting->breadcrumb, 'assets/front/img/user/breadcrumb/');
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
                $newSection = $sourceSection->replicate();
                $newSection->user_id = $targetUser->id;
                if (isset($sourceSection->language_id)) {
                    $newSection->language_id = $languageMap[$sourceSection->language_id] ?? $sourceSection->language_id;
                }
                $this->safeSave($newSection);
            }

            foreach (SEO::where('user_id', $templateUser->id)->get() as $sourceSeo) {
                $newSeo = $sourceSeo->replicate();
                $newSeo->user_id = $targetUser->id;
                $newSeo->language_id = $languageMap[$sourceSeo->language_id] ?? $sourceSeo->language_id;
                $this->safeSave($newSeo);
            }

            foreach (BasicExtende::where('user_id', $templateUser->id)->get() as $sourceBasicExtende) {
                $newBasicExtende = $sourceBasicExtende->replicate();
                $newBasicExtende->user_id = $targetUser->id;
                if (isset($sourceBasicExtende->language_id)) {
                    $newBasicExtende->language_id = $languageMap[$sourceBasicExtende->language_id] ?? $sourceBasicExtende->language_id;
                }
                if (isset($sourceBasicExtende->hero_section_background_image)) {
                    $newBasicExtende->hero_section_background_image = $this->duplicateAsset($sourceBasicExtende->hero_section_background_image, 'assets/front/img/hero_slider/');
                }
                $this->safeSave($newBasicExtende);
            }

            if ($this->tableExists((new HeroSlider)->getTable())) {
                foreach (HeroSlider::where('user_id', $templateUser->id)->get() as $sourceHeroSlider) {
                    $newHeroSlider = $sourceHeroSlider->replicate();
                    $newHeroSlider->user_id = $targetUser->id;
                    $newHeroSlider->language_id = $languageMap[$sourceHeroSlider->language_id] ?? $sourceHeroSlider->language_id;
                    $newHeroSlider->img = $this->duplicateAsset($sourceHeroSlider->img, 'assets/front/img/hero_slider/');
                    $this->safeSave($newHeroSlider);
                }
            }

            if ($this->tableExists((new Banner)->getTable())) {
                foreach (Banner::where('user_id', $templateUser->id)->get() as $sourceBanner) {
                    $newBanner = $sourceBanner->replicate();
                    $newBanner->user_id = $targetUser->id;
                    $newBanner->language_id = $languageMap[$sourceBanner->language_id] ?? $sourceBanner->language_id;
                    $newBanner->banner_img = $this->duplicateAsset($sourceBanner->banner_img, 'assets/front/img/user/banners/');
                    $this->safeSave($newBanner);
                }
            }

            if ($this->tableExists((new Tab)->getTable())) {
                foreach (Tab::where('user_id', $templateUser->id)->get() as $sourceTab) {
                    $newTab = $sourceTab->replicate();
                    $newTab->user_id = $targetUser->id;
                    $newTab->language_id = $languageMap[$sourceTab->language_id] ?? $sourceTab->language_id;
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
                    $newProductHeroSlider->products = $this->mapSerializedIds($sourceProductHeroSlider->products, $itemMap);
                    $this->safeSave($newProductHeroSlider);
                }
            }

            if ($this->tableExists((new HowitWorkSection)->getTable())) {
                foreach (HowitWorkSection::where('user_id', $templateUser->id)->get() as $sourceHowItWorkSection) {
                    $newHowItWorkSection = $sourceHowItWorkSection->replicate();
                    $newHowItWorkSection->user_id = $targetUser->id;
                    $newHowItWorkSection->language_id = $languageMap[$sourceHowItWorkSection->language_id] ?? $sourceHowItWorkSection->language_id;
                    $this->safeSave($newHowItWorkSection);
                }
            }

            if ($this->tableExists((new CounterInformation)->getTable())) {
                foreach (CounterInformation::where('user_id', $templateUser->id)->get() as $sourceCounterInformation) {
                    $newCounterInformation = $sourceCounterInformation->replicate();
                    $newCounterInformation->user_id = $targetUser->id;
                    $newCounterInformation->language_id = $languageMap[$sourceCounterInformation->language_id] ?? $sourceCounterInformation->language_id;
                    $this->safeSave($newCounterInformation);
                }
            }

            if ($this->tableExists((new CounterSection)->getTable())) {
                foreach (CounterSection::where('user_id', $templateUser->id)->get() as $sourceCounterSection) {
                    $newCounterSection = $sourceCounterSection->replicate();
                    $newCounterSection->user_id = $targetUser->id;
                    $newCounterSection->language_id = $languageMap[$sourceCounterSection->language_id] ?? $sourceCounterSection->language_id;
                    $newCounterSection->image = $this->duplicateAsset($sourceCounterSection->image, 'assets/front/img/user/about/');
                    $this->safeSave($newCounterSection);
                }
            }

            if ($this->tableExists((new CallToAction)->getTable())) {
                foreach (CallToAction::where('user_id', $templateUser->id)->get() as $sourceCallToAction) {
                    $newCallToAction = $sourceCallToAction->replicate();
                    $newCallToAction->user_id = $targetUser->id;
                    $newCallToAction->language_id = $languageMap[$sourceCallToAction->language_id] ?? $sourceCallToAction->language_id;
                    $newCallToAction->side_image = $this->duplicateAsset($sourceCallToAction->side_image, 'assets/front/img/cta/');
                    $newCallToAction->background_image = $this->duplicateAsset($sourceCallToAction->background_image, 'assets/front/img/cta/');
                    $this->safeSave($newCallToAction);
                }
            }

            if ($this->tableExists((new Testimonial)->getTable())) {
                foreach (Testimonial::where('user_id', $templateUser->id)->get() as $sourceTestimonial) {
                    $newTestimonial = $sourceTestimonial->replicate();
                    $newTestimonial->user_id = $targetUser->id;
                    $newTestimonial->language_id = $languageMap[$sourceTestimonial->language_id] ?? $sourceTestimonial->language_id;
                    $newTestimonial->image = $this->duplicateAsset($sourceTestimonial->image, 'assets/front/img/testimonials/');
                    $this->safeSave($newTestimonial);
                }
            }

            if ($this->tableExists((new UserFooter)->getTable())) {
                foreach (UserFooter::where('user_id', $templateUser->id)->get() as $sourceFooter) {
                    $langId = $languageMap[$sourceFooter->language_id] ?? $sourceFooter->language_id;
                    // Update the footer created during registration if it exists, otherwise insert
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
                    $newUlink = $sourceUlink->replicate();
                    $newUlink->user_id = $targetUser->id;
                    $newUlink->language_id = $languageMap[$sourceUlink->language_id] ?? $sourceUlink->language_id;
                    $this->safeSave($newUlink);
                }
            }

            if ($this->tableExists((new UserMenu)->getTable())) {
                foreach (UserMenu::where('user_id', $templateUser->id)->get() as $sourceMenu) {
                    $langId = $languageMap[$sourceMenu->language_id] ?? $sourceMenu->language_id;
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
                    $new = $source->replicate();
                    $new->user_id = $targetUser->id;
                    $new->language_id = $languageMap[$source->language_id] ?? $source->language_id;
                    $new->background_image = $this->duplicateAsset($source->background_image, 'assets/front/img/hero_slider/');
                    $new->hero_image = $this->duplicateAsset($source->hero_image, 'assets/front/img/hero_slider/');
                    $this->safeSave($new);
                }
            }

            // AboutUs — used by the About page
            if ($this->tableExists((new AboutUs)->getTable())) {
                foreach (AboutUs::where('user_id', $templateUser->id)->get() as $source) {
                    $new = $source->replicate();
                    $new->user_id = $targetUser->id;
                    $new->language_id = $languageMap[$source->language_id] ?? $source->language_id;
                    $new->image = $this->duplicateAsset($source->image, 'assets/front/img/user/about/');
                    $this->safeSave($new);
                }
            }

            // AboutUsFeatures — used by the About page
            if ($this->tableExists((new AboutUsFeatures)->getTable())) {
                foreach (AboutUsFeatures::where('user_id', $templateUser->id)->get() as $source) {
                    $new = $source->replicate();
                    $new->user_id = $targetUser->id;
                    $new->language_id = $languageMap[$source->language_id] ?? $source->language_id;
                    $this->safeSave($new);
                }
            }

            // UserContact — used by the Contact page
            if ($this->tableExists((new UserContact)->getTable())) {
                foreach (UserContact::where('user_id', $templateUser->id)->get() as $source) {
                    $new = $source->replicate();
                    $new->user_id = $targetUser->id;
                    $new->language_id = $languageMap[$source->language_id] ?? $source->language_id;
                    $this->safeSave($new);
                }
            }

            // Faq — used by the FAQ page
            if ($this->tableExists((new Faq)->getTable())) {
                foreach (Faq::where('user_id', $templateUser->id)->get() as $source) {
                    $new = $source->replicate();
                    $new->user_id = $targetUser->id;
                    $new->language_id = $languageMap[$source->language_id] ?? $source->language_id;
                    $this->safeSave($new);
                }
            }

            // UserFeature — used by features section
            if ($this->tableExists((new UserFeature)->getTable())) {
                foreach (UserFeature::where('user_id', $templateUser->id)->get() as $source) {
                    $new = $source->replicate();
                    $new->user_id = $targetUser->id;
                    $new->language_id = $languageMap[$source->language_id] ?? $source->language_id;
                    $this->safeSave($new);
                }
            }

            // UserHeader — used by header section
            if ($this->tableExists((new UserHeader)->getTable())) {
                foreach (UserHeader::where('user_id', $templateUser->id)->get() as $source) {
                    $new = $source->replicate();
                    $new->user_id = $targetUser->id;
                    $new->language_id = $languageMap[$source->language_id] ?? $source->language_id;
                    $this->safeSave($new);
                }
            }

            // UserHeading — used for page headings
            if ($this->tableExists((new UserHeading)->getTable())) {
                foreach (UserHeading::where('user_id', $templateUser->id)->get() as $source) {
                    $new = $source->replicate();
                    $new->user_id = $targetUser->id;
                    $new->language_id = $languageMap[$source->language_id] ?? $source->language_id;
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
                    $newAdditionalSectionContent = $sourceAdditionalSectionContent->replicate();
                    $newAdditionalSectionContent->addition_section_id = $additionalSectionMap[$sourceAdditionalSectionContent->addition_section_id] ?? $sourceAdditionalSectionContent->addition_section_id;
                    $newAdditionalSectionContent->language_id = $languageMap[$sourceAdditionalSectionContent->language_id] ?? $sourceAdditionalSectionContent->language_id;
                    $this->safeSave($newAdditionalSectionContent);
                }
            }
        });

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
        $sourceLangs = UserLanguage::where('user_id', $sourceUserId)->pluck('id', 'code')->toArray();
        $targetLangs = UserLanguage::where('user_id', $targetUserId)->pluck('id', 'code')->toArray();

        $map = [];
        foreach ($sourceLangs as $code => $sourceLangId) {
            if (isset($targetLangs[$code])) {
                $map[(int) $sourceLangId] = (int) $targetLangs[$code];
            }
        }

        return $map;
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
            // Source file missing — return null so the target row doesn't store a broken path
            return null;
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $newFilename = uniqid() . ($extension ? '.' . $extension : '');
        $destinationPath = $isStorageDirectory
            ? rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $newFilename
            : public_path(trim($directory, '/\\') . DIRECTORY_SEPARATOR . $newFilename);

        @mkdir(dirname($destinationPath), 0775, true);
        if (!@copy($sourcePath, $destinationPath)) {
            return null;
        }

        return $newFilename;
    }
}
