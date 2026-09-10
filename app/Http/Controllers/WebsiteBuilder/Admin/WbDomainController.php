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

        if (str_starts_with($id, 'agency_')) {
            $raw = str_replace('agency_', '', $id);
            if (str_contains($raw, '___')) {
                [$targetDbName, $settingId] = explode('___', $raw, 2);
            } else {
                $settingId = $raw;
            }
        }

        foreach ($allDbs as $dbName) {
            if (!$this->tryConnectDb($dbName)) {
                continue;
            }

            try {
                if (Schema::hasTable('wb_agency_settings')) {
                    if (($targetDbName && $dbName === $targetDbName && $settingId) || $settingId) {
                        $setting = WbAgencySetting::find($settingId);
                        if ($setting) {
                            $setting->custom_domain_status = $newStatus;
                            $setting->save();
                            $targetDomain = $setting->custom_domain;
                        }
                    }
                }
            } catch (\Throwable $e) {}
        }

        if ($newStatus == 1 && $targetDomain) {
            $cleanDom = strtolower(trim(preg_replace('#^https?://#', '', $targetDomain)));
            $cleanDom = preg_replace('#^www\.#', '', $cleanDom);

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
                            if ($rDom === $cleanDom) {
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

        if (str_starts_with($id, 'agency_')) {
            $raw = str_replace('agency_', '', $id);
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
                if (Schema::hasTable('wb_agency_settings') && $settingId) {
                    $setting = WbAgencySetting::find($settingId);
                    if ($setting) {
                        $setting->custom_domain = null;
                        $setting->custom_domain_status = 0;
                        $setting->save();
                    }
                }
                if (Schema::hasTable('user_custom_domains') && is_numeric($settingId)) {
                    UserCustomDomain::where('id', $settingId)->delete();
                }
            } catch (\Throwable $e) {}
        }

        $this->tryConnectDb($originalDb);

        return redirect()->back()->with('success', 'Custom domain request permanently deleted from database!');
    }
}

