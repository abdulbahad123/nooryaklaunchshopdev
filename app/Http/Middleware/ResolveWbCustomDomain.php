<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Apex custom domains (e.g. funkiddoz.in) CNAME to the main app host, but the
 * launched agency site often lives in an agency website-builder tenant database.
 * Routes are compiled against the default DB, so this runs globally (before
 * routing), switches to the owning database, and rewrites the URI onto the
 * always-registered /website-builder/{subdomain} theme routes.
 */
class ResolveWbCustomDomain
{
    public function handle(Request $request, Closure $next)
    {
        $host = strtolower((string) $request->getHost());
        $host = preg_replace('/:\d+$/', '', $host);
        $cleanHost = function_exists('normalizeWbHost')
            ? normalizeWbHost($host)
            : preg_replace('/^www\./', '', $host);

        if ($cleanHost === '' || $this->isInfrastructureHost($cleanHost, $host) || $request->attributes->get('is_launchshop_custom_domain') || (app()->bound('is_launchshop_custom_domain') && app('is_launchshop_custom_domain')) || $this->isLaunchShopCustomDomain($cleanHost, $host)) {
            return $next($request);
        }

        $path = ltrim($request->getPathInfo(), '/');
        if ($this->isPublicFile($path)) {
            return $next($request);
        }

        $resolved = $this->resolve($cleanHost);
        if (!$resolved) {
            return $next($request);
        }

        $request->attributes->set('wb_custom_domain_resolved', true);
        $request->attributes->set('wb_custom_domain_subdomain', $resolved['subdomain']);
        app()->instance('wb_custom_domain_resolved', true);

        if (str_starts_with($path, 'website-builder')) {
            return $next($request);
        }

        $this->rewriteToWebsiteBuilder($request, $resolved['subdomain'], $path);

        return $next($request);
    }

    private function isInfrastructureHost(string $cleanHost, string $host): bool
    {
        if (str_starts_with($host, 'websitebuilder.') || str_starts_with($host, 'website-builder.')) {
            return true;
        }

        $appHost = strtolower(parse_url((string) env('APP_URL', ''), PHP_URL_HOST) ?: '');

        $mainHosts = array_values(array_unique(array_filter([
            strtolower((string) env('WEBSITE_HOST', '')),
            $appHost,
            'localhost',
            '127.0.0.1',
        ])));

        if (in_array($cleanHost, $mainHosts, true)) {
            return true;
        }

        foreach ($mainHosts as $base) {
            if ($base !== '' && str_ends_with($cleanHost, '.' . $base)) {
                return true;
            }
        }

        return false;
    }

    private function isPublicFile(string $path): bool
    {
        if ($path === '') {
            return false;
        }
        $full = public_path($path);
        return is_file($full);
    }

    private function resolve(string $cleanHost): ?array
    {
        $original = [
            'database' => config('database.connections.mysql.database'),
            'username' => config('database.connections.mysql.username'),
            'password' => config('database.connections.mysql.password'),
        ];

        $allTenantDbs = $this->websiteBuilderTenantDatabases();
        $dedicatedDbs = array_values(array_diff($allTenantDbs, [$original['database']]));
        $candidates   = array_values(array_unique(array_filter(array_merge(
            $dedicatedDbs,
            [$original['database']]
        ))));

        // Two-pass scan: check ALL databases before deciding.
        // A connected (status=1) record in any DB beats a pending record
        // in the first DB found (prevents dev/test DBs from shadowing prod).
        $connectedResult = null;
        $anyResult       = null;

        foreach ($candidates as $dbName) {
            $match = $this->matchInDatabase($dbName, $cleanHost, $original);
            if (!$match) {
                continue;
            }
            if ($match['is_connected']) {
                // Authoritative: connected record wins immediately
                $connectedResult = $match;
                break;
            }
            if (!$anyResult) {
                $anyResult = $match;
            }
        }

        $result = $connectedResult ?? $anyResult;

        if ($result) {
            // Ensure the DB connection is left on the winning database
            if (config('database.connections.mysql.database') !== $result['database']) {
                try {
                    DB::purge('mysql');
                    config(['database.connections.mysql.database' => $result['database']]);
                    DB::reconnect('mysql');
                } catch (\Throwable $e) {
                    // ignore
                }
            }
            return $result;
        }

        $this->restoreConnection($original);
        return null;
    }

