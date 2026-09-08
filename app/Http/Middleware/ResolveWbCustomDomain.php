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

        if ($cleanHost === '' || $this->isInfrastructureHost($cleanHost, $host)) {
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

        $mainHosts = array_values(array_unique(array_filter([
            strtolower((string) env('WEBSITE_HOST', '')),
            'launchshop.in',
            'maturednature.com',
            'nooryak.in',
            'cockroachjantaparty.top',
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

        $candidates = array_values(array_unique(array_filter(array_merge(
            [$original['database']],
            $this->websiteBuilderTenantDatabases()
        ))));

        foreach ($candidates as $dbName) {
            $found = $this->matchInDatabase($dbName, $cleanHost, $original);
            if ($found) {
                return $found;
            }
        }

        $this->restoreConnection($original);
        return null;
    }

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

            $any = null;
            $connected = null;
            foreach ($rows as $row) {
                if (!$this->hostsMatch($row->custom_domain ?? '', $cleanHost)) {
                    continue;
                }
                $any = $row;
                if ((int) ($row->custom_domain_status ?? 0) === 1) {
                    $connected = $row;
                    break;
                }
            }

            $setting = $connected ?: $any;
            if (!$setting) {
                $this->restoreConnection($original);
                return null;
            }

            $subdomain = $cleanHost;
            if (!empty($setting->customer_id) && Schema::hasTable('wb_customers')) {
                $customerSub = DB::table('wb_customers')->where('id', $setting->customer_id)->value('subdomain');
                if (!empty($customerSub)) {
                    $subdomain = $customerSub;
                }
            }

            return [
                'database'  => $dbName,
                'subdomain' => $subdomain,
                'setting'   => $setting,
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
            if (!$pdo) {
                return [];
            }
            $cols = $pdo->query("SHOW COLUMNS FROM agency_products LIKE 'db_name'");
            if (!$cols || !$cols->fetch()) {
                return [];
            }
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
        } catch (\Throwable $e) {
            return [];
        }

        return array_values(array_unique($names));
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
}
