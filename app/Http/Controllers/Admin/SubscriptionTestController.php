<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Models\BasicExtended;
use App\Models\Membership;
use App\Models\Package;
use App\Models\User;
use App\Services\SubscriptionExpiryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SubscriptionTestController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('id', 'desc')->take(100)->get();
        $selectedUser = null;
        if ($request->has('user_id')) {
            $selectedUser = User::find($request->user_id);
        } elseif ($request->has('username')) {
            $selectedUser = User::where('username', $request->username)->first();
        }

        $latestMembership = null;
        if ($selectedUser) {
            $latestMembership = Membership::where('user_id', $selectedUser->id)
                ->orderBy('expire_date', 'desc')
                ->first();
        }

        return view('admin.subscription_test.index', compact('users', 'selectedUser', 'latestMembership'));
    }

    public function execute(Request $request)
    {
        $userId = $request->input('user_id');
        $action = $request->input('action', 'simulate-full');
        $minutes = intval($request->input('minutes', 5));
        $days = intval($request->input('days', 30));

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Apply temporary test overrides if provided in request
        if ($request->filled('test_phone')) {
            $user->phone = $request->input('test_phone');
        }
        if ($request->filled('test_email')) {
            $user->email = $request->input('test_email');
        }

        $storeHost = env('WEBSITE_HOST', 'launchshop.youverse.in');
        $storeUrl = "https://{$storeHost}/{$user->username}";
        $result = [
            'success' => true,
            'action' => $action,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'phone' => $user->phone ?: $user->phone_number,
                'email' => $user->email,
            ],
            'store_url' => $storeUrl,
            'details' => [],
        ];

        switch ($action) {
            case 'set-expiry':
                $m = SubscriptionExpiryService::setExpiryInMinutes($user, $minutes);
                $result['message'] = "Subscription set to expire in {$minutes} minutes ({$m->expire_date}).";
                $result['expire_date'] = $m->expire_date;
                break;

            case 'send-warning':
                $warnRes = SubscriptionExpiryService::sendExpiryWarningForUser($user, $minutes, true);
                $result['warning_dispatch'] = $warnRes;
                $result['message'] = "Expiry warning dispatched: WhatsApp=" . ($warnRes['whatsapp_sent'] ? 'SENT' : 'FAILED') . ", Email=" . ($warnRes['email_sent'] ? 'SENT' : 'FAILED');
                break;

            case 'expire-now':
                $m = SubscriptionExpiryService::expireUserImmediately($user);
                $result['message'] = "Subscription expired immediately! Public store is now BLOCKED (Suspended Screen).";
                $result['expire_date'] = $m->expire_date;
                break;

            case 'reactivate':
                $m = SubscriptionExpiryService::reactivateUser($user, $days);
                $result['message'] = "Subscription reactivated by Admin! Store extended for {$days} days and UNBLOCKED.";
                $result['expire_date'] = $m->expire_date;
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
                        'payment_method' => 'Test Mode / Gateway'
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
                    $result['invoice'] = $invoiceFile;
                    $result['message'] = "Payment renewal simulated (+{$minutes} min). Invoice PDF generated & sent via WhatsApp/Email!";
                } catch (\Throwable $ex) {
                    $result['message'] = "Payment renewal simulated (+{$minutes} min). Notice: " . $ex->getMessage();
                }
                break;

            case 'simulate-full':
            default:
                $m = SubscriptionExpiryService::setExpiryInMinutes($user, $minutes);
                $warnRes = SubscriptionExpiryService::sendExpiryWarningForUser($user, $minutes, true);
                $result['warning_dispatch'] = $warnRes;
                $result['expire_date'] = $m->expire_date;
                $result['message'] = "Full Simulation Complete! Set to expire in {$minutes} min, and sent warning notifications.";
                break;
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        Session::flash('test_result', $result);
        return back();
    }
}