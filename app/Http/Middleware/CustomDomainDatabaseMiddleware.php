<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

/**
 * Custom Domain Database Middleware
 * 
 * This middleware detects when a custom domain (approved by admin) is accessed
 * and switches the database connection from maindb to agencydb.
 * 
 * Logic:
 * 1. Check if the current host is a custom domain
 * 2. Look up the custom domain in user_custom_domains table
 * 3. If found and status = 1 (Connected/Approved), switch to agencydb
 * 4. Otherwise, continue with maindb
 */
class CustomDomainDatabaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip if already resolved by another middleware
        if ($request->attributes->get('custom_domain_db_resolved')) {
            return $next($request);
        }

        // Get the current host
        $host = $request->getHost();
        $normalizedHost = strtolower(preg_replace('/^www\./', '', $host));
        
        // List of main infrastructure hosts that should always use maindb
        $mainHosts = $this->getMainHosts();
        
        // If it's a main host, skip custom domain check
        if (in_array($normalizedHost, $mainHosts)) {
            Log::info("CustomDomainDB: Main host detected '{$normalizedHost}' - using maindb");
            return $next($request);
        }

        // Check if this domain is a registered custom domain
        $customDomainData = $this->findCustomDomain($host, $normalizedHost);
        
        if ($customDomainData) {
            // Custom domain found and approved - switch to agency database
            $this->switchToAgencyDatabase($customDomainData);
            $request->attributes->set('custom_domain_db_resolved', true);
            $request->attributes->set('custom_domain_info', $customDomainData['custom_domain']);
            
            Log::info("CustomDomainDB: Switched to agency database for custom domain '{$normalizedHost}'", [
                'user_id' => $customDomainData['custom_domain']->user_id,
                'requested_domain' => $customDomainData['custom_domain']->requested_domain,
                'agency_db' => $customDomainData['agency_db'],
            ]);
        } else {
            Log::debug("CustomDomainDB: No custom domain found for '{$normalizedHost}' - using default db");
        }

        return $next($request);
    }

    /**
     * Get list of main infrastructure hosts
     *
     * @return array
     */
    protected function getMainHosts(): array
    {
        return array_filter([
            'nooryak.in',
            'www.nooryak.in',
            '127.0.0.1',
            'localhost',
            'launchshop.in',
            'www.launchshop.in',
            'cockroachjantaparty.top',
            'www.cockroachjantaparty.top',
            strtolower((string) env('WEBSITE_HOST', '')),
            strtolower((string) parse_url(env('APP_URL', ''), PHP_URL_HOST)),
        ]);
    }

    /**
     * Find custom domain in database and get associated agency database
     *
     * @param string $host
     * @param string $normalizedHost
     * @return array|null Returns ['custom_domain' => object, 'agency_db' => string] or null
     */
    protected function findCustomDomain(string $host, string $normalizedHost): ?array
    {
        try {
            // Query the user_custom_domains table in maindb
            // Status: 0 = Pending, 1 = Connected (Approved), 2 = Rejected
            $customDomain = DB::table('user_custom_domains')
                ->where('status', 1) // Only approved/connected domains
                ->where(function ($query) use ($host, $normalizedHost) {
                    $query->where('requested_domain', $host)
                        ->orWhere('requested_domain', $normalizedHost)
                        ->orWhere('requested_domain', 'www.' . $normalizedHost)
                        ->orWhere('requested_domain', 'http://' . $normalizedHost)
                        ->orWhere('requested_domain', 'https://' . $normalizedHost)
                        ->orWhere('requested_domain', 'http://www.' . $normalizedHost)
                        ->orWhere('requested_domain', 'https://www.' . $normalizedHost);
                })
                ->first();

            if (!$customDomain) {
                return null;
            }

            // Get the user to find their agency database
            $user = DB::table('users')->where('id', $customDomain->user_id)->first();
            
            if (!$user) {
                Log::warning("CustomDomainDB: User not found for custom domain", ['user_id' => $customDomain->user_id]);
                return null;
            }

            // Now lookup the agency database from Sass_admin
            $agencyDb = $this->getAgencyDatabase($user);
            
            if (!$agencyDb) {
                Log::warning("CustomDomainDB: No agency database found for user", ['username' => $user->username]);
                return null;
            }

            return [
                'custom_domain' => $customDomain,
                'agency_db' => $agencyDb,
                'user' => $user
            ];
        } catch (\Throwable $e) {
            Log::error("CustomDomainDB: Error finding custom domain: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get the agency database name from Sass_admin for a user
     *
     * @param object $user
     * @return string|null
     */
    protected function getAgencyDatabase(object $user): ?string
    {
        // Connect to Sass_admin database to lookup agency database
        $sassAdminDb = env('SASS_ADMIN_DB', 'sass_admin');
        $sassAdminUser = env('SASS_ADMIN_DB_USER', env('DB_USERNAME'));
        $sassAdminPass = env('SASS_ADMIN_DB_PASS', env('DB_PASSWORD'));
        $sassAdminHost = env('SASS_ADMIN_DB_HOST', env('DB_HOST', '127.0.0.1'));
        $sassAdminPort = env('SASS_ADMIN_DB_PORT', env('DB_PORT', '3306'));

        // Try to connect to Sass_admin database
        try {
            $dsn = "mysql:host={$sassAdminHost};port={$sassAdminPort};dbname={$sassAdminDb};charset=utf8mb4";
            $pdo = new \PDO($dsn, $sassAdminUser, $sassAdminPass, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            ]);

            // Query to find agency database
            // The agency databases follow pattern: bazaarwa_ps_{agency_slug}_launchsh
            // Look up in agency_products table with product slug 'launchshop'
            $stmt = $pdo->prepare("
                SELECT ap.db_name, a.slug, a.name
                FROM agency_products ap
                INNER JOIN agencies a ON a.id = ap.agency_id
                INNER JOIN products p ON p.id = ap.product_id
                WHERE p.slug = 'launchshop'
                  AND a.slug IS NOT NULL
                  AND ap.db_name IS NOT NULL
                  AND ap.db_name != ''
                ORDER BY ap.updated_at DESC
                LIMIT 1
            ");
            
            $stmt->execute();
            $agencyProduct = $stmt->fetch(\PDO::FETCH_OBJ);

            if ($agencyProduct && !empty($agencyProduct->db_name)) {
                Log::info("CustomDomainDB: Found agency database from Sass_admin", [
                    'agency_name' => $agencyProduct->name,
                    'agency_slug' => $agencyProduct->slug,
                    'db_name' => $agencyProduct->db_name
                ]);
                return $agencyProduct->db_name;
            }

            // Fallback: Try to construct database name from agency slug
            // Pattern: bazaarwa_ps_{slug}_launchsh
            $cpanelUser = env('CPANEL_USER', 'bazaarwa');
            $agencySlug = $user->username ?? null;
            
            if ($agencySlug) {
                $slugNormalized = str_replace('-', '_', strtolower($agencySlug));
                $candidates = [
                    "{$cpanelUser}_ps_{$slugNormalized}_launchsh",
                    "{$cpanelUser}_ps_{$slugNormalized}_launchshop",
                    "{$cpanelUser}_{$slugNormalized}_launchshop",
                ];

                // Check which database exists
                foreach ($candidates as $dbCandidate) {
                    if ($this->databaseExists($dbCandidate)) {
                        Log::info("CustomDomainDB: Found agency database by pattern matching", [
                            'db_name' => $dbCandidate
                        ]);
                        return $dbCandidate;
                    }
                }
            }

        } catch (\Throwable $e) {
            Log::error("CustomDomainDB: Error connecting to Sass_admin: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Check if a database exists
     *
     * @param string $dbName
     * @return bool
     */
    protected function databaseExists(string $dbName): bool
    {
        try {
            $result = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?", [$dbName]);
            return !empty($result);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Switch database connection to agency database
     *
     * @param array $customDomainData Array with keys: custom_domain, agency_db, user
     * @return void
     */
    protected function switchToAgencyDatabase(array $customDomainData): void
    {
        try {
            $customDomain = $customDomainData['custom_domain'];
            $agencyDbName = $customDomainData['agency_db'];
            $user = $customDomainData['user'];

            // Store original connection details
            $originalConnection = config('database.default');
            
            // Get database credentials (use same as main DB or sass_admin credentials)
            $dbHost = env('SASS_ADMIN_DB_HOST', env('DB_HOST', '127.0.0.1'));
            $dbPort = env('SASS_ADMIN_DB_PORT', env('DB_PORT', '3306'));
            $dbUser = env('SASS_ADMIN_DB_USER', env('DB_USERNAME', 'root'));
            $dbPass = env('SASS_ADMIN_DB_PASS', env('DB_PASSWORD', ''));

            // Purge existing connection and set new one
            DB::purge('mysql');
            
            // Update the mysql connection to use agency database settings
            Config::set('database.connections.mysql.database', $agencyDbName);
            Config::set('database.connections.mysql.username', $dbUser);
            Config::set('database.connections.mysql.password', $dbPass);
            Config::set('database.connections.mysql.host', $dbHost);
            Config::set('database.connections.mysql.port', $dbPort);
            
            // Reconnect with new settings
            DB::reconnect('mysql');
            
            // Test the connection
            DB::connection('mysql')->getPdo();
            
            // Store in session for subsequent requests
            session([
                'custom_domain_active' => true,
                'custom_domain_user_id' => $customDomain->user_id,
                'custom_domain_name' => $customDomain->requested_domain,
                'using_agency_db' => true,
                'agency_db_name' => $agencyDbName,
            ]);
            
            Log::info("CustomDomainDB: Successfully switched to agency database", [
                'database' => $agencyDbName,
                'user_id' => $customDomain->user_id,
                'username' => $user->username ?? 'N/A',
            ]);
        } catch (\Throwable $e) {
            Log::error("CustomDomainDB: Failed to switch to agency database: " . $e->getMessage());
            
            // Attempt to restore original connection on failure
            try {
                DB::purge('mysql');
                Config::set('database.connections.mysql.database', env('DB_DATABASE'));
                Config::set('database.connections.mysql.username', env('DB_USERNAME'));
                Config::set('database.connections.mysql.password', env('DB_PASSWORD'));
                DB::reconnect('mysql');
            } catch (\Throwable $restoreException) {
                Log::critical("CustomDomainDB: Failed to restore original connection: " . $restoreException->getMessage());
            }
        }
    }
}
