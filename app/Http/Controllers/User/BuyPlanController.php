<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UserPermissionHelper;
use App\Models\Language;
use App\Models\Membership;
use App\Models\OfflineGateway;
use App\Models\Package;
use App\Models\PaymentGateway;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Session;

class BuyPlanController extends Controller
{
    public function index()
    {
        $user_id = Auth::guard('web')->user()->id;
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['bex'] = $currentLang->basic_extended;
        $data['allPfeatures'] = json_decode($data['bex']->package_features, true) ?: [];
        $data['packages'] = Package::where('status', '1')->get();

        $nextPackageCount = Membership::query()->where([
            ['user_id', $user_id],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->count();
        //current package
        $data['current_membership'] = Membership::query()->where([
            ['user_id', $user_id],
            ['start_date', '<=', Carbon::now()->toDateString()],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->where('status', 1)->whereYear('start_date', '<>', '9999')->first();
        if ($data['current_membership']) {
            $countCurrMem = Membership::query()->where([
                ['user_id', $user_id],
                ['start_date', '<=', Carbon::now()->toDateString()],
                ['expire_date', '>=', Carbon::now()->toDateString()]
            ])->where('status', 1)->whereYear('start_date', '<>', '9999')->count();
            if ($countCurrMem > 1) {
                $data['next_membership'] = Membership::query()->where([
                    ['user_id', $user_id],
                    ['start_date', '<=', Carbon::now()->toDateString()],
                    ['expire_date', '>=', Carbon::now()->toDateString()]
                ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')->orderBy('id', 'DESC')->first();
            } else {
                $data['next_membership'] = Membership::query()->where([
                    ['user_id', $user_id],
                    ['start_date', '>', $data['current_membership']->expire_date]
                ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->first();
            }
            $data['next_package'] = $data['next_membership'] ? Package::query()->where('id', $data['next_membership']->package_id)->first() : null;
        }
        
        $data['current_package'] = $data['current_membership'] ? Package::query()->where('id', $data['current_membership']->package_id)->first() : null;
        if (Auth::guard('web')->user()->preview_template == 1) {
            $premiumPackage = Package::where('title', 'LIKE', '%Premium%')->first()
                ?? Package::orderBy('id', 'DESC')->first();
            if ($premiumPackage) {
                $data['current_package'] = $premiumPackage;
            }
        }
        $data['package_count'] = $nextPackageCount;

        return view('user.buy_plan.index', $data);
    }

    public function checkout($package_id)
    {
        $user_id = Auth::guard('web')->user()->id;
        $packageCount = Membership::query()->where([
            ['user_id', $user_id],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->count();

        $hasPendingMemb = UserPermissionHelper::hasPendingMembership($user_id);


        if ($hasPendingMemb) {
            Session::flash('warning', __('You already have a pending membership request'));
            return back();
        }
        if ($packageCount >= 2) {
            Session::flash('warning', __('You have another package to activate after the current package expires. You cannot purchase or extend any package until the next package is activated'));
            return back();
        }

        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()
                ->get('lang'))
                ->first();
        } else {
            $currentLang = Language::where('is_default', 1)
                ->first();
        }
        $be = $currentLang->basic_extended;
        $online = PaymentGateway::query()->where('status', 1)->get();
        $offline = OfflineGateway::all();
        $data['offline'] = $offline;
        $data['payment_methods'] = $online->merge($offline);
        $data['checkout_package'] = Package::query()->findOrFail($package_id);
        $data['membership'] = Membership::query()->where([
            ['user_id', $user_id],
            ['expire_date', '>=', \Carbon\Carbon::now()->format('Y-m-d')]
        ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')
            ->latest()
            ->first();
        $data['previousPackage'] = null;
        if (!is_null($data['membership'])) {
            $data['previousPackage'] = Package::query()
                ->where('id', $data['membership']->package_id)
                ->first();
        }

        // Calculate Prorated Discount for Yearly-to-Yearly Upgrades
        $data['discount'] = 0;
        $data['final_price'] = $data['checkout_package']->price;

        if (
            !is_null($data['membership']) && 
            !is_null($data['previousPackage']) && 
            $data['previousPackage']->term === 'yearly' && 
            $data['checkout_package']->term === 'yearly' &&
            $data['previousPackage']->id !== $data['checkout_package']->id
        ) {
            $startDate = \Carbon\Carbon::parse($data['membership']->start_date);
            $expireDate = \Carbon\Carbon::parse($data['membership']->expire_date);
            $totalDays = $startDate->diffInDays($expireDate) + 1;
            if ($totalDays <= 0) {
                $totalDays = 365;
            }

            // Daily rate of the previous package
            $dailyRate = floatval($data['membership']->price) / $totalDays;

            // Remaining days of the previous package
            $today = \Carbon\Carbon::today();
            if ($today->lt($expireDate)) {
                $remainingDays = $today->diffInDays($expireDate);
                $calculatedDiscount = $dailyRate * $remainingDays;
                
                // Cap the discount to the price of the new package
                $data['discount'] = round(min($calculatedDiscount, $data['checkout_package']->price), 2);
                $data['final_price'] = round($data['checkout_package']->price - $data['discount'], 2);
            }
        }

        $data['bex'] = $be;
        return view('user.buy_plan.checkout', $data);
    }
}