    /**
     * Check a single database for a matching custom domain record.
     * Returns an array with keys: database, subdomain, setting, is_connected.
     * Returns null if no matching row found or DB inaccessible.
     * NOTE: Does NOT stay connected to $dbName — restores $original connection
     *       so the caller can decide which DB to commit to after all scans.
     */
    private function matchInDatabase(string $dbName, string $cleanHost, array $original): ?array
    {
        try {
            DB::purge('mysql');
            config(['database.connections.mysql.database' => $dbName]);
            DB::reconnect('mysql');
            DB::connection('mysql')->getPdo();

            if (!Schema::hasTable('wb_agency_settings')) {
                $this->restoreConnection($original);
                return null;
            }

            $rows = DB::table('wb_agency_settings')
                ->whereNotNull('custom_domain')
                ->where('custom_domain', '!=', '')
                ->orderBy('updated_at', 'desc')
                ->get();

            $any       = null;
            $connected = null;
            foreach ($rows as $row) {
                if (!$this->hostsMatch($row->custom_domain ?? '', $cleanHost)) {
                    continue;
                }
                if ($any === null) {
                    $any = $row;
                }
                if ((int)($row->custom_domain_status ?? 0) === 1) {
                    $connected = $row;
                    break;
                }
            }

            $setting = $connected ?: $any;
            if (!$setting) {
                // No match in this DB — restore and return null
                $this->restoreConnection($original);
                return null;
            }

            // Resolve subdomain while still connected to this DB
            $subdomain   = $cleanHost;
            $isConnected = (int)($setting->custom_domain_status ?? 0) === 1;

            if (!empty($setting->customer_id) && Schema::hasTable('wb_customers')) {
                $customerSub = DB::table('wb_customers')
                    ->where('id', $setting->customer_id)
                    ->value('subdomain');
                if (!empty($customerSub)) {
                    $subdomain = $customerSub;
                }
            }

            // Restore original connection — resolve() will re-switch to winner
            $this->restoreConnection($original);

            return [
                'database'     => $dbName,
                'subdomain'    => $subdomain,
                'setting'      => $setting,
                'is_connected' => $isConnected,
            ];
        } catch (\Throwable $e) {
            $this->restoreConnection($original);
            return null;
        }
    }

    private function hostsMatch(string $stored, string $cleanHost): bool
    {
        if (function_exists('wbHostsMatch')) {
            return wbHostsMatch($stored, $cleanHost);
        }
        $storedClean = strtolower(trim(preg_replace('#^https?://#', '', $stored)));
        $storedClean = preg_replace('#^www\.#', '', $storedClean);
        $storedClean = explode('/', $storedClean)[0];
        $storedClean = preg_replace('/:\d+$/', '', $storedClean);
        return $storedClean === $cleanHost;
    }

