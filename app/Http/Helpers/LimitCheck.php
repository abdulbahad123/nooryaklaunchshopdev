<?php

namespace App\Http\Helpers;

use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;

class LimitCheck
{
    public static function current_package($user_id)
    {
        if (self::isDemoTheme($user_id)) {
            $premiumPackageId = Package::where('title', 'LIKE', '%Premium%')->value('id')
                ?? Package::orderBy('id', 'DESC')->value('id');
            if ($premiumPackageId) {
                return $premiumPackageId;
            }
        }

        $id = Membership::query()->where([
            ['user_id', '=', $user_id],
            ['status', '=', 1],
            ['start_date', '<=', Carbon::now()->format('Y-m-d')],
            ['expire_date', '>=', Carbon::now()->format('Y-m-d')]
        ])->pluck('package_id')->first();

        return $id;
    }
    public static function blogLimit($user_id)
    {
        $packageId =  self::current_package($user_id);

        if (isset($packageId)) {
            $package = Package::query()->select('post_limit')->find($packageId);
        }
        return isset($package) ? $package->post_limit : 0;
    }

    public static function itemLimit($user_id)
    {
        $packageId =  self::current_package($user_id);

        if (isset($packageId)) {
            $package = Package::query()->select('product_limit')->find($packageId);
        }
        return isset($package) ? $package->product_limit : 0;
    }

    public static function catLimit($user_id)
    {
        $packageId =  self::current_package($user_id);

        if (isset($packageId)) {
            $package = Package::query()->select('categories_limit')->find($packageId);
        }
        return isset($package) ? $package->categories_limit : 0;
    }

    public static function subcatLimit($user_id)
    {
        $packageId =  self::current_package($user_id);

        if (isset($packageId)) {
            $package = Package::query()->select('subcategories_limit')->find($packageId);
        }
        return isset($package) ? $package->subcategories_limit : 0;
    }

    public static function langLimit($user_id)
    {
        $packageId =  self::current_package($user_id);

        if (isset($packageId)) {
            $package = Package::query()->select('language_limit')->find($packageId);
        }
        return isset($package) ? $package->language_limit : 0;
    }

    public static function pageLimit($user_id)
    {
        $packageId =  self::current_package($user_id);

        if (isset($packageId)) {
            $package = Package::query()->select('number_of_custom_page')->find($packageId);
        }
        return isset($package) ? $package->number_of_custom_page : 0;
    }

    public static function orderLimit($user_id)
    {
        $packageId =  self::current_package($user_id);

        if (isset($packageId)) {
            $package = Package::query()->select('order_limit')->find($packageId);
        }
        return isset($package) ? $package->order_limit : 0;
    }

    public static function couponLimit($user_id)
    {
        $packageId =  self::current_package($user_id);

        if (isset($packageId)) {
            $package = Package::query()->select('coupon_limit')->find($packageId);
        }
        return isset($package) ? $package->coupon_limit : 0;
    }

    /**
     * count all feates from here
     * */
    public static function packageFeaturesCount(int $user_id)
    {
        $user = User::find($user_id);

        $featuresCount = [];
        $featuresCount['categories'] = $user->item_categories()->whereNotNull('unique_id')->distinct('unique_id')->count('unique_id')
            + $user->item_categories()->whereNull('unique_id')->count();
        $featuresCount['subcategories'] = $user->item_sub_categories()->whereNotNull('unique_id')->distinct('unique_id')->count('unique_id')
            + $user->item_sub_categories()->whereNull('unique_id')->count();
        $featuresCount['languages'] = max(0, $user->languages()->count() - 1);
        $featuresCount['items'] = $user->items->count();
        $featuresCount['orders'] = $user->orders()->count();
        $featuresCount['custome_page'] = $user->custome_page->count();
        $featuresCount['blogs'] = $user->blogs->count();
        $featuresCount['coupons'] = \App\Models\User\UserCoupon::where('user_id', $user_id)->count();

        return $featuresCount;
    }

    private static function isDemoTheme($user_id)
    {
        $user = User::query()->select('preview_template', 'email')->find($user_id);
        return $user && ($user->preview_template == 1 || $user->email === 'sathikaqiq121@gmail.com');
    }
}
