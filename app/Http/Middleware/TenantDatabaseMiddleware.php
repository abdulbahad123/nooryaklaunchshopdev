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
        $host = $request->getHost();
        $normalizedHost = strtolower(preg_replace('/^www\./', '', $host));
        $cleanHost = preg_replace('/^(launchshop|checkout|app|www)\./i', '', $normalizedHost);

        $mainHosts = array_filter([
            'nooryak.in',
            '127.0.0.1',
            'localhost',
            'launchshop.in',
            'cockroachjantaparty.top',
            strtolower((string) env('WEBSITE_HOST', '')),
        ]);

        $isMainHostRequest = in_array($cleanHost, $mainHosts)
            || in_array($normalizedHost, $mainHosts);


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
            if (count($parts) >= 3 && !in_array(strtolower($parts[0]), ['www', 'app', 'launchshop', 'admin', 'localhost'])) {
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
                $dbFromPivot = $this->findAgencyProductDb($agency->id);
                if ($dbFromPivot) {
                    $candidates[] = $dbFromPivot;
                }
                $candidates[] = $this->findExistingDbBySlug($agency->slug ?? '');
                $candidates[] = $this->findExistingDbBySlug($agencySlug);
            } else {
                $candidates[] = $this->findExistingDbBySlug($agencySlug);
            }
        } else {
            $cleanHost = preg_replace('/^(launchshop|checkout|app|www)\./i', '', $host);

            // ── Main / infrastructure hosts — never switch databases ───────────
            // Add any domain here that should always use the main DB connection.
            $mainHosts = [
                'nooryak.in',
                '127.0.0.1',
                'localhost',
                'launchshop.in',
                'cockroachjantaparty.top',
                env('WEBSITE_HOST', ''),
            ];
            $isMain = in_array($cleanHost, $mainHosts)
                   || in_array($host, $mainHosts);


            if (!$isMain) {
                $agency = $this->findAgencyByDomain($cleanHost);
                if ($agency) {
                    $dbFromPivot = $this->findAgencyProductDb($agency->id);
                    if ($dbFromPivot) {
                        $candidates[] = $dbFromPivot;
                    }
                    if (!empty($agency->slug)) {
                        $candidates[] = $this->findExistingDbBySlug($agency->slug);
                    }
                    if (!empty($agency->name)) {
                        $candidates[] = $this->findExistingDbBySlug(\Illuminate\Support\Str::slug($agency->name));
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
                        $hasPackages = DB::select("SHOW TABLES LIKE 'packages'");
                        $hasUsers    = DB::select("SHOW TABLES LIKE 'users'");
                        if (empty($hasPackages) || empty($hasUsers)) {
                            Log::info("TenantMiddleware: Tenant DB '{$targetDb}' is missing core tables (packages/users). Auto-importing clean schema template...");
                            $this->autoImportCleanSchemaTemplate();
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

    protected function findAgencyProductDb(int $agencyId): ?string
    {
        // Check db_name column exists
        $cols = $this->sassQuery("SHOW COLUMNS FROM agency_products LIKE 'db_name'");
        if (empty($cols)) {
            Log::debug("TenantMiddleware: db_name column missing in agency_products.");
            return null;
        }

        $rows = $this->sassQuery(
            "SELECT ap.db_name
             FROM agency_products ap
             JOIN products p ON p.id = ap.product_id
             WHERE ap.agency_id = ?
               AND p.slug = 'launchshop'
               AND ap.db_name IS NOT NULL
               AND ap.db_name != ''
             LIMIT 1",
            [$agencyId]
        );

        return $rows[0]->db_name ?? null;
    }

    protected function findExistingDbBySlug(string $slug): ?string
    {
        if (empty($slug)) {
            return null;
        }

        $cpanelUser = env('CPANEL_USER', 'bazaarwa');
        $fullSlug   = str_replace('-', '_', strtolower($slug));
        $shortSlug  = substr($fullSlug, 0, 16);

        $candidates = array_unique([
            "{$cpanelUser}_ps_{$fullSlug}_launchshop",
            "{$cpanelUser}_ps_{$shortSlug}_launchshop",
            "{$cpanelUser}_{$fullSlug}_launchshop",
            "{$cpanelUser}_{$shortSlug}_launchshop",
            "bazaarwa_ps_{$fullSlug}_launchshop",
            "bazaarwa_ps_{$shortSlug}_launchshop",
            "bazaarwa_{$fullSlug}_launchshop",
            "bazaarwa_{$shortSlug}_launchshop",
        ]);

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

    /**
     * Auto-import the clean schema template into an empty tenant database.
     */
    private function autoImportCleanSchemaTemplate(): void
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');

        $paths = [
            database_path('schema/launchshop_clean_template.sql'),
            base_path('../Sass_admin/database/schema/launchshop_clean_template.sql'),
            '/home/bazaarwa/public_html/database/schema/launchshop_clean_template.sql',
            '/home/bazaarwa/launchshop.in/database/schema/launchshop_clean_template.sql',
        ];

        $schemaFile = null;
        foreach ($paths as $path) {
            if (file_exists($path)) {
                $schemaFile = $path;
                break;
            }
        }

        if (!$schemaFile) {
            Log::warning("TenantMiddleware: launchshop_clean_template.sql not found for auto-import.");
            return;
        }

        try {
            $pdo = DB::connection('mysql')->getPdo();
            $pdo->exec('SET FOREIGN_KEY_CHECKS=0;');

            $sql = file_get_contents($schemaFile);
            $statements = preg_split('/;\r?\n(?=(?:CREATE TABLE|INSERT INTO|DROP TABLE|LOCK TABLES|UNLOCK TABLES|ALTER TABLE|\/\*!|--))/i', $sql);

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
            Log::info("TenantMiddleware: Successfully auto-imported clean schema into tenant DB.");
        } catch (\Throwable $e) {
            Log::error("TenantMiddleware: Auto-import failed: " . $e->getMessage());
        }
    }
}
