<?php

namespace App\Http\Helpers;

use App\Models\Membership;
use App\Models\Package;
use Carbon\Carbon;
use Exception;

class UserPermissionHelper
{

    private static function isDemoTheme(int $userId)
    {
        $user = \App\Models\User::query()->select('preview_template', 'email')->find($userId);
        if ($user && ($user->preview_template == 1 || self::isSathika($userId))) {
            return true;
        }
        return false;
    }

    public static function packagePermission(int $userId)
    {
        if (self::isDemoTheme($userId)) {
            $premiumPackage = Package::where('title', 'LIKE', '%Premium%')->first()
                ?? Package::orderBy('id', 'DESC')->first();
            if ($premiumPackage) {
                return $premiumPackage->features;
            }
        }

        $currentPackage = Membership::query()
            ->with('package') // Eager load the package
            ->where('user_id', $userId)
            ->where('status', 1)
            ->where('start_date', '<=', Carbon::now()->format('Y-m-d'))
            ->where('expire_date', '>=', Carbon::now()->format('Y-m-d'))
            ->first();

        return $currentPackage && $currentPackage->package
            ? $currentPackage->package->features
            : collect([]);
    }

    public static function uniqidReal($lenght = 13)
    {
        // uniqid gives 13 chars, but you could adjust it to your needs.
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }

    private static function isSathika(int $userId)
    {
        $user = \App\Models\User::query()->select('email')->find($userId);
        return $user && $user->email === 'sathikaqiq121@gmail.com';
    }

    public static function currentPackagePermission(int $userId)
    {
        if (self::isDemoTheme($userId)) {
            $premiumPackage = Package::where('title', 'LIKE', '%Premium%')->first()
                ?? Package::orderBy('id', 'DESC')->first();
            if ($premiumPackage) {
                return $premiumPackage;
            }
        }

        $currentPackage = Membership::query()->where([
            ['user_id', '=', $userId],
            ['status', '=', 1],
            ['start_date', '<=', Carbon::now()->format('Y-m-d')],
            ['expire_date', '>=', Carbon::now()->format('Y-m-d')]
        ])->first();
        $package = isset($currentPackage) ? Package::query()->findOrFail($currentPackage->package_id) : null;
        if ($package && self::isSathika($userId)) {
            $package->product_limit = 600;
            $package->categories_limit = 150;
            $package->order_limit = 600;
            $package->number_of_custom_page = 40;
            $package->post_limit = 100;
            $package->coupon_limit = 150;
        }
        return $package;
    }

    public static function currPackageOrPending($userId)
    {
        $currentPackage = Self::currentPackagePermission($userId);
        if (!$currentPackage) {
            $currentPackage = Membership::query()->where([
                ['user_id', '=', $userId],
                ['status', 0]
            ])->whereYear('start_date', '<>', '9999')->orderBy('id', 'DESC')->first();
            $package = isset($currentPackage) ? Package::query()->findOrFail($currentPackage->package_id) : null;
            if ($package && self::isSathika($userId)) {
                $package->product_limit = 600;
                $package->categories_limit = 150;
                $package->order_limit = 600;
                $package->number_of_custom_page = 40;
                $package->post_limit = 100;
                $package->coupon_limit = 150;
            }
            return $package;
        }
        return $currentPackage;
    }

    public static function currMembOrPending($userId)
    {
        $currMemb = Self::userPackage($userId);
        if (!$currMemb) {
            $currMemb = Membership::query()->where([
                ['user_id', '=', $userId],
                ['status', 0],
            ])->whereYear('start_date', '<>', '9999')->orderBy('id', 'DESC')->first();
        }
        return isset($currMemb) ? $currMemb : null;
    }

    public static function hasPendingMembership($userId)
    {
        $count = Membership::query()->where([
            ['user_id', '=', $userId],
            ['status', 0]
        ])->whereYear('start_date', '<>', '9999')->count();
        return $count > 0 ? true : false;
    }

    public static function nextPackage(int $userId)
    {
        $currMemb = Membership::query()->where([
            ['user_id', $userId],
            ['start_date', '<=', Carbon::now()->toDateString()],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999');
        $nextPackage = null;
        if ($currMemb->first()) {
            $countCurrMem = $currMemb->count();
            if ($countCurrMem > 1) {
                $nextMemb = $currMemb->orderBy('id', 'DESC')->first();
            } else {
                $nextMemb = Membership::query()->where([
                    ['user_id', $userId],
                    ['start_date', '>', $currMemb->first()->expire_date]
                ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->first();
            }
            $nextPackage = $nextMemb ? Package::query()->where('id', $nextMemb->package_id)->first() : null;
        }
        return $nextPackage;
    }

    public static function userPackage(int $userId)
    {
        return Membership::query()->where([
            ['user_id', '=', $userId],
            ['status', '=', 1],
            ['start_date', '<=', Carbon::now()->format('Y-m-d')],
            ['expire_date', '>=', Carbon::now()->format('Y-m-d')]
        ])->first();
    }

    public static function nextMembership(int $userId)
    {
        $currMemb = Membership::query()->where([
            ['user_id', $userId],
            ['start_date', '<=', Carbon::now()->toDateString()],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999');
        $nextMemb = null;
        if ($currMemb->first()) {
            $countCurrMem = $currMemb->count();
            if ($countCurrMem > 1) {
                $nextMemb = $currMemb->orderBy('id', 'DESC')->first();
            } else {
                $nextMemb = Membership::query()->where([
                    ['user_id', $userId],
                    ['start_date', '>', $currMemb->first()->expire_date]
                ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->first();
            }
        }
        return $nextMemb;
    }
}
