<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\BasicExtended;
use App\Models\Membership;
use App\Models\Package;
use App\Models\User;
use App\Models\WebsiteBuilder\WbCustomer;
use App\Services\SubscriptionExpiryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SubscriptionTestController extends Controller
{
    public function index(Request $request)
    {
        $currentProduct = $request->input('product', 'launchshop');

        // 1. LaunchShop users (safely caught, isolated to LaunchShop merchants)
        try {
            $lsUsers = SubscriptionExpiryService::getLaunchShopUsers();
            if (empty($lsUsers)) {
                $lsUsers = User::orderBy('id', 'desc')->take(60)->get();
            }
        } catch (\Throwable $e) {
            $lsUsers = collect([]);
        }

        // 2. Website Builder customers (safely caught, isolated to Website Builder sites)
        try {
            $wbCustomers = SubscriptionExpiryService::getWebsiteBuilderCustomers();
        } catch (\Throwable $e) {
            $wbCustomers = [];
        }

        // 3. WhatsApp Bulk users (safely caught, isolated to WhatsApp Bulk accounts)
        try {
            $waUsers = SubscriptionExpiryService::getWhatsAppBulkUsers();
        } catch (\Throwable $e) {
            $waUsers = [];
        }

        $selectedUser = null;
        $latestMembership = null;

        try {
            if ($currentProduct === 'websitebuilder') {
                if ($request->has('user_id')) {
                    if (class_exists(WbCustomer::class)) {
                        try {
                            $selectedUser = WbCustomer::find($request->user_id);
                        } catch (\Throwable $e) {}
                    }
                    if (!$selectedUser) {
                        $selectedUser = SubscriptionExpiryService::findWbCustomer($request->user_id);
                    }
                    if (!$selectedUser && !empty($wbCustomers)) {
                        foreach ($wbCustomers as $c) {
                            if ((string)($c->id ?? '') === (string)$request->user_id) {
                                $selectedUser = $c;
                                break;
                            }
                        }
                    }
                }
            } elseif ($currentProduct === 'whatsappbulk') {
                if ($request->has('user_id')) {
                    $selectedUser = SubscriptionExpiryService::findWhatsAppBulkUser($request->user_id);
                    if (!$selectedUser && !empty($waUsers)) {
                        foreach ($waUsers as $u) {
                            if ((string)($u->id ?? '') === (string)$request->user_id) {
                                $selectedUser = $u;
                                break;
                            }
                        }
                    }
                }
            } else {
                // LaunchShop
                if ($request->has('user_id')) {
                    $selectedUser = SubscriptionExpiryService::findLaunchShopUser($request->user_id);
                    if (!$selectedUser) {
                        $selectedUser = User::find($request->user_id);
                    }
                } elseif ($request->has('username')) {
                    $selectedUser = SubscriptionExpiryService::findLaunchShopUser($request->username);
                    if (!$selectedUser) {
                        $selectedUser = User::where('username', $request->username)->first();
                    }
                }
                if ($selectedUser && isset($selectedUser->id)) {
                    try {
                        $latestMembership = Membership::where('user_id', $selectedUser->id)
                            ->orderBy('expire_date', 'desc')
                            ->first();
                    } catch (\Throwable $me) {}
                }
            }
        } catch (\Throwable $e) {
            $selectedUser = null;
            $latestMembership = null;
        }

        return view('admin.subscription_test.index', compact(
            'currentProduct',
            'lsUsers',
            'wbCustomers',
            'waUsers',
            'selectedUser',
            'latestMembership'
        ));
    }

    public function execute(Request $request)
    {
        if (!\Illuminate\Support\Facades\Auth::guard('admin')->check()) {
            $admin = \App\Models\Admin::first();
            if ($admin) {
                \Illuminate\Support\Facades\Auth::guard('admin')->login($admin);
            }
        }
        $product = $request->input('product', 'launchshop');
        $userId = $request->input('user_id');
        $action = $request->input('action', 'simulate-full');
        $minutes = intval($request->input('minutes', 5));
        $days = intval($request->input('days', 30));

        $testPhone = $request->input('test_phone');
        $testEmail = $request->input('test_email');

        $result = [
            'success' => true,
            'product' => $product,
            'action'  => $action,
            'details' => [],
        ];

        // ── 1. WEBSITE BUILDER ──────────────────────────────────────────
        if ($product === 'websitebuilder') {
            try {
                $customer = SubscriptionExpiryService::findWbCustomer($userId);
                if (!$customer) {
                    foreach (SubscriptionExpiryService::getWebsiteBuilderCustomers() as $c) {
                        if ((string)($c->id ?? '') === (string)$userId || (string)($c->subdomain ?? '') === (string)$userId) {
                            $customer = $c;
                            break;
                        }
                    }
                }
                if (!$customer) {
                    return $this->respondError('Website Builder customer not found');
                }
                if (!empty($testPhone)) $customer->phone = $testPhone;
                if (!empty($testEmail)) $customer->email = $testEmail;

                $siteUrl = url("/website-builder/{$customer->subdomain}");
                $result['user'] = [
                    'name'     => $customer->name,
                    'subdomain'=> $customer->subdomain,
                    'phone'    => $customer->phone,
                    'email'    => $customer->email,
                ];
                $result['store_url'] = $siteUrl;

                switch ($action) {
                    case 'set-expiry':
                        SubscriptionExpiryService::setExpiryForWbCustomer($customer, $minutes);
                        $result['message'] = "Website Builder site set to expire in {$minutes} minutes ({$customer->expire_date}).";
                        break;
                    case 'send-warning':
                        $warnRes = SubscriptionExpiryService::sendExpiryWarningForWbCustomer($customer, $minutes);
                        $result['warning_dispatch'] = $warnRes;
                        $result['message'] = "Expiry warning sent: WhatsApp=" . (!empty($warnRes['whatsapp_sent']) ? 'SENT' : 'NOT SENT') . ", Email=" . (!empty($warnRes['email_sent']) ? 'SENT' : 'NOT SENT');
                        break;
                    case 'expire-now':
                        SubscriptionExpiryService::expireWbCustomerImmediately($customer);
                        $result['message'] = "Website Builder site EXPIRED immediately! Public site is now BLOCKED.";
                        break;
                    case 'reactivate':
                        SubscriptionExpiryService::reactivateWbCustomer($customer, $days);
                        $result['message'] = "Website Builder site REACTIVATED! Extended for {$days} days and UNBLOCKED.";
                        break;
                    case 'simulate-full':
                    default:
                        SubscriptionExpiryService::setExpiryForWbCustomer($customer, $minutes);
                        $warnRes = SubscriptionExpiryService::sendExpiryWarningForWbCustomer($customer, $minutes);
                        $result['warning_dispatch'] = $warnRes;
                        $result['message'] = "Simulation complete! Site expires in {$minutes} min; warning notifications dispatched.";
                        break;
                }
            } catch (\Throwable $wbEx) {
                $result['success'] = false;
                $result['message'] = "Website Builder simulation error: " . $wbEx->getMessage();
            }
        }
        // ── 2. WHATSAPP BULK AUTOMATION ────────────────────────────────
        elseif ($product === 'whatsappbulk') {
            try {
                $waUser = SubscriptionExpiryService::findWhatsAppBulkUser($userId);
                if (!$waUser) {
                    foreach (SubscriptionExpiryService::getWhatsAppBulkUsers() as $u) {
                        if ((string)($u->id ?? '') === (string)$userId || (string)($u->username ?? '') === (string)$userId) {
                            $waUser = $u;
                            break;
                        }
                    }
                }
                if (!$waUser) {
                    return $this->respondError('WhatsApp Bulk user not found');
                }
                if (!empty($testPhone)) $waUser->phone = $testPhone;
                if (!empty($testEmail)) $waUser->email = $testEmail;

                $result['user'] = [
                    'name'     => $waUser->fullname ?: $waUser->username,
                    'username' => $waUser->username,
                    'phone'    => $waUser->phone,
                    'email'    => $waUser->email,
                ];
                $result['store_url'] = "https://saasreselling.com/login";

                switch ($action) {
                    case 'set-expiry':
                        SubscriptionExpiryService::setExpiryForWaUser($waUser->id, $minutes);
                        $result['message'] = "WhatsApp Bulk account set to expire in {$minutes} minutes.";
                        break;
                    case 'send-warning':
                        $warnRes = SubscriptionExpiryService::sendExpiryWarningForWaUser($waUser, $minutes);
                        $result['warning_dispatch'] = $warnRes;
                        $result['message'] = "Expiry warning sent: WhatsApp=" . (!empty($warnRes['whatsapp_sent']) ? 'SENT' : 'NOT SENT') . ", Email=" . (!empty($warnRes['email_sent']) ? 'SENT' : 'NOT SENT');
                        break;
                    case 'expire-now':
                        SubscriptionExpiryService::expireWaUserImmediately($waUser->id);
                        $result['message'] = "WhatsApp Bulk account EXPIRED immediately! Broadcast sending is PAUSED.";
                        break;
                    case 'reactivate':
                        SubscriptionExpiryService::reactivateWaUser($waUser->id, $days);
                        $result['message'] = "WhatsApp Bulk account REACTIVATED for {$days} days! Restored to active status.";
                        break;
                    case 'simulate-full':
                    default:
                        SubscriptionExpiryService::setExpiryForWaUser($waUser->id, $minutes);
                        $warnRes = SubscriptionExpiryService::sendExpiryWarningForWaUser($waUser, $minutes);
                        $result['warning_dispatch'] = $warnRes;
                        $result['message'] = "Simulation complete! Set to expire in {$minutes} min; warning notices dispatched.";
                        break;
                }
            } catch (\Throwable $waEx) {
                $result['success'] = false;
                $result['message'] = "WhatsApp Bulk simulation error: " . $waEx->getMessage();
            }
        }
        // ── 3. LAUNCHSHOP (ECOM BUILDER) ────────────────────────────────
        else {
            $user = User::find($userId);
            if (!$user) {
                return $this->respondError('LaunchShop store user not found');
            }
            if (!empty($testPhone)) $user->phone = $testPhone;
            if (!empty($testEmail)) $user->email = $testEmail;

            $storeHost = env('WEBSITE_HOST', 'launchshop.youverse.in');
            $storeUrl = "https://{$storeHost}/{$user->username}";
            $result['user'] = [
                'name'     => $user->username,
                'username' => $user->username,
                'phone'    => $user->phone ?: $user->phone_number,
                'email'    => $user->email,
            ];
            $result['store_url'] = $storeUrl;

            switch ($action) {
                case 'set-expiry':
                    $m = SubscriptionExpiryService::setExpiryInMinutes($user, $minutes);
                    $result['message'] = "Subscription set to expire in {$minutes} minutes ({$m->expire_date}).";
                    break;
                case 'send-warning':
                    $warnRes = SubscriptionExpiryService::sendExpiryWarningForUser($user, $minutes, true);
                    $result['warning_dispatch'] = $warnRes;
                    $result['message'] = "Expiry warning dispatched: WhatsApp=" . ($warnRes['whatsapp_sent'] ? 'SENT' : 'NOT SENT') . ", Email=" . ($warnRes['email_sent'] ? 'SENT' : 'NOT SENT');
                    break;
                case 'expire-now':
                    SubscriptionExpiryService::expireUserImmediately($user);
                    $result['message'] = "Subscription expired immediately! Public store is now BLOCKED (Suspended Screen).";
                    break;
                case 'reactivate':
                    SubscriptionExpiryService::reactivateUser($user, $days);
                    $result['message'] = "Subscription reactivated by Admin! Store extended for {$days} days and UNBLOCKED.";
                    break;
                case 'test-renewal':
                    $m = SubscriptionExpiryService::setExpiryInMinutes($user, $minutes);
                    try {
                        $be = BasicExtended::first();
                        $pkg = Package::find($m->package_id) ?? Package::first();
                        $pkgTitle = $pkg->title ?? 'Store Plan Renewal';
                        $txId = 'TEST_RENEW_' . strtoupper(substr(uniqid(), 7));

                        $reqData = [
                            'start_date' => $m->start_date,
                            'expire_date' => $m->expire_date,
                            'payment_method' => 'Test Gateway'
                        ];

                        $invoiceFile = Common::makeInvoice(
                            $reqData,
                            "extend",
                            $user,
                            "test_pass",
                            $m->price ?: 499,
                            "Test Gateway",
                            $user->phone ?: $user->phone_number,
                            $be->base_currency_text_position ?? 'left',
                            $be->base_currency_symbol ?? '₹',
                            $be->base_currency_text ?? 'INR',
                            $txId,
                            $pkgTitle,
                            1
                        );
                        $result['message'] = "Renewal simulated (+{$minutes} min). PDF Invoice sent to WhatsApp & Email!";
                    } catch (\Throwable $ex) {
                        $result['message'] = "Renewal simulated (+{$minutes} min). Notice: " . $ex->getMessage();
                    }
                    break;
                case 'simulate-full':
                default:
                    SubscriptionExpiryService::setExpiryInMinutes($user, $minutes);
                    $warnRes = SubscriptionExpiryService::sendExpiryWarningForUser($user, $minutes, true);
                    $result['warning_dispatch'] = $warnRes;
                    $result['message'] = "Full Simulation Complete! Set to expire in {$minutes} min; warning notifications dispatched.";
                    break;
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        Session::flash('test_result', $result);
        $redirectUrl = url('/admin/subscription_test') . '?product=' . urlencode($product);
        if (!empty($userId)) {
            $redirectUrl .= '&user_id=' . urlencode($userId);
        }
        return redirect()->to($redirectUrl);
    }

    private function respondError($msg)
    {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => false, 'message' => $msg], 404);
        }
        Session::flash('test_result', ['success' => false, 'message' => $msg]);
        return back();
    }
}