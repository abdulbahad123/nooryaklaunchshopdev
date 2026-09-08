<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteBuilder\WbAgencySetting;
use App\Models\WebsiteBuilder\WbCustomer;
use App\Models\User\UserCustomDomain;
use Illuminate\Support\Facades\Schema;

class WbDomainController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');

        // 1. Clean up dummy 'manti' entries from user_custom_domains & wb_agency_settings
        try {
            if (Schema::hasTable('user_custom_domains')) {
                $hasReqDomain = Schema::hasColumn('user_custom_domains', 'requested_domain');
                $hasDomain = Schema::hasColumn('user_custom_domains', 'domain');
                if ($hasReqDomain || $hasDomain) {
                    UserCustomDomain::where(function($subQ) use ($hasReqDomain, $hasDomain) {
                        if ($hasReqDomain) $subQ->where('requested_domain', 'like', '%manti%');
                        if ($hasDomain) $subQ->orWhere('domain', 'like', '%manti%');
                    })->delete();
                }
            }
            if (Schema::hasTable('wb_agency_settings')) {
                WbAgencySetting::where('custom_domain', 'like', '%manti%')->update([
                    'custom_domain' => null,
                    'custom_domain_status' => 0
                ]);
            }
        } catch (\Throwable $e) {}

        $domainList = collect();

        // 2. Fetch domain requests from wb_agency_settings
        if (Schema::hasTable('wb_agency_settings')) {
            WbAgencySetting::ensureColumnsExist();
            $query = WbAgencySetting::whereNotNull('custom_domain')->where('custom_domain', '!=', '');

            if ($status === 'pending') {
                $query->where('custom_domain_status', 0);
            } elseif ($status === 'connected') {
                $query->where('custom_domain_status', 1);
            } elseif ($status === 'rejected') {
                $query->where('custom_domain_status', 2);
            }

            $agencySettingsList = $query->orderBy('updated_at', 'desc')->get();

            foreach ($agencySettingsList as $setting) {
                $customer = null;
                if ($setting->customer_id && Schema::hasTable('wb_customers')) {
                    $customer = WbCustomer::find($setting->customer_id);
                }

                $domainList->push((object)[
                    'id'               => 'agency_' . $setting->id,
                    'setting_id'       => $setting->id,
                    'client_name'      => $customer ? ($customer->company_name ?: $customer->name) : ($setting->site_title ?: 'Client'),
                    'client_email'     => $customer ? $customer->email : ($setting->email ?: 'client@example.com'),
                    'requested_domain' => $setting->custom_domain,
                    'subdomain'        => $customer ? $customer->subdomain : 'wezantech',
                    'status'           => (int)$setting->custom_domain_status,
                    'type'             => 'agency',
                    'created_at'       => $setting->updated_at ?: now(),
                ]);
            }
        }

        // 3. Fetch domain requests from user_custom_domains table
        if (Schema::hasTable('user_custom_domains')) {
            $uQuery = UserCustomDomain::with('user');
            if ($status === 'pending') {
                $uQuery->where('status', 0);
            } elseif ($status === 'connected') {
                $uQuery->where('status', 1);
            } elseif ($status === 'rejected') {
                $uQuery->where('status', 2);
            }

            $uList = $uQuery->orderBy('id', 'desc')->get();
            $hasReqDomain = Schema::hasColumn('user_custom_domains', 'requested_domain');
            $hasDomain = Schema::hasColumn('user_custom_domains', 'domain');

            foreach ($uList as $ud) {
                $domainName = ($hasReqDomain ? $ud->requested_domain : null) ?: ($hasDomain ? $ud->domain : null);
                if ($domainName && !str_contains($domainName, 'manti') && !$domainList->firstWhere('requested_domain', $domainName)) {
                    $domainList->push((object)[
                        'id'               => 'user_' . $ud->id,
                        'setting_id'       => null,
                        'user_domain_id'   => $ud->id,
                        'client_name'      => $ud->user ? ($ud->user->username ?: $ud->user->first_name) : 'Client',
                        'client_email'     => $ud->user ? $ud->user->email : '',
                        'requested_domain' => $domainName,
                        'subdomain'        => $ud->user ? $ud->user->username : 'sub',
                        'status'           => (int)$ud->status,
                        'type'             => 'user',
                        'created_at'       => $ud->created_at ?: now(),
                    ]);
                }
            }
        }

        $domains = $domainList;

        return view('website_builder.admin.domains.index', compact('domains', 'status'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|in:0,1,2',
        ]);

        $newStatus = (int)$request->status;

        if (str_starts_with($id, 'agency_')) {
            $settingId = str_replace('agency_', '', $id);
            $setting = WbAgencySetting::find($settingId);
            if ($setting) {
                $setting->custom_domain_status = $newStatus;
                $setting->save();

                if ($setting->custom_domain && Schema::hasTable('user_custom_domains')) {
                    $hasReqDomain = Schema::hasColumn('user_custom_domains', 'requested_domain');
                    $hasDomain = Schema::hasColumn('user_custom_domains', 'domain');
                    if ($hasReqDomain || $hasDomain) {
                        UserCustomDomain::where(function($q) use ($setting, $hasReqDomain, $hasDomain) {
                            if ($hasReqDomain) $q->where('requested_domain', $setting->custom_domain);
                            if ($hasDomain) $q->orWhere('domain', $setting->custom_domain);
                        })->update(['status' => $newStatus]);
                    }
                }
            }
        } else {
            $realId = str_replace('user_', '', $id);
            if (Schema::hasTable('user_custom_domains')) {
                $ud = UserCustomDomain::find($realId);
                if ($ud) {
                    $ud->status = $newStatus;
                    $ud->save();

                    $hasReqDomain = Schema::hasColumn('user_custom_domains', 'requested_domain');
                    $hasDomain = Schema::hasColumn('user_custom_domains', 'domain');
                    $domainName = ($hasReqDomain ? $ud->requested_domain : null) ?: ($hasDomain ? $ud->domain : null);

                    if ($domainName && Schema::hasTable('wb_agency_settings')) {
                        $cleanDom = strtolower(trim(preg_replace('#^https?://#', '', $domainName)));
                        $cleanDom = preg_replace('#^www\.#', '', $cleanDom);
                        $cleanDom = rtrim($cleanDom, '/');
                        WbAgencySetting::where(function($q) use ($cleanDom) {
                            $q->where('custom_domain', $cleanDom)
                              ->orWhere('custom_domain', 'www.' . $cleanDom)
                              ->orWhere('custom_domain', 'https://' . $cleanDom)
                              ->orWhere('custom_domain', 'http://' . $cleanDom)
                              ->orWhere('custom_domain', 'like', '%' . $cleanDom . '%');
                        })->update(['custom_domain_status' => $newStatus]);
                    }
                }
            }
        }

        $statusText = $newStatus == 1 ? 'Connected (Approved)' : ($newStatus == 2 ? 'Rejected' : 'Pending');
        return redirect()->back()->with('success', "Domain request updated to {$statusText} successfully!");
    }
}

