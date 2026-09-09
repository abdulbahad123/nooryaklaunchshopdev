<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TenantDatabaseMiddleware
{
    /**
     * Handle incoming request and dynamically resolve dynamic database for White Label agencies.
     *
     * KEY FIX: On cPanel, the launchshop MySQL user (bazaarwa_launchshop) has NO access
     * to the Sass Admin DB (bazaarwa_Sass_admindb). We must connect to the Sass Admin DB
     * using its OWN credentials (SASS_ADMIN_DB_USER / SASS_ADMIN_DB_PASSWORD) stored in .env.
     *
     * IMPORTANT: This middleware must run AFTER StartSession in Kernel.php
     */
    public function handle($request, Closure $next)
    {
        if ($request->attributes->get('wb_custom_domain_resolved') || (app()->bound('wb_custom_domain_resolved') && app('wb_custom_domain_resolved'))) {
            return $next($request);
        }

        $host = $request->getHost();
        $normalizedHost = strtolower(preg_replace('/^www\./', '', $host));
        $isWbSubdomain = str_starts_with($normalizedHost, 'websitebuilder.') || str_starts_with($normalizedHost, 'website-builder.');

        $mainHosts = array_filter([
            'nooryak.in',
            '127.0.0.1',
            'localhost',
            'launchshop.in',
            'cockroachjantaparty.top',
            strtolower((string) env('WEBSITE_HOST', '')),
        ]);

        $cleanHost = preg_replace('/^(launchshop|checkout|app|www|websitebuilder|website-builder)\./i', '', $normalizedHost);

        // System infrastructure main hosts ONLY (excluding agency websitebuilder subdomains/domains)
        $isMainHostRequest = in_array($normalizedHost, $mainHosts);


        // 1. Check if explicit agency or tenant DB is passed in query param or session
        $agencySlug = $request->query('agency') ?? $request->query('tenant') ?? session('tenant_agency_slug');
        $tenantDb   = $request->query('tenant_db') ?? session('tenant_db');

        // Main host should never continue with stale tenant DB from old session.
        $hasExplicitTenantOverride = $request->query('agency') || $request->query('tenant') || $request->query('tenant_db');
        if ($isMainHostRequest && !$hasExplicitTenantOverride) {
            if (session()->has('tenant_db') || session()->has('tenant_agency_slug')) {
                Log::info("TenantMiddleware: Clearing stale tenant session on main host '{$normalizedHost}'.");
            }
            session()->forget(['tenant_db', 'tenant_agency_slug']);
            $agencySlug = null;
            $tenantDb = null;
        }

        // Guard: if session has a tenant_db, verify it still actually exists in MySQL
        if ($tenantDb && !$request->query('tenant_db')) {
            $exists = false;
            try {
                $rows   = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$tenantDb]);
                $exists = !empty($rows);
            } catch (\Throwable $e) {
                // ignore
            }
            if (!$exists) {
                Log::warning("TenantMiddleware: Stale session tenant_db '{$tenantDb}' — clearing.");
                session()->forget(['tenant_db', 'tenant_agency_slug']);
                $tenantDb   = null;
                $agencySlug = null;
            }
        }

        // 2. Extract subdomain (e.g. wibro.launchshop.nooryak.in -> wibro)
        if (!$agencySlug && !$tenantDb) {
            $parts = explode('.', $host);
            if (count($parts) >= 3 && !in_array(strtolower($parts[0]), ['www', 'app', 'launchshop', 'admin', 'websitebuilder', 'website-builder', 'localhost'])) {
                $agencySlug = $parts[0];
            }
        }

        $isWbRequest = $isWbSubdomain
            || $request->is('website-builder*')
            || $request->is('wb-*')
            || $request->is('admin/customers*')
            || $request->is('admin/wb-*')
            || $request->is('admin/templates*')
            || $request->is('admin/packages*');
        $targetProductSlug = $isWbRequest ? 'website-builder' : 'launchshop';

        // Main host should never continue with stale tenant DB from old session (unless it's a WB request or logged in WB customer)
        $hasExplicitTenantOverride = $request->query('agency') || $request->query('tenant') || $request->query('tenant_db');
        if ($isMainHostRequest && !$hasExplicitTenantOverride && !$isWbRequest && !session('wb_customer_email')) {
            if (session()->has('tenant_db') || session()->has('tenant_agency_slug')) {
                Log::info("TenantMiddleware: Clearing stale tenant session on main host '{$normalizedHost}'.");
            }
            session()->forget(['tenant_db', 'tenant_agency_slug']);
            $agencySlug = null;
            $tenantDb = null;
        }

        // Guard: if session has a tenant_db, verify it still actually exists in MySQL
        if ($tenantDb && !$request->query('tenant_db')) {
            $exists = false;
            try {
                $rows   = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$tenantDb]);
                $exists = !empty($rows);
            } catch (\Throwable $e) {
                // ignore
            }
            if (!$exists) {
                Log::warning("TenantMiddleware: Stale session tenant_db '{$tenantDb}' — clearing.");
                session()->forget(['tenant_db', 'tenant_agency_slug']);
                $tenantDb   = null;
                $agencySlug = null;
            }
        }

        // 2. Extract subdomain (e.g. wibro.launchshop.nooryak.in -> wibro)
        if (!$agencySlug && !$tenantDb) {
            $parts = explode('.', $host);
            if (count($parts) >= 3 && !in_array(strtolower($parts[0]), ['www', 'app', 'launchshop', 'admin', 'websitebuilder', 'website-builder', 'localhost'])) {
                $agencySlug = $parts[0];
            }
        }

        // 3. Resolve target tenant database candidates
        $candidates = [];

        if ($tenantDb) {
            $candidates[] = $tenantDb;
        } elseif ($agencySlug) {
            $agency = $this->findAgencyBySlug($agencySlug);
            if ($agency) {
                $dbFromPivot = $this->findAgencyProductDb($agency->id, $targetProductSlug);
                if ($dbFromPivot) {
                    $candidates[] = $dbFromPivot;
                }
                $candidates[] = $this->findExistingDbBySlug($agency->slug ?? '', $targetProductSlug);
                $candidates[] = $this->findExistingDbBySlug($agencySlug, $targetProductSlug);
            } else {
                $candidates[] = $this->findExistingDbBySlug($agencySlug, $targetProductSlug);
            }
        } else {
            $isMain = in_array($normalizedHost, $mainHosts);

            if ($isWbRequest) {
                // 1. Extract subdomain or custom domain slug from URL path (e.g. /website-builder/{subdomain})
                $pathSubdomain = $this->extractWbSubdomainFromPath($request);
                if ($pathSubdomain) {
                    $wbSubDb = $this->findDbByWbSubdomain($pathSubdomain);
                    if ($wbSubDb) {
                        $candidates[] = $wbSubDb;
                    }
                }

                // 2. Check if request inputs contain a customer email or login string (e.g. during login / checkout / OTP)
                $reqEmail = $request->input('customer_email') ?? $request->input('email') ?? $request->input('login');
                if ($reqEmail && filter_var($reqEmail, FILTER_VALIDATE_EMAIL)) {
                    $emailDb = $this->findDbByWbCustomerEmail($reqEmail);
                    if ($emailDb) {
                        $candidates[] = $emailDb;
                    }
                }

                // 3. Check if active session or logged-in WB customer email has an associated tenant DB
                $sessionEmail = session('wb_customer_email') ?? (function_exists('Auth') && \Illuminate\Support\Facades\Auth::guard('wb_customer')->check() ? \Illuminate\Support\Facades\Auth::guard('wb_customer')->user()->email : null);
                if ($sessionEmail) {
                    $sessionDb = $this->findDbByWbCustomerEmail($sessionEmail);
                    if ($sessionDb) {
                        $candidates[] = $sessionDb;
                    }
                }

                // 4. Check if session('tenant_db') is already set
                if (session('tenant_db')) {
                    $candidates[] = session('tenant_db');
                }
            }

            if (!$isMain) {
                $agency = $this->findAgencyByDomain($cleanHost) ?? $this->findAgencyByDomain($normalizedHost);
                if ($agency) {
                    $dbFromPivot = $this->findAgencyProductDb($agency->id, $targetProductSlug);
                    if ($dbFromPivot) {
                        $candidates[] = $dbFromPivot;
                    }
                    if (!empty($agency->slug)) {
                        $candidates[] = $this->findExistingDbBySlug($agency->slug, $targetProductSlug);
                    }
                    if (!empty($agency->name)) {
                        $candidates[] = $this->findExistingDbBySlug(\Illuminate\Support\Str::slug($agency->name), $targetProductSlug);
                    }
                    Log::info("TenantMiddleware: domain '{$cleanHost}' -> agency '{$agency->name}'");
                } else {
                    // Check if domain is a tenant custom domain (e.g. maturednature.com)
                    try {
                        $cDomainRow = DB::table('user_custom_domains')
                            ->where('status', 1)
                            ->where(function ($q) use ($host, $cleanHost) {
                                $q->where('requested_domain', $host)
                                  ->orWhere('requested_domain', $cleanHost)
                                  ->orWhere('requested_domain', 'www.' . $cleanHost)
                                  ->orWhere('requested_domain', 'http://' . $cleanHost)
                                  ->orWhere('requested_domain', 'https://' . $cleanHost);
                            })
                            ->first();

                        if ($cDomainRow) {
                            $userObj = DB::table('users')->where('id', $cDomainRow->user_id)->first();
                            if ($userObj && !empty($userObj->username)) {
                                $cdb = $this->findExistingDbBySlug($userObj->username);
                                if ($cdb) {
                                    $candidates[] = $cdb;
                                }
                            }
                        }
                    } catch (\Throwable $e) {
                        Log::warning("TenantMiddleware custom domain check error: " . $e->getMessage());
                    }

                    // Check if domain is registered in wb_agency_settings across databases
                    try {
                        $wbDb = $this->findDbByWbAgencyCustomDomain($cleanHost);
                        if ($wbDb) {
                            $candidates[] = $wbDb;
                        }
                    } catch (\Throwable $e) {
                        Log::warning("TenantMiddleware wb_agency_settings custom domain check error: " . $e->getMessage());
                    }

                    // Fallback for agency domains
                    if (str_contains($cleanHost, 'maturednature.com') || str_contains($host, 'maturednature.com')) {
                        $candidates[] = 'bazaarwa_ps_lane_launchshop';
                        $candidates[] = 'bazaarwa_ps_maturednature_launchshop';
                    }
                    Log::info("TenantMiddleware: Domain '{$cleanHost}'. Candidate count: " . count($candidates));
                }
            }
        }

        $candidates = array_unique(array_filter($candidates));

        // 4. Try connecting to candidates in order of priority
        $currentDb = config('database.connections.mysql.database');
        $origUser  = config('database.connections.mysql.username');
        $origUser  = config('database.connections.mysql.username');
        $origPass  = config('database.connections.mysql.password');

        $tenantUser = env('SASS_ADMIN_DB_USER', env('DB_USERNAME_admin', $origUser));
        $tenantPass = env('SASS_ADMIN_DB_PASS', env('DB_PASSWORD_admin', $origPass));

        $userPairs = array_values(array_filter([
            ['user' => $tenantUser, 'pass' => $tenantPass],
            ['user' => $origUser,   'pass' => $origPass],
        ], function ($item) {
            return !empty($item['user']);
        }));

        $switched = false;

        foreach ($candidates as $targetDb) {
            foreach ($userPairs as $pair) {
                $u = $pair['user'];
                $p = $pair['pass'];
                try {
                    DB::purge('mysql');
                    config([
                        'database.connections.mysql.database' => $targetDb,
                        'database.connections.mysql.username' => $u,
                        'database.connections.mysql.password' => $p,
                    ]);
                    DB::reconnect('mysql');
                    DB::connection('mysql')->getPdo(); // throws if DB inaccessible

                    session(['tenant_db' => $targetDb]);
                    if ($agencySlug) {
                        session(['tenant_agency_slug' => $agencySlug]);
                    }

                    // Auto-heal empty or un-provisioned tenant databases
                    try {
                        $checkTable  = $isWbRequest ? 'wb_customers' : 'packages';
                        $hasTable    = DB::select("SHOW TABLES LIKE '{$checkTable}'");
                        $hasLangs    = DB::select("SHOW TABLES LIKE 'languages'");
                        $hasAdmins   = DB::select("SHOW TABLES LIKE 'admins'");
                        $hasSettings = DB::select("SHOW TABLES LIKE 'basic_settings'");
                        $hasRoles    = DB::select("SHOW TABLES LIKE 'roles'");
                        $hasGateways = DB::select("SHOW TABLES LIKE 'payment_gateways'");

                        $adminCount = 0;
                        if (!empty($hasAdmins)) {
                            try {
                                $adminCount = DB::table('admins')->count();
                            } catch (\Throwable $e) {}
                        }

                        if (empty($hasTable) || empty($hasLangs) || empty($hasAdmins) || empty($hasSettings) || empty($hasRoles) || empty($hasGateways) || $adminCount === 0) {
                            Log::info("TenantMiddleware: Tenant DB '{$targetDb}' is missing core tables (roles/payment_gateways/admins) or default admin. Auto-importing clean schema template...");
                            $this->autoImportCleanSchemaTemplate($targetProductSlug);
                        }
                    } catch (\Throwable $checkEx) {
                        Log::warning("TenantMiddleware: Table check/import failed for '{$targetDb}': " . $checkEx->getMessage());
                    }

                    Log::info("TenantMiddleware: Switched to tenant DB '{$targetDb}' as user '{$u}'");
                    $switched = true;
                    break 2;
                } catch (\Throwable $e) {
                    Log::warning("TenantMiddleware: Candidate DB '{$targetDb}' connection failed as user '{$u}': " . $e->getMessage());
                    // Restore main DB connection before trying next candidate
                    try {
                        DB::purge('mysql');
                        config([
                            'database.connections.mysql.database' => $currentDb,
                            'database.connections.mysql.username' => $origUser,
                            'database.connections.mysql.password' => $origPass,
                        ]);
                        DB::reconnect('mysql');
                    } catch (\Throwable $restoreEx) {
                        Log::error("TenantMiddleware: Failed to restore main DB: " . $restoreEx->getMessage());
                    }
                }
            }
        }

        return $next($request);
    }

    /**
     * Get a PDO connection to the Sass Admin DB using its own credentials.
     *
     * On cPanel, the launchshop MySQL user (bazaarwa_launchshop) has NO cross-DB access.
     * We connect using the Sass Admin DB's own credentials.
     *
     * Reads from launchshop .env (supports two naming conventions):
     *   SASS_ADMIN_DB=bazaarwa_Sass_admindb       ← DB name
     *   DB_USERNAME_admin=bazaarwa_sass_admindb   ← DB user  (existing key)
     *   DB_PASSWORD_admin=<password>              ← DB pass  (existing key)
     *
     *   OR alternatively:
     *   SASS_ADMIN_DB_USER=bazaarwa_sass_admindb
     *   SASS_ADMIN_DB_PASS=<password>
     */
    protected function getSassAdminPdo(): ?\PDO
    {
        // DB name: SASS_ADMIN_DB takes priority, fallback to DB_DATABASE_admin
        $dbName = env('SASS_ADMIN_DB') ?: env('DB_DATABASE_admin');

        // DB user: check both naming conventions
        $dbUser = env('SASS_ADMIN_DB_USER') ?: env('DB_USERNAME_admin');

        // DB password: check both naming conventions
        $dbPass = env('SASS_ADMIN_DB_PASS') ?: env('DB_PASSWORD_admin', '');

        $dbHost = env('SASS_ADMIN_DB_HOST', env('DB_HOST', '127.0.0.1'));
        $dbPort = env('SASS_ADMIN_DB_PORT', env('DB_PORT', '3306'));

        if (!$dbName || !$dbUser) {
            return null;
        }

        static $pdo = null;
        if ($pdo !== null) {
            return $pdo;
        }

        $dbNameCandidates = array_values(array_unique(array_filter([
            $dbName,
            strtolower($dbName),
            'bazaarwa_sass_admindb',
            'bazaarwa_Sass_admindb',
        ])));

        foreach ($dbNameCandidates as $candDb) {
            try {
                $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$candDb};charset=utf8mb4";
                $pdo = new \PDO($dsn, $dbUser, $dbPass, [
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                    \PDO::ATTR_TIMEOUT            => 5,
                ]);
                return $pdo;
            } catch (\Throwable $e) {
                Log::debug("TenantMiddleware: Cannot connect to Sass Admin DB candidate '{$candDb}': " . $e->getMessage());
            }
        }

        return null;
    }

    /**
     * Run a SELECT query against the Sass Admin DB.
     * Uses dedicated PDO if credentials are set, otherwise falls back to cross-DB via main connection.
     */
    protected function sassQuery(string $sql, array $bindings = []): array
    {
        // Try dedicated Sass Admin connection first (required on cPanel)
        $pdo = $this->getSassAdminPdo();
        if ($pdo) {
            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute($bindings);
                return $stmt->fetchAll(\PDO::FETCH_OBJ);
            } catch (\Throwable $e) {
                Log::debug("TenantMiddleware: sassQuery PDO error: " . $e->getMessage());
                return [];
            }
        }

        // Fallback: use main DB connection with cross-DB table prefix (works locally)
        $sassDb = env('SASS_ADMIN_DB', 'sass_admin');
        // Replace table names with prefixed versions in the SQL
        $sql = preg_replace('/\bFROM\s+agencies\b/i',         "FROM {$sassDb}.agencies",         $sql);
        $sql = preg_replace('/\bFROM\s+agency_products\b/i',  "FROM {$sassDb}.agency_products",  $sql);
        $sql = preg_replace('/\bFROM\s+products\b/i',         "FROM {$sassDb}.products",          $sql);
        $sql = preg_replace('/\bJOIN\s+agencies\b/i',         "JOIN {$sassDb}.agencies",          $sql);
        $sql = preg_replace('/\bJOIN\s+agency_products\b/i',  "JOIN {$sassDb}.agency_products",   $sql);
        $sql = preg_replace('/\bJOIN\s+products\b/i',         "JOIN {$sassDb}.products",           $sql);
        $sql = preg_replace('/\bSHOW COLUMNS FROM\s+/i',      "SHOW COLUMNS FROM {$sassDb}.",      $sql);

        try {
            return DB::select($sql, $bindings);
        } catch (\Throwable $e) {
            Log::debug("TenantMiddleware: sassQuery fallback error: " . $e->getMessage());
            return [];
        }
    }

    protected function findAgencyByDomain(string $cleanHost): ?object
    {
        $rootHost = preg_replace('/^(launchshop|app|www)\./i', '', $cleanHost);

        $sql = "SELECT id, name, slug, custom_domain FROM agencies
                WHERE custom_domain = ?
                   OR custom_domain = ?
                   OR custom_domain = ?
                   OR custom_domain = ?
                   OR custom_domain = ?
                   OR custom_domain = ?
                   OR custom_domain LIKE ?
                LIMIT 1";

        $rows = $this->sassQuery($sql, [
            $cleanHost,
            $rootHost,
            "https://{$cleanHost}",
            "http://{$cleanHost}",
            "https://{$rootHost}",
            "http://{$rootHost}",
            "%{$rootHost}%",
        ]);

        return $rows[0] ?? null;
    }

    protected function findAgencyBySlug(string $slug): ?object
    {
        $rows = $this->sassQuery(
            "SELECT id, name, slug, custom_domain FROM agencies WHERE slug = ? OR name LIKE ? LIMIT 1",
            [$slug, "%{$slug}%"]
        );

        return $rows[0] ?? null;
    }

    protected function findAgencyProductDb(int $agencyId, string $productSlug = 'launchshop'): ?string
    {
        // Check db_name column exists
        $cols = $this->sassQuery("SHOW COLUMNS FROM agency_products LIKE 'db_name'");
        if (empty($cols)) {
            Log::debug("TenantMiddleware: db_name column missing in agency_products.");
            return null;
        }

        $slugs = [$productSlug];
        if (in_array($productSlug, ['website-builder', 'websitebuilder'])) {
            $slugs = ['website-builder', 'websitebuilder'];
        }

        $placeholders = implode(',', array_fill(0, count($slugs), '?'));
        $params = array_merge([$agencyId], $slugs);

        $rows = $this->sassQuery(
            "SELECT ap.db_name
             FROM agency_products ap
             JOIN products p ON p.id = ap.product_id
             WHERE ap.agency_id = ?
               AND p.slug IN ({$placeholders})
               AND ap.db_name IS NOT NULL
               AND ap.db_name != ''
             LIMIT 1",
            $params
        );

        return $rows[0]->db_name ?? null;
    }

    protected function findExistingDbBySlug(string $slug, string $productSlug = 'launchshop'): ?string
    {
        if (empty($slug)) {
            return null;
        }

        $cpanelUser = env('CPANEL_USER', 'bazaarwa');
        $fullSlug   = str_replace('-', '_', strtolower($slug));
        $shortSlug  = substr($fullSlug, 0, 16);
        $isWb       = in_array($productSlug, ['website-builder', 'websitebuilder']);
        $prodSuffix = $isWb ? 'website_builder' : 'launchshop';

        $candidates = array_unique([
            "{$cpanelUser}_ps_{$fullSlug}_{$prodSuffix}",
            "{$cpanelUser}_ps_{$shortSlug}_{$prodSuffix}",
            "{$cpanelUser}_{$fullSlug}_{$prodSuffix}",
            "{$cpanelUser}_{$shortSlug}_{$prodSuffix}",
            "{$cpanelUser}_{$fullSlug}_websitebuilder",
            "{$cpanelUser}_{$shortSlug}_websitebuilder",
            "bazaarwa_ps_{$fullSlug}_{$prodSuffix}",
            "bazaarwa_ps_{$shortSlug}_{$prodSuffix}",
            "bazaarwa_{$fullSlug}_{$prodSuffix}",
            "bazaarwa_{$shortSlug}_{$prodSuffix}",
            "bazaarwa_{$fullSlug}_websitebuilder",
            "bazaarwa_{$shortSlug}_websitebuilder",
        ]);

        if ($isWb) {
            $candidates[] = "{$cpanelUser}_ps_{$fullSlug}_launchshop";
            $candidates[] = "{$cpanelUser}_ps_{$shortSlug}_launchshop";
            $candidates[] = "bazaarwa_ps_{$fullSlug}_launchshop";
            $candidates[] = "bazaarwa_ps_{$shortSlug}_launchshop";
        }

        $allCandidates = [];
        foreach ($candidates as $cand) {
            $allCandidates[] = $cand;
            $allCandidates[] = substr($cand, 0, 32);
        }
        $candidates = array_unique(array_filter($allCandidates));

        $currentDb = config('database.connections.mysql.database');

        foreach ($candidates as $cand) {
            try {
                // Try a direct connection — avoids INFORMATION_SCHEMA privilege issue on cPanel
                DB::purge('mysql');
                config(['database.connections.mysql.database' => $cand]);
                DB::reconnect('mysql');
                DB::connection('mysql')->getPdo();
                // Success — restore original connection and return the found DB
                DB::purge('mysql');
                config(['database.connections.mysql.database' => $currentDb]);
                DB::reconnect('mysql');
                return $cand;
            } catch (\Throwable $e) {
                // DB doesn't exist or no access — try next candidate
            }
        }

        // Restore original connection
        try {
            DB::purge('mysql');
            config(['database.connections.mysql.database' => $currentDb]);
            DB::reconnect('mysql');
        } catch (\Throwable $e) {
            // ignore
        }

        return null;
    }

    protected function findDbByWbAgencyCustomDomain(string $cleanHost): ?string
    {
        $currentDb = config('database.connections.mysql.database');
        $allDbs = $this->getAllCandidateDatabases();
        $dedicatedDbs = array_values(array_diff($allDbs, [$currentDb]));
        $allDbs = array_merge($dedicatedDbs, [$currentDb]);

        // First pass: find a DB where this domain is CONNECTED (status=1) — authoritative match
        $connectedDb = null;
        $anyMatchDb  = null;

        foreach ($allDbs as $dbName) {
            try {
                DB::purge('mysql');
                config(['database.connections.mysql.database' => $dbName]);
                DB::reconnect('mysql');

                if (\Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings')) {
                    $rows = DB::table('wb_agency_settings')
                        ->whereNotNull('custom_domain')
                        ->where('custom_domain', '!=', '')
                        ->get();

                    foreach ($rows as $row) {
                        $stored = strtolower(trim(preg_replace('#^https?://#', '', $row->custom_domain ?? '')));
                        $stored = preg_replace('#^www\.#', '', $stored);
                        $stored = explode('/', $stored)[0];
                        $stored = preg_replace('/:\d+$/', '', $stored);

                        if ($stored === $cleanHost) {
                            if ((int)($row->custom_domain_status ?? 0) === 1) {
                                // Connected record wins immediately
                                $connectedDb = $dbName;
                                break 2;
                            }
                            if ($anyMatchDb === null) {
                                $anyMatchDb = $dbName;
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                // continue searching next DB
            }
        }

        // Restore original connection before returning
        try {
            DB::purge('mysql');
            config(['database.connections.mysql.database' => $currentDb]);
            DB::reconnect('mysql');
        } catch (\Throwable $e) {}

        return $connectedDb ?? $anyMatchDb ?? null;
    }

    protected function extractWbSubdomainFromPath($request): ?string
    {
        $path = ltrim($request->getPathInfo(), '/');
        if (!str_starts_with($path, 'website-builder')) {
            return null;
        }

        $segments = explode('/', $path);
        $sub = strtolower(trim($segments[1] ?? ''));
        if ($sub === '') {
            return null;
        }

        $reserved = [
            'agency-admin', 'admin', 'login', 'logout', 'checkout', 'templates', 'pricing',
            'register', 'user', 'secret-login', 'midtrans', 'check-payment', 'process-checkout',
            'process-login', 'send-otp', 'verify-otp', 'process-template-purchase',
        ];

        if (in_array($sub, $reserved, true)) {
            return null;
        }

        return $sub;
    }

    protected function findDbByWbSubdomain(string $subdomain): ?string
    {
        $cleanSub = strtolower(trim(preg_replace('#^https?://#', '', $subdomain)));
        $cleanSub = preg_replace('#^www\.#', '', $cleanSub);
        $cleanSub = explode('/', $cleanSub)[0];
        $cleanSub = preg_replace('/:\d+$/', '', $cleanSub);

        if (empty($cleanSub)) {
            return null;
        }

        $allDbs = $this->getAllCandidateDatabases();
        $currentDb = config('database.connections.mysql.database');

        $connectedDb      = null;
        $tenantCustomerDb = null;
        $anyCustomerDb    = null;
        $anyDomainDb      = null;

        foreach ($allDbs as $dbName) {
            try {
                DB::purge('mysql');
                config(['database.connections.mysql.database' => $dbName]);
                DB::reconnect('mysql');

                $hasWbSettings  = \Illuminate\Support\Facades\Schema::hasTable('wb_agency_settings');
                $hasWbCustomers = \Illuminate\Support\Facades\Schema::hasTable('wb_customers');

                // Pass 1: Check wb_agency_settings for custom domain match
                if ($hasWbSettings) {
                    $rows = DB::table('wb_agency_settings')
                        ->whereNotNull('custom_domain')
                        ->where('custom_domain', '!=', '')
                        ->get();

                    foreach ($rows as $row) {
                        $stored = strtolower(trim(preg_replace('#^https?://#', '', $row->custom_domain ?? '')));
                        $stored = preg_replace('#^www\.#', '', $stored);
                        $stored = explode('/', $stored)[0];
                        $stored = preg_replace('/:\d+$/', '', $stored);

                        if ($stored === $cleanSub || (function_exists('wbHostsMatch') && wbHostsMatch($row->custom_domain ?? '', $cleanSub))) {
                            if ((int)($row->custom_domain_status ?? 0) === 1) {
                                $connectedDb = $dbName;
                                break 2;
                            }
                            if ($anyDomainDb === null) {
                                $anyDomainDb = $dbName;
                            }
                        }
                    }
                }

                // Pass 2: Check wb_customers for subdomain or email match
                if ($hasWbCustomers) {
                    $c = DB::table('wb_customers')
                        ->where('subdomain', $cleanSub)
                        ->orWhere('email', $cleanSub)
                        ->first();
                    if ($c) {
                        // Check if this DB has an active connected agency custom domain setting for this customer
                        $hasConn = false;
                        if ($hasWbSettings) {
                            $hasConn = DB::table('wb_agency_settings')
                                ->where('customer_id', $c->id)
                                ->where('custom_domain_status', 1)
                                ->exists();
                        }
                        if ($hasConn) {
                            $connectedDb = $dbName;
                            break;
                        }

                        // Prefer dedicated tenant database (not the main platform DB)
                        if ($dbName !== $currentDb && $tenantCustomerDb === null) {
                            $tenantCustomerDb = $dbName;
                        } elseif ($anyCustomerDb === null) {
                            $anyCustomerDb = $dbName;
                        }
                    }
                }
            } catch (\Throwable $e) {
                // continue searching next DB
            }
        }

        $this->restoreDbConnection($currentDb);

        return $connectedDb ?? $tenantCustomerDb ?? $anyCustomerDb ?? $anyDomainDb ?? null;
    }

    protected function findDbByWbCustomerEmail(string $email): ?string
    {
        $cleanEmail = strtolower(trim($email));
        if (empty($cleanEmail)) {
            return null;
        }

        $allDbs = $this->getAllCandidateDatabases();
        $currentDb = config('database.connections.mysql.database');

        foreach ($allDbs as $dbName) {
            try {
                DB::purge('mysql');
                config(['database.connections.mysql.database' => $dbName]);
                DB::reconnect('mysql');

                if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                    $c = DB::table('wb_customers')->where('email', $cleanEmail)->first();
                    if ($c) {
                        $this->restoreDbConnection($currentDb);
                        return $dbName;
                    }
                }
            } catch (\Throwable $e) {
                // continue
            }
        }

        $this->restoreDbConnection($currentDb);
        return null;
    }

    protected function getAllCandidateDatabases(): array
    {
        $allDbs = [];
        try {
            $rows = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME NOT IN ('information_schema', 'mysql', 'performance_schema', 'sys')");
            foreach ($rows as $r) {
                if (!empty($r->SCHEMA_NAME)) {
                    $allDbs[] = $r->SCHEMA_NAME;
                }
            }
        } catch (\Throwable $e) {}

        try {
            $pdo = $this->getSassAdminPdo();
            if ($pdo) {
                $cols = $pdo->query("SHOW COLUMNS FROM agency_products LIKE 'db_name'");
                if ($cols && $cols->fetch()) {
                    $sql = "SELECT DISTINCT db_name FROM agency_products WHERE db_name IS NOT NULL AND db_name != ''";
                    foreach ($pdo->query($sql)->fetchAll(\PDO::FETCH_OBJ) as $row) {
                        if (!empty($row->db_name)) {
                            $allDbs[] = $row->db_name;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {}

        $currentDb = config('database.connections.mysql.database');
        if ($currentDb) {
            $allDbs[] = $currentDb;
        }

        return array_values(array_unique(array_filter($allDbs)));
    }

    protected function restoreDbConnection(string $targetDb): void
    {
        try {
            DB::purge('mysql');
            config(['database.connections.mysql.database' => $targetDb]);
            DB::reconnect('mysql');
        } catch (\Throwable $e) {}
    }

    /**
     * Auto-import the clean schema template into an empty tenant database.
     */
    private function autoImportCleanSchemaTemplate(string $productSlug = 'launchshop'): void
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');

        $isWb = in_array($productSlug, ['website-builder', 'websitebuilder']);
        $templateFile = $isWb ? 'website_builder_clean_template.sql' : 'launchshop_clean_template.sql';

        $paths = [
            database_path("schema/{$templateFile}"),
            base_path("../Sass_admin/database/schema/{$templateFile}"),
            "/home/bazaarwa/public_html/database/schema/{$templateFile}",
            "/home/bazaarwa/launchshop.in/database/schema/{$templateFile}",
        ];

        $schemaFile = null;
        foreach ($paths as $path) {
            if (file_exists($path)) {
                $schemaFile = $path;
                break;
            }
        }

        if (!$schemaFile) {
            Log::warning("TenantMiddleware: {$templateFile} not found for auto-import.");
            return;
        }

        try {
            $pdo = DB::connection('mysql')->getPdo();
            $pdo->exec('SET FOREIGN_KEY_CHECKS=0;');

            $sql = file_get_contents($schemaFile);
            $statements = preg_split('/;\s*[\r\n]+/', $sql);

            foreach ($statements as $stmt) {
                $stmt = trim($stmt);
                if (!empty($stmt)) {
                    try {
                        $pdo->exec($stmt);
                    } catch (\Throwable $ex) {
                        // Ignore existing table or duplicate key errors during auto-import
                    }
                }
            }

            $pdo->exec('SET FOREIGN_KEY_CHECKS=1;');

            // Ensure default admin account exists
            try {
                $adminCount = DB::table('admins')->count();
                if ($adminCount === 0) {
                    DB::table('admins')->insert([
                        'username'   => 'admin',
                        'email'      => 'admin@websitebuilder.com',
                        'first_name' => 'Admin',
                        'last_name'  => 'User',
                        'password'   => \Illuminate\Support\Facades\Hash::make('password'),
                        'status'     => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    Log::info("TenantMiddleware: Created default admin account in tenant DB.");
                }
            } catch (\Throwable $adminEx) {
                Log::warning("TenantMiddleware: Failed to create default admin in tenant DB: " . $adminEx->getMessage());
            }

            Log::info("TenantMiddleware: Successfully auto-imported {$templateFile} into tenant DB.");
        } catch (\Throwable $e) {
            Log::error("TenantMiddleware: Auto-import failed: " . $e->getMessage());
        }
    }
}