    private function websiteBuilderTenantDatabases(): array
    {
        $names = [];
        try {
            $pdo = $this->sassPdo();
            if ($pdo) {
                $cols = $pdo->query("SHOW COLUMNS FROM agency_products LIKE 'db_name'");
                if ($cols && $cols->fetch()) {
                    $sql = "SELECT DISTINCT ap.db_name
                            FROM agency_products ap
                            JOIN products p ON p.id = ap.product_id
                            WHERE ap.db_name IS NOT NULL
                              AND ap.db_name != ''
                              AND (p.slug IN ('website-builder', 'websitebuilder')
                                   OR p.slug LIKE '%website%builder%')";
                    foreach ($pdo->query($sql)->fetchAll(\PDO::FETCH_OBJ) as $row) {
                        if (!empty($row->db_name)) {
                            $names[] = $row->db_name;
                        }
                    }

                    $sqlAll = "SELECT DISTINCT db_name FROM agency_products WHERE db_name IS NOT NULL AND db_name != ''";
                    foreach ($pdo->query($sqlAll)->fetchAll(\PDO::FETCH_OBJ) as $row) {
                        if (!empty($row->db_name)) {
                            $names[] = $row->db_name;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // continue
        }

        // Scan MySQL SCHEMATA via INFORMATION_SCHEMA or current DB connection
        try {
            $rows = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME NOT IN ('information_schema', 'mysql', 'performance_schema', 'sys')");
            foreach ($rows as $r) {
                if (!empty($r->SCHEMA_NAME)) {
                    $names[] = $r->SCHEMA_NAME;
                }
            }
        } catch (\Throwable $e) {
            // continue
        }

        return array_values(array_unique(array_filter($names)));
    }

    private function sassPdo(): ?\PDO
    {
        $dbName = env('SASS_ADMIN_DB') ?: env('DB_DATABASE_admin');
        $dbUser = env('SASS_ADMIN_DB_USER') ?: env('DB_USERNAME_admin');
        $dbPass = env('SASS_ADMIN_DB_PASS') ?: env('DB_PASSWORD_admin', '');
        $dbHost = env('SASS_ADMIN_DB_HOST', env('DB_HOST', '127.0.0.1'));
        $dbPort = env('SASS_ADMIN_DB_PORT', env('DB_PORT', '3306'));

        if (!$dbName || !$dbUser) {
            return null;
        }

        $candidates = array_values(array_unique(array_filter([
            $dbName,
            strtolower($dbName),
            'bazaarwa_sass_admindb',
            'bazaarwa_Sass_admindb',
        ])));

        foreach ($candidates as $candDb) {
            try {
                $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$candDb};charset=utf8mb4";
                return new \PDO($dsn, $dbUser, $dbPass, [
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                    \PDO::ATTR_TIMEOUT            => 5,
                ]);
            } catch (\Throwable $e) {
                continue;
            }
        }

        return null;
    }

    private function restoreConnection(array $original): void
    {
        try {
            DB::purge('mysql');
            config([
                'database.connections.mysql.database' => $original['database'],
                'database.connections.mysql.username' => $original['username'],
                'database.connections.mysql.password' => $original['password'],
            ]);
            DB::reconnect('mysql');
        } catch (\Throwable $e) {
            // ignore restore failures; next candidate/request will reconnect
        }
    }

    private function rewriteToWebsiteBuilder(Request $request, string $subdomain, string $path): void
    {
        $passthrough = [
            'agency-admin', 'admin', 'user', 'login', 'logout', 'checkout',
            'templates', 'pricing', 'secret-login', 'midtrans', 'check-payment',
        ];

        $segments = $path === '' ? [] : explode('/', $path);
        $first = strtolower($segments[0] ?? '');

        if ($path === '') {
            $newPath = '/website-builder/' . rawurlencode($subdomain);
        } elseif (in_array($first, $passthrough, true)) {
            $newPath = '/website-builder/' . $path;
        } else {
            $newPath = '/website-builder/' . rawurlencode($subdomain) . '/' . $path;
        }

        $baseUrl = rtrim($request->getBaseUrl(), '/');
        $query = $request->getQueryString();
        $uri = $baseUrl . $newPath . ($query ? '?' . $query : '');

        $request->server->set('REQUEST_URI', $uri);
        $request->server->set('PATH_INFO', $newPath);

        $ref = new \ReflectionObject($request);
        foreach (['requestUri', 'pathInfo', 'baseUrl'] as $prop) {
            if ($ref->hasProperty($prop)) {
                $p = $ref->getProperty($prop);
                $p->setAccessible(true);
                $p->setValue($request, null);
            }
        }
    }

    private function isLaunchShopCustomDomain(string $cleanHost, string $host): bool
    {
        $originalDb = config('database.connections.mysql.database');
        $allTenantDbs = $this->websiteBuilderTenantDatabases();

        foreach ($allTenantDbs as $dbName) {
            try {
                DB::purge('mysql');
                config(['database.connections.mysql.database' => $dbName]);
                DB::reconnect('mysql');

                if (Schema::hasTable('user_custom_domains')) {
                    $exists = DB::table('user_custom_domains')
                        ->where('status', 1)
                        ->where(function ($q) use ($host, $cleanHost) {
                            $q->where('requested_domain', $host)
                              ->orWhere('requested_domain', $cleanHost)
                              ->orWhere('requested_domain', 'www.' . $cleanHost)
                              ->orWhere('requested_domain', 'http://' . $cleanHost)
                              ->orWhere('requested_domain', 'https://' . $cleanHost)
                              ->orWhere('requested_domain', 'http://www.' . $cleanHost)
                              ->orWhere('requested_domain', 'https://www.' . $cleanHost);
                        })
                        ->exists();

                    if ($exists) {
                        $this->restoreConnection([
                            'database' => $originalDb,
                            'username' => config('database.connections.mysql.username'),
                            'password' => config('database.connections.mysql.password'),
                        ]);
                        return true;
                    }
                }
            } catch (\Throwable $e) {
                // continue searching next DB
            }
        }

        $this->restoreConnection([
            'database' => $originalDb,
            'username' => config('database.connections.mysql.username'),
            'password' => config('database.connections.mysql.password'),
        ]);
        return false;
    }
}
