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
    private function getAllDatabaseNames(): array
    {
        $dbs = [];
        try {
            $rows = \Illuminate\Support\Facades\DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME NOT IN ('information_schema', 'mysql', 'performance_schema', 'sys')");
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
            try {
                \Illuminate\Support\Facades\DB::purge('mysql');
                config(['database.connections.mysql.database' => $dbName]);
                \Illuminate\Support\Facades\DB::reconnect('mysql');

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

                        $cleanDom = strtolower(trim(preg_replace('#^https?://#', '', $setting->custom_domain)));
                        $cleanDom = preg_replace('#^www\.#', '', $cleanDom);

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
            } catch (\Throwable $e) {
                // continue
            }
        }

        // Restore original DB connection
        try {
            \Illuminate\Support\Facades\DB::purge('mysql');
            config(['database.connections.mysql.database' => $originalDb]);
            \Illuminate\Support\Facades\DB::reconnect('mysql');
        } catch (\Throwable $e) {}

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
            try {
                \Illuminate\Support\Facades\DB::purge('mysql');
                config(['database.connections.mysql.database' => $dbName]);
                \Illuminate\Support\Facades\DB::reconnect('mysql');

                if (Schema::hasTable('wb_agency_settings')) {
                    if ($targetDbName && $dbName === $targetDbName && $settingId) {
                        $setting = WbAgencySetting::find($settingId);
                        if ($setting) {
                            $setting->custom_domain_status = $newStatus;
                            $setting->save();
                            $targetDomain = $setting->custom_domain;
                        }
                    } elseif ($settingId) {
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

        // If status == 1 (Connected), clear stale entries from other databases so dev DB doesn't shadow tenant DB
        if ($newStatus == 1 && $targetDomain) {
            $cleanDom = strtolower(trim(preg_replace('#^https?://#', '', $targetDomain)));
            $cleanDom = preg_replace('#^www\.#', '', $cleanDom);

            foreach ($allDbs as $dbName) {
                if ($targetDbName && $dbName === $targetDbName) {
                    continue;
                }
                try {
                    \Illuminate\Support\Facades\DB::purge('mysql');
                    config(['database.connections.mysql.database' => $dbName]);
                    \Illuminate\Support\Facades\DB::reconnect('mysql');

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

        // Restore original DB connection
        try {
            \Illuminate\Support\Facades\DB::purge('mysql');
            config(['database.connections.mysql.database' => $originalDb]);
            \Illuminate\Support\Facades\DB::reconnect('mysql');
        } catch (\Throwable $e) {}

        $statusText = $newStatus == 1 ? 'Connected (Approved)' : ($newStatus == 2 ? 'Rejected' : 'Pending');
        return redirect()->back()->with('success', "Domain request updated to {$statusText} successfully!");
    }
}

