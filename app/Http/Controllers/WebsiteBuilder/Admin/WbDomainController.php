<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteBuilder\WbAgencySetting;
use App\Models\WebsiteBuilder\WbCustomer;
use App\Models\User\UserCustomDomain;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class WbDomainController extends Controller
{
    private function tryConnectDb(string $targetDb): bool
    {
        $origUser   = config('database.connections.mysql.username');
        $origPass   = config('database.connections.mysql.password');
        $tenantUser = env('SASS_ADMIN_DB_USER', env('DB_USERNAME_admin', $origUser));
        $tenantPass = env('SASS_ADMIN_DB_PASS', env('DB_PASSWORD_admin', $origPass));

        $userPairs = array_values(array_filter([
            ['user' => $tenantUser, 'pass' => $tenantPass],
            ['user' => $origUser,   'pass' => $origPass],
        ], function ($item) {
            return !empty($item['user']);
        }));

        foreach ($userPairs as $pair) {
            try {
                DB::purge('mysql');
                config([
                    'database.connections.mysql.database' => $targetDb,
                    'database.connections.mysql.username' => $pair['user'],
                    'database.connections.mysql.password' => $pair['pass'],
                ]);
                DB::reconnect('mysql');
                DB::connection('mysql')->getPdo();
                return true;
            } catch (\Throwable $e) {}
        }

        return false;
    }

    private function getAllDatabaseNames(): array
    {
        $dbs = [];
        try {
            $rows = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME NOT IN ('information_schema', 'mysql', 'performance_schema', 'sys')");
            foreach ($rows as $r) {
                if (!empty($r->SCHEMA_NAME)) {
                    $dbs[] = $r->SCHEMA_NAME;
                }
            }
        } catch (\Throwable $e) {}

        $current = config('database.connections.mysql.database');
        if ($current) {
            $dbs[] = $current;
        }

        return array_values(array_unique(array_filter($dbs)));
    }

    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $domainList = collect();
        $allDbs = $this->getAllDatabaseNames();
        $originalDb = config('database.connections.mysql.database');

        foreach ($allDbs as $dbName) {
            if (!$this->tryConnectDb($dbName)) {
                continue;
            }

            try {
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

                        $existing = $domainList->firstWhere('requested_domain', $setting->custom_domain);
                        if (!$existing) {
                            $domainList->push((object)[
                                'id'               => 'agency_' . $dbName . '___' . $setting->id,
                                'db_name'          => $dbName,
                                'setting_id'       => $setting->id,
                                'client_name'      => $customer ? ($customer->company_name ?: $customer->name) : ($setting->site_title ?: 'Client'),
                                'client_email'     => $customer ? $customer->email : ($setting->email ?: 'client@example.com'),
                                'requested_domain' => $setting->custom_domain,
                                'subdomain'        => $customer ? $customer->subdomain : ($setting->site_title ? strtolower(preg_replace('/[^a-z0-9]/', '', strtolower($setting->site_title))) : ''),
                                'status'           => (int)$setting->custom_domain_status,
                                'type'             => 'agency',
                                'created_at'       => $setting->updated_at ?: now(),
                            ]);
                        }
                    }
                }

                if (Schema::hasTable('user_custom_domains')) {
                    $ucQuery = UserCustomDomain::query();
                    if ($status === 'pending') {
                        $ucQuery->where('status', 0);
                    } elseif ($status === 'connected') {
                        $ucQuery->where('status', 1);
                    } elseif ($status === 'rejected') {
                        $ucQuery->where('status', 2);
                    }
                    $ucList = $ucQuery->orderBy('updated_at', 'desc')->get();
                    foreach ($ucList as $uc) {
                        $domainName = $uc->requested_domain ?: $uc->current_domain;
                        if (!$domainName) continue;

                        $existing = $domainList->firstWhere('requested_domain', $domainName);
                        if (!$existing) {
                            $userObj = null;
                            try {
                                $userObj = $uc->user;
                            } catch (\Throwable $e) {}

                            $domainList->push((object)[
                                'id'               => 'ucd_' . $dbName . '___' . $uc->id,
                                'db_name'          => $dbName,
                                'setting_id'       => $uc->id,
                                'client_name'      => $userObj ? ($userObj->username ?: $userObj->first_name) : 'Client',
                                'client_email'     => $userObj ? $userObj->email : 'client@example.com',
                                'requested_domain' => $domainName,
                                'subdomain'        => $userObj ? $userObj->username : '',
                                'status'           => (int)$uc->status,
                                'type'             => 'ucd',
                                'created_at'       => $uc->updated_at ?: now(),
                            ]);
                        }
                    }
                }
            } catch (\Throwable $e) {}
        }

        $this->tryConnectDb($originalDb);

        $domains = $domainList;

        return view('website_builder.admin.domains.index', compact('domains', 'status'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|integer|in:0,1,2',
        ]);

        $newStatus = (int)$request->status;
        $allDbs = $this->getAllDatabaseNames();
        $originalDb = config('database.connections.mysql.database');

        $targetDomain = null;
        $targetDbName = null;
        $settingId = null;

        if (str_starts_with($id, 'agency_') || str_starts_with($id, 'ucd_')) {
            $raw = preg_replace('/^(agency_|ucd_)/', '', $id);
            if (str_contains($raw, '___')) {
                [$targetDbName, $settingId] = explode('___', $raw, 2);
            } else {
                $settingId = $raw;
            }
        } else {
            $settingId = $id;
        }

        foreach ($allDbs as $dbName) {
            if (!$this->tryConnectDb($dbName)) {
                continue;
            }

            try {
                if (Schema::hasTable('wb_agency_settings')) {
                    if (($targetDbName && $dbName === $targetDbName && $settingId) || !$targetDbName) {
                        $setting = WbAgencySetting::find($settingId);
                        if ($setting) {
                            $setting->custom_domain_status = $newStatus;
                            $setting->save();
                            $targetDomain = $setting->custom_domain;
                        }
                    }
                }
                if (Schema::hasTable('user_custom_domains') && is_numeric($settingId)) {
                    if (($targetDbName && $dbName === $targetDbName) || !$targetDbName) {
                        $ucd = UserCustomDomain::find($settingId);
                        if ($ucd) {
                            $ucd->status = $newStatus;
                            $ucd->save();
                            $targetDomain = $ucd->requested_domain ?: $ucd->current_domain;
                        }
                    }
                }
            } catch (\Throwable $e) {}
        }

        if ($newStatus == 1 && $targetDomain) {
            $cleanDom = strtolower(trim(preg_replace('#^https?://#', '', $targetDomain)));
            $cleanDom = preg_replace('#^www\.#', '', $cleanDom);
            $cleanDom = rtrim($cleanDom, '/');

            foreach ($allDbs as $dbName) {
                if ($targetDbName && $dbName === $targetDbName) {
                    continue;
                }
                if (!$this->tryConnectDb($dbName)) {
                    continue;
                }
                try {
                    if (Schema::hasTable('wb_agency_settings')) {
                        $rows = WbAgencySetting::whereNotNull('custom_domain')->where('custom_domain', '!=', '')->get();
                        foreach ($rows as $r) {
                            $rDom = strtolower(trim(preg_replace('#^https?://#', '', $r->custom_domain ?? '')));
                            $rDom = preg_replace('#^www\.#', '', $rDom);
                            $rDom = rtrim($rDom, '/');
                            if ($rDom === $cleanDom || ($rDom && str_contains($rDom, $cleanDom))) {
                                $r->custom_domain = null;
                                $r->custom_domain_status = 0;
                                $r->save();
                            }
                        }
                    }
                } catch (\Throwable $e) {}
            }
        }

        $this->tryConnectDb($originalDb);

        $statusText = $newStatus == 1 ? 'Connected (Approved)' : ($newStatus == 2 ? 'Rejected' : 'Pending');
        return redirect()->back()->with('success', "Domain request updated to {$statusText} successfully!");
    }

    public function destroy(Request $request, $id)
    {
        $allDbs = $this->getAllDatabaseNames();
        $originalDb = config('database.connections.mysql.database');

        $targetDbName = null;
        $settingId = null;
        $isUcd = str_starts_with($id, 'ucd_');

        if (str_starts_with($id, 'agency_') || str_starts_with($id, 'ucd_')) {
            $raw = preg_replace('/^(agency_|ucd_)/', '', $id);
            if (str_contains($raw, '___')) {
                [$targetDbName, $settingId] = explode('___', $raw, 2);
            } else {
                $settingId = $raw;
            }
        } else {
            $settingId = $id;
        }

        $targetDomains = [];

        // Fetch domain string from target database or candidate databases
        $checkDbs = $targetDbName ? array_unique(array_merge([$targetDbName], $allDbs)) : $allDbs;
        foreach ($checkDbs as $dbName) {
            if (!$this->tryConnectDb($dbName)) continue;
            try {
                if (Schema::hasTable('wb_agency_settings') && $settingId) {
                    $s = WbAgencySetting::find($settingId);
                    if ($s && !empty($s->custom_domain)) {
                        $targetDomains[] = $s->custom_domain;
                    }
                }
                if (Schema::hasTable('user_custom_domains') && is_numeric($settingId)) {
                    $ucd = UserCustomDomain::find($settingId);
                    if ($ucd) {
                        if (!empty($ucd->requested_domain)) $targetDomains[] = $ucd->requested_domain;
                        if (!empty($ucd->current_domain)) $targetDomains[] = $ucd->current_domain;
                    }
                }
            } catch (\Throwable $e) {}
            if (!empty($targetDomains)) break;
        }

        $cleanDomains = [];
        foreach ($targetDomains as $td) {
            $c = strtolower(trim(preg_replace('#^https?://#', '', $td)));
            $c = preg_replace('#^www\.#', '', $c);
            $c = rtrim($c, '/');
            if (!empty($c)) {
                $cleanDomains[] = $c;
            }
        }
        $cleanDomains = array_unique($cleanDomains);

        // Permanently clear/delete from ALL databases
        foreach ($allDbs as $dbName) {
            if (!$this->tryConnectDb($dbName)) {
                continue;
            }

            try {
                if (Schema::hasTable('wb_agency_settings')) {
                    if ($targetDbName && $dbName === $targetDbName && $settingId) {
                        $setting = WbAgencySetting::find($settingId);
                        if ($setting) {
                            $setting->custom_domain = null;
                            $setting->custom_domain_status = 0;
                            $setting->save();
                        }
                    }

                    $rows = WbAgencySetting::whereNotNull('custom_domain')->where('custom_domain', '!=', '')->get();
                    foreach ($rows as $r) {
                        $rDom = strtolower(trim(preg_replace('#^https?://#', '', $r->custom_domain ?? '')));
                        $rDom = preg_replace('#^www\.#', '', $rDom);
                        $rDom = rtrim($rDom, '/');

                        $shouldClear = false;
                        if (empty($cleanDomains)) {
                            if ($targetDbName && $dbName === $targetDbName && $r->id == $settingId) {
                                $shouldClear = true;
                            }
                        } else {
                            foreach ($cleanDomains as $cd) {
                                if (!empty($rDom) && ($rDom === $cd || str_contains($rDom, $cd) || str_contains($cd, $rDom))) {
                                    $shouldClear = true;
                                    break;
                                }
                            }
                        }

                        if ($shouldClear) {
                            $r->custom_domain = null;
                            $r->custom_domain_status = 0;
                            $r->save();
                        }
                    }
                }

                if (Schema::hasTable('user_custom_domains')) {
                    if ($targetDbName && $dbName === $targetDbName && is_numeric($settingId)) {
                        UserCustomDomain::where('id', $settingId)->delete();
                    }

                    if (!empty($cleanDomains)) {
                        foreach ($cleanDomains as $cd) {
                            UserCustomDomain::where('requested_domain', 'LIKE', '%' . $cd . '%')
                                ->orWhere('current_domain', 'LIKE', '%' . $cd . '%')
                                ->delete();
                        }
                    }
                }
            } catch (\Throwable $e) {}
        }

        $this->tryConnectDb($originalDb);

        return redirect()->back()->with('success', 'Custom domain request permanently deleted from database!');
    }
}

