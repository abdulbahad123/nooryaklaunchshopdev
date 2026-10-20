<?php

namespace App\Services;

use App\Http\Helpers\BasicMailer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionExpiryService
{
    /**
     * Run daily expiration checks across all products and agencies.
     * Checks: LaunchShop, Website Builder, WhatsApp Bulk.
     * Sends: 2-day and 1-day warning notices via WhatsApp and Email.
     */
    public static function runDailyChecks(): array
    {
        Log::info("SubscriptionExpiryService: Starting multi-product daily subscription check...");

        $stats = [
            'agencies_scanned'   => 0,
            'launchshop_notified' => 0,
            'websitebuilder_notified' => 0,
            'whatsappbulk_notified'   => 0,
            'errors'             => [],
        ];

        // 1. Get list of all agencies + master superadmin platform
        $agencies = self::getAllAgencies();
        $stats['agencies_scanned'] = count($agencies);

        foreach ($agencies as $agency) {
            $agencyName = $agency->name ?? 'Platform';
            $agencySlug = $agency->slug ?? 'main';
            $customDomain = $agency->custom_domain ?? '';

            // Clean domain for URLs
            $cleanDomain = self::cleanDomain($customDomain, $agencySlug);

            // A. Check LaunchShop Subscriptions
            try {
                $lsCount = self::checkLaunchShopSubscriptions($agency, $cleanDomain);
                $stats['launchshop_notified'] += $lsCount;
            } catch (\Throwable $e) {
                $stats['errors'][] = "LS [{$agencySlug}]: " . $e->getMessage();
                Log::warning("SubscriptionExpiryService LS error for {$agencySlug}: " . $e->getMessage());
            }

            // B. Check Website Builder Subscriptions
            try {
                $wbCount = self::checkWebsiteBuilderSubscriptions($agency, $cleanDomain);
                $stats['websitebuilder_notified'] += $wbCount;
            } catch (\Throwable $e) {
                $stats['errors'][] = "WB [{$agencySlug}]: " . $e->getMessage();
                Log::warning("SubscriptionExpiryService WB error for {$agencySlug}: " . $e->getMessage());
            }
        }

        // C. Check WhatsApp Bulk Subscriptions (central / agency WhatsApp DBs)
        try {
            $waCount = self::checkWhatsAppBulkSubscriptions();
            $stats['whatsappbulk_notified'] += $waCount;
        } catch (\Throwable $e) {
            $stats['errors'][] = "WA Bulk: " . $e->getMessage();
            Log::warning("SubscriptionExpiryService WA Bulk error: " . $e->getMessage());
        }

        Log::info("SubscriptionExpiryService: Completed. Stats: " . json_encode($stats));
        return $stats;
    }

    /**
     * Check LaunchShop memberships table for 2-day and 1-day expiry.
     */
    protected static function checkLaunchShopSubscriptions(object $agency, string $cleanDomain): int
    {
        $cpanelUser = env('CPANEL_USER', 'nooryak');
        $slug = $agency->slug ?? '';
        $isMain = empty($slug) || $slug === 'main';

        $dbCandidates = [];
        if (!$isMain) {
            $fullSlug = str_replace('-', '_', strtolower($slug));
            $shortSlug = substr($fullSlug, 0, 16);
            $dbCandidates = [
                "{$cpanelUser}_ps_{$fullSlug}_launchshop",
                "{$cpanelUser}_ps_{$shortSlug}_launchshop",
                "nooryak_ps_{$fullSlug}_launchshop",
                "nooryak_ps_{$shortSlug}_launchshop",
            ];
        } else {
            $dbCandidates = [
                env('DB_DATABASE', 'nooryak_launchshopp'),
            ];
        }

        $pdo = null;
        $activeDb = null;
        foreach ($dbCandidates as $candDb) {
            $pdo = self::getPdoForDb($candDb);
            if ($pdo) {
                $activeDb = $candDb;
                break;
            }
        }

        if (!$pdo) {
            return 0;
        }

        $sql = "SELECT m.id as membership_id, m.user_id, m.package_id, m.expire_date, m.start_date,
                       u.first_name, u.username, u.email, u.phone,
                       p.title as package_title
                FROM memberships m
                JOIN users u ON u.id = m.user_id
                LEFT JOIN packages p ON p.id = m.package_id
                WHERE m.status = 1
                  AND m.expire_date NOT LIKE '9999%'
                  AND (
                      DATE(m.expire_date) = CURDATE() + INTERVAL 2 DAY
                      OR DATE(m.expire_date) = CURDATE() + INTERVAL 1 DAY
                      OR DATE(m.expire_date) = CURDATE()
                  )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $members = $stmt->fetchAll(\PDO::FETCH_OBJ);

        $notified = 0;
        $today = date('Y-m-d');

        foreach ($members as $m) {
            $expDate = Carbon::parse($m->expire_date)->toDateString();
            $diffDays = Carbon::now()->diffInDays(Carbon::parse($expDate), false);

            if ($diffDays == 2) {
                $daysText = '2 days';
                $stage = '2day';
            } elseif ($diffDays == 1) {
                $daysText = '1 day (tomorrow)';
                $stage = '1day';
            } else {
                $daysText = 'today';
                $stage = 'today';
            }

            // Cache key lock to prevent duplicate reminders in the same day
            $lockKey = "exp_rem_ls_{$m->membership_id}_{$stage}_{$today}";
            if (Cache::has($lockKey)) {
                continue;
            }

            $renewalUrl = "https://launchshop.{$cleanDomain}/user/membership/extend-membership";
            $productName = $agency->name ?? 'Ecom Builder';
            $customerName = !empty($m->first_name) ? $m->first_name : $m->username;
            $planName = $m->package_title ?: 'Standard Subscription';

            // 1. Send WhatsApp Warning
            if (!empty($m->phone)) {
                try {
                    WhatsAppNotificationService::sendExpiryReminder(
                        $m->phone,
                        $customerName,
                        $planName,
                        $daysText,
                        Carbon::parse($m->expire_date)->toFormattedDateString(),
                        $renewalUrl,
                        $productName,
                        $agency
                    );
                } catch (\Throwable $waEx) {
                    Log::warning("LS Expiry WhatsApp fail for user {$m->username}: " . $waEx->getMessage());
                }
            }

            // 2. Send Email Warning
            if (!empty($m->email)) {
                try {
                    self::sendExpiryEmail(
                        $m->email,
                        $customerName,
                        $productName,
                        $planName,
                        $daysText,
                        Carbon::parse($m->expire_date)->toFormattedDateString(),
                        $renewalUrl,
                        $pdo
                    );
                } catch (\Throwable $emEx) {
                    Log::warning("LS Expiry Email fail for user {$m->username}: " . $emEx->getMessage());
                }
            }

            Cache::put($lockKey, 1, 86400);
            $notified++;
        }

        return $notified;
    }

    /**
     * Check Website Builder customers table for 2-day and 1-day expiry.
     */
    protected static function checkWebsiteBuilderSubscriptions(object $agency, string $cleanDomain): int
    {
        $cpanelUser = env('CPANEL_USER', 'nooryak');
        $slug = $agency->slug ?? '';
        $isMain = empty($slug) || $slug === 'main';

        $dbCandidates = [];
        if (!$isMain) {
            $fullSlug = str_replace('-', '_', strtolower($slug));
            $shortSlug = substr($fullSlug, 0, 16);
            $dbCandidates = [
                "{$cpanelUser}_ps_{$fullSlug}_webbuild",
                "{$cpanelUser}_ps_{$shortSlug}_webbuild",
                "{$cpanelUser}_ps_{$fullSlug}_websitebuilder",
                "{$cpanelUser}_ps_{$shortSlug}_websitebuilder",
                "nooryak_ps_{$fullSlug}_websitebuilder",
            ];
        } else {
            $dbCandidates = [
                env('DB_DATABASE', 'nooryak_launchshopp'),
            ];
        }

        $pdo = null;
        foreach ($dbCandidates as $candDb) {
            $pdo = self::getPdoForDb($candDb);
            if ($pdo) {
                break;
            }
        }

        if (!$pdo) {
            return 0;
        }

        // Check if wb_customers has expire_date column
        $hasExpireCol = false;
        try {
            $colCheck = $pdo->query("SHOW COLUMNS FROM wb_customers LIKE 'expire_date'")->fetchAll();
            $hasExpireCol = !empty($colCheck);
        } catch (\Throwable $e) {
            return 0;
        }

        $sql = "";
        if ($hasExpireCol) {
            $sql = "SELECT c.id, c.name, c.subdomain, c.email, c.phone, c.expire_date, p.name as package_title
                    FROM wb_customers c
                    LEFT JOIN wb_packages p ON p.id = c.package_id
                    WHERE c.status = 1
                      AND c.expire_date IS NOT NULL
                      AND (
                          DATE(c.expire_date) = CURDATE() + INTERVAL 2 DAY
                          OR DATE(c.expire_date) = CURDATE() + INTERVAL 1 DAY
                          OR DATE(c.expire_date) = CURDATE()
                      )";
        } else {
            // Fallback: Check purchases made 28 or 29 days ago (30-day term) or 363/364 days ago (yearly)
            $sql = "SELECT p.id, p.customer_name as name, p.customer_email as email, p.customer_phone as phone,
                           p.template_slug as subdomain, p.created_at,
                           DATE_ADD(p.created_at, INTERVAL 30 DAY) as expire_date,
                           p.template_name as package_title
                    FROM wb_template_purchases p
                    WHERE p.status IN ('completed', 'success', 'Pending Verification')
                      AND (
                          DATE(DATE_ADD(p.created_at, INTERVAL 30 DAY)) = CURDATE() + INTERVAL 2 DAY
                          OR DATE(DATE_ADD(p.created_at, INTERVAL 30 DAY)) = CURDATE() + INTERVAL 1 DAY
                          OR DATE(DATE_ADD(p.created_at, INTERVAL 30 DAY)) = CURDATE()
                      )";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $customers = $stmt->fetchAll(\PDO::FETCH_OBJ);

        $notified = 0;
        $today = date('Y-m-d');

        foreach ($customers as $c) {
            $expDate = Carbon::parse($c->expire_date)->toDateString();
            $diffDays = Carbon::now()->diffInDays(Carbon::parse($expDate), false);

            if ($diffDays == 2) {
                $daysText = '2 days';
                $stage = '2day';
            } elseif ($diffDays == 1) {
                $daysText = '1 day (tomorrow)';
                $stage = '1day';
            } else {
                $daysText = 'today';
                $stage = 'today';
            }

            $lockKey = "exp_rem_wb_{$c->id}_{$stage}_{$today}";
            if (Cache::has($lockKey)) {
                continue;
            }

            $renewalUrl = "https://websitebuilder.{$cleanDomain}/website-builder/checkout";
            $productName = 'Website Builder';
            $customerName = !empty($c->name) ? $c->name : ($c->subdomain ?? 'Store Owner');
            $planName = $c->package_title ?: 'Pro Website Plan';

            // WhatsApp Notification
            if (!empty($c->phone)) {
                try {
                    WhatsAppNotificationService::sendExpiryReminder(
                        $c->phone,
                        $customerName,
                        $planName,
                        $daysText,
                        Carbon::parse($c->expire_date)->toFormattedDateString(),
                        $renewalUrl,
                        $productName,
                        $agency
                    );
                } catch (\Throwable $waEx) {
                    Log::warning("WB Expiry WhatsApp fail: " . $waEx->getMessage());
                }
            }

            // Email Notification
            if (!empty($c->email)) {
                try {
                    self::sendExpiryEmail(
                        $c->email,
                        $customerName,
                        $productName,
                        $planName,
                        $daysText,
                        Carbon::parse($c->expire_date)->toFormattedDateString(),
                        $renewalUrl,
                        $pdo
                    );
                } catch (\Throwable $emEx) {
                    Log::warning("WB Expiry Email fail: " . $emEx->getMessage());
                }
            }

            Cache::put($lockKey, 1, 86400);
            $notified++;
        }

        return $notified;
    }

    /**
     * Check WhatsApp Bulk users in nooryak_whatautomation for expiration.
     */
    protected static function checkWhatsAppBulkSubscriptions(): int
    {
        $dbName = 'nooryak_whatautomation';
        $pdo = self::getPdoForDb($dbName);
        if (!$pdo) {
            return 0;
        }

        // Find users with expiration_date in next 48 hours (excluding unlimited -1 and 0)
        $now = time();
        $in48h = $now + (2 * 86400);

        $sql = "SELECT id, fullname, username, email, phone, expiration_date
                FROM users
                WHERE status = 1
                  AND expiration_date > ?
                  AND expiration_date <= ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$now, $in48h]);
        $users = $stmt->fetchAll(\PDO::FETCH_OBJ);

        $notified = 0;
        $today = date('Y-m-d');

        foreach ($users as $u) {
            $secondsLeft = $u->expiration_date - $now;
            $hoursLeft = round($secondsLeft / 3600);

            if ($hoursLeft > 24) {
                $daysText = '2 days';
                $stage = '2day';
            } elseif ($hoursLeft > 0) {
                $daysText = '1 day (tomorrow)';
                $stage = '1day';
            } else {
                $daysText = 'today';
                $stage = 'today';
            }

            $lockKey = "exp_rem_wa_{$u->id}_{$stage}_{$today}";
            if (Cache::has($lockKey)) {
                continue;
            }

            $customerName = !empty($u->fullname) ? $u->fullname : $u->username;
            $expireDateFormatted = date('d M, Y', $u->expiration_date);
            $cleanHost = preg_replace('/^(launchshop|checkout|app|www|websitebuilder|website-builder)\./i', '', request()->getHost() ?: 'youverse.in');
            $renewalUrl = "https://whatsappbulk.{$cleanHost}/app/profile/plan";

            if (!empty($u->phone)) {
                try {
                    WhatsAppNotificationService::sendExpiryReminder(
                        $u->phone,
                        $customerName,
                        'WhatsApp Bulk Automation',
                        $daysText,
                        $expireDateFormatted,
                        $renewalUrl,
                        'WhatsApp Bulk Automation'
                    );
                } catch (\Throwable $waEx) {}
            }

            if (!empty($u->email)) {
                try {
                    self::sendExpiryEmail(
                        $u->email,
                        $customerName,
                        'WhatsApp Bulk Automation',
                        'WhatsApp Automation Plan',
                        $daysText,
                        $expireDateFormatted,
                        $renewalUrl,
                        null
                    );
                } catch (\Throwable $emEx) {}
            }

            Cache::put($lockKey, 1, 86400);
            $notified++;
        }

        return $notified;
    }

    /**
     * Send rich HTML Email Expiration Warning.
     */
    protected static function sendExpiryEmail(
        string $email,
        string $name,
        string $productName,
        string $planName,
        string $daysText,
        string $expireDate,
        string $renewalUrl,
        ?\PDO $pdo = null
    ): void {
        $subject = ($daysText === '1 day (tomorrow)')
            ? "🚨 Urgent: Your {$productName} subscription expires tomorrow!"
            : "⚠️ Action Required: Your {$productName} subscription expires in {$daysText}";

        $body = "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px;'>"
              . "<div style='text-align: center; margin-bottom: 20px;'>"
              . "<h2 style='color: #E11D48; margin-bottom: 4px;'>Subscription Expiry Notice</h2>"
              . "<p style='color: #64748B; font-size: 14px; margin: 0;'>Keep your store and website online without downtime</p>"
              . "</div>"
              . "<p style='font-size: 15px; color: #1E293B;'>Hello <strong>{$name}</strong>,</p>"
              . "<p style='font-size: 14px; color: #475569; line-height: 1.6;'>This is a friendly reminder that your subscription for <strong>{$productName}</strong> ({$planName}) will expire in <strong style='color:#E11D48;'>{$daysText}</strong> on <strong>{$expireDate}</strong>.</p>"
              . "<div style='background: #FFF1F2; border: 1px solid #FECDD3; border-radius: 8px; padding: 16px; margin: 20px 0;'>"
              . "<p style='margin: 0; color: #9F1239; font-size: 14px;'><strong>Important:</strong> After this date, your store and service features will be temporarily paused until renewed.</p>"
              . "</div>"
              . "<div style='text-align: center; margin: 28px 0;'>"
              . "<a href='{$renewalUrl}' style='background: #2563EB; color: #ffffff; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 15px; display: inline-block;'>Renew / Extend Plan Now &rarr;</a>"
              . "</div>"
              . "<p style='font-size: 13px; color: #64748B; text-align: center;'>Or copy and paste this link into your browser:<br><a href='{$renewalUrl}' style='color: #2563EB;'>{$renewalUrl}</a></p>"
              . "<hr style='border: none; border-top: 1px solid #e2e8f0; margin: 24px 0;'>"
              . "<p style='font-size: 12px; color: #94A3B8; text-align: center;'>Team {$productName} &bull; 24/7 Automated Support</p>"
              . "</div>";

        // Read SMTP settings
        $smtpHost = env('MAIL_HOST', 'mail.nooryak.in');
        $smtpPort = env('MAIL_PORT', 465);
        $smtpUser = env('MAIL_USERNAME', 'infosaasreselling@nooryak.in');
        $smtpPass = env('MAIL_PASSWORD', 'Admin@nooryak');
        $smtpEnc  = env('MAIL_ENCRYPTION', 'ssl');
        $fromMail = env('MAIL_FROM_ADDRESS', 'infosaasreselling@nooryak.in');

        if ($pdo) {
            try {
                $stmt = $pdo->query("SELECT smtp_host, smtp_port, smtp_username, smtp_password, encryption, from_mail FROM basic_extended LIMIT 1");
                $be = $stmt ? $stmt->fetch(\PDO::FETCH_OBJ) : null;
                if ($be && !empty($be->smtp_host)) {
                    $smtpHost = $be->smtp_host;
                    $smtpPort = $be->smtp_port ?: 465;
                    $smtpUser = $be->smtp_username ?: $smtpUser;
                    $smtpPass = $be->smtp_password ?: $smtpPass;
                    $smtpEnc  = $be->encryption ?: 'ssl';
                    $fromMail = $be->from_mail ?: $fromMail;
                }
            } catch (\Throwable $ex) {}
        }

        $mailData = [
            'smtp_status'   => 1,
            'smtp_host'     => $smtpHost,
            'smtp_port'     => $smtpPort,
            'smtp_username' => $smtpUser,
            'smtp_password' => $smtpPass,
            'encryption'    => $smtpEnc,
            'from_mail'     => $fromMail,
            'recipient'     => $email,
            'subject'       => $subject,
            'body'          => $body,
        ];

        BasicMailer::sendMail($mailData);
    }

    /**
     * Query all agencies from Sass Admin DB.
     */
    protected static function getAllAgencies(): array
    {
        $agencies = [];

        // Add platform master admin as the primary entry
        $platformDomain = env('WEBSITE_HOST', 'saasreselling.com');
        $agencies[] = (object)[
            'id'            => 0,
            'name'          => 'LaunchShop Platform',
            'slug'          => 'main',
            'custom_domain' => $platformDomain,
        ];

        $sassPdo = self::getSassAdminPdo();
        if ($sassPdo) {
            try {
                $stmt = $sassPdo->query("SELECT id, name, slug, custom_domain FROM agencies WHERE custom_domain IS NOT NULL OR slug IS NOT NULL");
                $rows = $stmt ? $stmt->fetchAll(\PDO::FETCH_OBJ) : [];
                foreach ($rows as $r) {
                    $agencies[] = $r;
                }
            } catch (\Throwable $e) {
                Log::debug("SubscriptionExpiryService: Sass Admin agencies query failed: " . $e->getMessage());
            }
        }

        return $agencies;
    }

    /**
     * Clean and format host/domain for renewal links.
     */
    protected static function cleanDomain(?string $customDomain, string $slug): string
    {
        if (!empty($customDomain)) {
            $host = strtolower(trim($customDomain));
            $host = preg_replace('#^https?://#', '', $host);
            $host = preg_replace('/:\d+$/', '', $host);
            $host = preg_replace('/^www\./i', '', $host);
            $clean = preg_replace('/^(launchshop|checkout|app|www|websitebuilder|website-builder)\./i', '', $host);
            if (!empty($clean)) {
                return $clean;
            }
        }

        if (!empty($slug) && $slug !== 'main') {
            return "{$slug}.saasreselling.com";
        }

        return env('WEBSITE_HOST', 'saasreselling.com');
    }

    /**
     * Connect to target MySQL DB using server credentials.
     */
    /**
     * Connect to target MySQL DB using server credentials.
     */
    protected static function getPdoForDb(string $dbName): ?\PDO
    {
        static $pdoCache = [];
        if (isset($pdoCache[$dbName])) {
            return $pdoCache[$dbName];
        }

        // 1. If requesting default database, return active Laravel PDO directly
        $defaultDb = config('database.connections.mysql.database', env('DB_DATABASE', 'nooryak_launchshopp'));
        if (empty($dbName) || $dbName === $defaultDb) {
            try {
                $activePdo = DB::connection()->getPdo();
                if ($activePdo) {
                    $pdoCache[$dbName] = $activePdo;
                    return $activePdo;
                }
            } catch (\Throwable $e) {}
        }

        // 2. For other databases, create an isolated separate PDO connection (NEVER execute USE on active Laravel connection)
        $primaryHost = trim((string)env('DB_HOST', '127.0.0.1'));
        $port = trim((string)env('DB_PORT', '3306'));

        $userPairs = array_values(array_filter([
            ['user' => trim((string)config('database.connections.mysql.username')), 'pass' => trim((string)config('database.connections.mysql.password'))],
            ['user' => trim((string)(env('SASS_ADMIN_DB_USER') ?: env('DB_USERNAME_admin'))), 'pass' => trim((string)(env('SASS_ADMIN_DB_PASS') ?: env('DB_PASSWORD_admin', '')))],
            ['user' => trim((string)env('DB_USERNAME')), 'pass' => trim((string)env('DB_PASSWORD', ''))],
            ['user' => 'root', 'pass' => ''],
        ], function ($item) {
            return !empty($item['user']) || $item['user'] === '';
        }));

        // Try unix socket for cPanel environments first (instant 0ms)
        $sockets = ['/var/lib/mysql/mysql.sock', '/tmp/mysql.sock', '/run/mysqld/mysqld.sock'];
        foreach ($sockets as $socket) {
            if (@file_exists($socket)) {
                foreach ($userPairs as $pair) {
                    try {
                        $dsn = "mysql:unix_socket={$socket};dbname={$dbName};charset=utf8mb4";
                        $pdo = new \PDO($dsn, $pair['user'], $pair['pass'], [
                            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_SILENT,
                            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                            \PDO::ATTR_TIMEOUT            => 1,
                        ]);
                        $pdoCache[$dbName] = $pdo;
                        return $pdo;
                    } catch (\Throwable $e) {}
                }
            }
        }

        // Try primary host
        foreach ($userPairs as $pair) {
            try {
                $dsn = "mysql:host={$primaryHost};port={$port};dbname={$dbName};charset=utf8mb4";
                $pdo = new \PDO($dsn, $pair['user'], $pair['pass'], [
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_SILENT,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                    \PDO::ATTR_TIMEOUT            => 1,
                ]);
                $pdoCache[$dbName] = $pdo;
                return $pdo;
            } catch (\Throwable $e) {}
        }

        return null;
    }

    /**
     * Query any database using cross-DB select, dynamic connection, or isolated PDO fallback.
     */
    public static function queryDb(string $dbName, string $sql, array $bindings = []): array
    {
        if (empty($dbName)) return [];

        // 1. Cross-DB query on default connection
        try {
            $parsedSql = preg_replace('/(?<!`)\b(wb_customers|wb_packages|wb_template_purchases|users|plans|options)\b(?!`)/i', "`{$dbName}`.$1", $sql);
            $rows = DB::select($parsedSql, $bindings);
            if (!empty($rows)) {
                return $rows;
            }
        } catch (\Throwable $e) {}

        // 2. Dynamic Laravel connection
        try {
            $connName = 'dyn_' . substr(md5($dbName), 0, 8);
            if (!config("database.connections.{$connName}")) {
                config([
                    "database.connections.{$connName}" => array_merge(
                        config('database.connections.mysql'),
                        ['database' => $dbName]
                    )
                ]);
            }
            $rows = DB::connection($connName)->select($sql, $bindings);
            if (!empty($rows)) {
                return $rows;
            }
        } catch (\Throwable $e) {}

        // 3. Isolated PDO
        $pdo = self::getPdoForDb($dbName);
        if ($pdo) {
            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute($bindings);
                return $stmt->fetchAll(\PDO::FETCH_OBJ) ?: [];
            } catch (\Throwable $e) {}
        }

        return [];
    }

    /**
     * Execute SQL statement on any database.
     */
    public static function executeDb(string $dbName, string $sql, array $bindings = []): bool
    {
        if (empty($dbName)) return false;

        // 1. Cross-DB statement on default connection
        try {
            $parsedSql = preg_replace('/(?<!`)\b(wb_customers|wb_packages|wb_template_purchases|users|plans|options)\b(?!`)/i', "`{$dbName}`.$1", $sql);
            DB::statement($parsedSql, $bindings);
            return true;
        } catch (\Throwable $e) {}

        // 2. Dynamic Laravel connection
        try {
            $connName = 'dyn_' . substr(md5($dbName), 0, 8);
            if (!config("database.connections.{$connName}")) {
                config([
                    "database.connections.{$connName}" => array_merge(
                        config('database.connections.mysql'),
                        ['database' => $dbName]
                    )
                ]);
            }
            DB::connection($connName)->statement($sql, $bindings);
            return true;
        } catch (\Throwable $e) {}

        // 3. Isolated PDO
        $pdo = self::getPdoForDb($dbName);
        if ($pdo) {
            try {
                $stmt = $pdo->prepare($sql);
                return (bool)$stmt->execute($bindings);
            } catch (\Throwable $e) {}
        }

        return false;
    }

    /**
     * Connect to Sass Admin DB.
     */
    protected static function getSassAdminPdo(): ?\PDO
    {
        $cpanelUser = env('CPANEL_USER', 'nooryak');
        $dbCandidates = array_values(array_unique(array_filter([
            env('SASS_ADMIN_DB'),
            env('DB_DATABASE_admin'),
            "{$cpanelUser}_sass_admindb",
            "{$cpanelUser}_Sass_admindb",
            'nooryak_sass_admindb',
            'nooryak_Sass_admindb',
            'sass_admin',
        ])));

        foreach ($dbCandidates as $dbName) {
            $pdo = self::getPdoForDb($dbName);
            if ($pdo) {
                return $pdo;
            }
        }

        return null;
    }

    /**
     * Send immediate expiry warning notification to a specific user (for tests or manual trigger).
     */
    public static function sendExpiryWarningForUser($user, $customMinutes = null, bool $force = true): array
    {
        $res = [
            'whatsapp_sent' => false,
            'email_sent'    => false,
            'phone'         => null,
            'email'         => null,
            'time_text'     => null,
            'errors'        => [],
        ];

        try {
            if (!$user) {
                $res['errors'][] = "User not found";
                return $res;
            }

            // Fetch latest membership and package
            $membership = \App\Models\Membership::where('user_id', $user->id)
                ->orderBy('expire_date', 'desc')
                ->first();

            $package = null;
            if ($membership && $membership->package_id) {
                $package = \App\Models\Package::find($membership->package_id);
            }

            $planName = $package->title ?? 'Store Subscription';
            $customerName = !empty($user->first_name) ? $user->first_name : $user->username;
            $phone = !empty($user->phone) ? $user->phone : (!empty($user->phone_number) ? $user->phone_number : null);
            $email = $user->email ?? null;

            $res['phone'] = $phone;
            $res['email'] = $email;

            // Determine time remaining text
            if ($customMinutes !== null) {
                $mins = intval($customMinutes);
                $daysText = $mins <= 1 ? "1 minute" : "{$mins} minutes";
            } elseif ($membership && $membership->expire_date) {
                $exp = Carbon::parse($membership->expire_date);
                $now = Carbon::now();
                if ($exp->isPast()) {
                    $daysText = "today (EXPIRED)";
                } else {
                    $diffInMinutes = $now->diffInMinutes($exp);
                    if ($diffInMinutes < 60) {
                        $daysText = $diffInMinutes <= 1 ? "1 minute" : "{$diffInMinutes} minutes";
                    } elseif ($diffInMinutes < 1440) {
                        $hours = round($diffInMinutes / 60);
                        $daysText = "{$hours} hours";
                    } else {
                        $days = $now->diffInDays($exp);
                        $daysText = $days <= 1 ? "1 day (tomorrow)" : "{$days} days";
                    }
                }
            } else {
                $daysText = "soon";
            }

            $res['time_text'] = $daysText;

            // Get renewal link
            $renewalUrl = url('/user/membership/extend-membership');
            $productName = 'LaunchShop';

            // 1. Send WhatsApp Warning
            if (!empty($phone)) {
                try {
                    $expFormatted = ($membership && $membership->expire_date) 
                        ? Carbon::parse($membership->expire_date)->format('M d, Y h:i A') 
                        : Carbon::now()->addMinutes(intval($customMinutes ?: 5))->format('M d, Y h:i A');

                    $waResult = WhatsAppNotificationService::sendExpiryReminder(
                        $phone,
                        $customerName,
                        $planName,
                        $daysText,
                        $expFormatted,
                        $renewalUrl,
                        $productName,
                        $agency ?? null
                    );
                    $res['whatsapp_sent'] = !empty($waResult['success']);
                    if (!$res['whatsapp_sent'] && !empty($waResult['error'])) {
                        $res['errors'][] = "WhatsApp error: " . $waResult['error'];
                    }
                } catch (\Throwable $waEx) {
                    $res['errors'][] = "WhatsApp exception: " . $waEx->getMessage();
                }
            } else {
                $res['errors'][] = "No phone number configured for user";
            }

            // 2. Send Email Warning
            if (!empty($email)) {
                try {
                    $subject = "⚠️ Action Required: Your {$productName} subscription expires in {$daysText}";
                    $body = "
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e5e7eb; border-radius: 12px; background-color: #ffffff;'>
                            <div style='text-align: center; margin-bottom: 24px;'>
                                <h2 style='color: #ef4444; margin: 0;'>Subscription Expiry Notice</h2>
                                <p style='color: #6b7280; font-size: 14px;'>{$productName} Platform</p>
                            </div>
                            <p style='color: #374151; font-size: 16px;'>Hello <strong>{$customerName}</strong>,</p>
                            <p style='color: #374151; font-size: 15px; line-height: 1.6;'>
                                This is an automated warning that your active subscription plan <strong>({$planName})</strong> is expiring in <span style='color: #ef4444; font-weight: bold;'>{$daysText}</span>.
                            </p>
                            <div style='background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 16px; margin: 20px 0; border-radius: 4px;'>
                                <p style='margin: 0; color: #991b1b; font-size: 14px;'>
                                    <strong>Important:</strong> If your subscription expires without renewal, your public store and customer ordering will be automatically suspended until payment is received.
                                </p>
                            </div>
                            <div style='text-align: center; margin: 30px 0;'>
                                <a href='{$renewalUrl}' style='display: inline-block; padding: 14px 28px; background: #6366f1; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 15px;'>
                                    Renew Subscription Now
                                </a>
                            </div>
                            <p style='color: #6b7280; font-size: 13px; line-height: 1.5; border-top: 1px solid #f3f4f6; padding-top: 16px;'>
                                If you have already made a payment, please disregard this notice. For assistance, contact our support team.
                            </p>
                        </div>
                    ";

                    $mailData = [
                        'recipient' => $email,
                        'subject'   => $subject,
                        'body'      => $body,
                    ];
                    BasicMailer::sendMail($mailData);
                    $res['email_sent'] = true;
                } catch (\Throwable $mEx) {
                    $res['errors'][] = "Email exception: " . $mEx->getMessage();
                }
            } else {
                $res['errors'][] = "No email address found for user";
            }
        } catch (\Throwable $e) {
            $res['errors'][] = "General exception: " . $e->getMessage();
        }

        return $res;
    }

    /**
     * Fast test helper: Set a user's subscription to expire in X minutes.
     */
    public static function setExpiryInMinutes($user, int $minutes = 5)
    {
        $membership = \App\Models\Membership::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();

        $expireDate = Carbon::now()->addMinutes($minutes)->format('Y-m-d H:i:s');
        $startDate = Carbon::now()->subHour()->format('Y-m-d H:i:s');

        if ($membership) {
            $membership->expire_date = $expireDate;
            $membership->start_date = $startDate;
            $membership->status = 1;
            $membership->save();
        } else {
            $pkg = \App\Models\Package::first();
            $membership = \App\Models\Membership::create([
                'user_id' => $user->id,
                'package_id' => $pkg->id ?? 1,
                'start_date' => $startDate,
                'expire_date' => $expireDate,
                'status' => 1,
                'price' => 0,
                'currency' => 'INR',
                'currency_symbol' => '₹',
                'payment_method' => 'test_mode',
                'transaction_id' => 'TEST_' . uniqid(),
            ]);
        }

        if (isset($user->status) && $user->status != 1) {
            $user->status = 1;
            $user->save();
        }

        return $membership;
    }

    /**
     * Fast test helper: Immediately expire a user's subscription so the store is blocked.
     */
    public static function expireUserImmediately($user)
    {
        $membership = \App\Models\Membership::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();

        $pastDate = Carbon::now()->subMinutes(2)->format('Y-m-d H:i:s');
        if ($membership) {
            $membership->expire_date = $pastDate;
            $membership->save();
        }

        return $membership;
    }

    /**
     * Admin reactivation helper: Unblock user and extend validity.
     */
    public static function reactivateUser($user, int $days = 30)
    {
        $membership = \App\Models\Membership::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();

        $expireDate = Carbon::now()->addDays($days)->format('Y-m-d H:i:s');
        $startDate = Carbon::now()->format('Y-m-d H:i:s');

        if ($membership) {
            $membership->start_date = $startDate;
            $membership->expire_date = $expireDate;
            $membership->status = 1;
            $membership->save();
        } else {
            $pkg = \App\Models\Package::first();
            $membership = \App\Models\Membership::create([
                'user_id' => $user->id,
                'package_id' => $pkg->id ?? 1,
                'start_date' => $startDate,
                'expire_date' => $expireDate,
                'status' => 1,
                'price' => 0,
                'currency' => 'INR',
                'currency_symbol' => '₹',
                'payment_method' => 'admin_reactivate',
                'transaction_id' => 'ADMIN_' . uniqid(),
            ]);
        }

        if (isset($user->status) && $user->status != 1) {
            $user->status = 1;
            $user->save();
        }

        return $membership;
    }

    // ─────────────────────────────────────────────────────────────────
    // WEBSITE BUILDER TESTING & MANAGEMENT HELPERS
    // ─────────────────────────────────────────────────────────────────

    public static function sendExpiryWarningForWbCustomer($customer, $customMinutes = null): array
    {
        $res = [
            'whatsapp_sent' => false,
            'email_sent'    => false,
            'phone'         => null,
            'email'         => null,
            'time_text'     => null,
            'errors'        => [],
        ];

        try {
            if (!$customer) {
                $res['errors'][] = "Customer not found";
                return $res;
            }

            $customerName = !empty($customer->name) ? $customer->name : ($customer->subdomain ?? 'Merchant');
            $phone = $customer->phone ?? null;
            $email = $customer->email ?? null;
            $planName = !empty($customer->package) ? ($customer->package->name ?? 'Pro Website Plan') : 'Website Builder Plan';
            $productName = 'Website Builder';

            $res['phone'] = $phone;
            $res['email'] = $email;

            if ($customMinutes !== null) {
                $mins = intval($customMinutes);
                $daysText = $mins <= 1 ? "1 minute" : "{$mins} minutes";
            } elseif (!empty($customer->expire_date)) {
                $exp = Carbon::parse($customer->expire_date);
                $diffInMinutes = Carbon::now()->diffInMinutes($exp);
                $daysText = $diffInMinutes < 60 ? "{$diffInMinutes} minutes" : round($diffInMinutes / 1440) . " days";
            } else {
                $daysText = "soon";
            }
            $res['time_text'] = $daysText;

            $domain = env('WEBSITE_HOST', 'launchshop.youverse.in');
            $cleanDomain = preg_replace('/^(launchshop|checkout|app|www|websitebuilder|website-builder)\./i', '', request()->getHost() ?: 'youverse.in');
            $renewalUrl = "https://websitebuilder.{$cleanDomain}/website-builder/checkout";

            // 1. WhatsApp Warning
            if (!empty($phone)) {
                try {
                    $expFormatted = !empty($customer->expire_date) 
                        ? Carbon::parse($customer->expire_date)->format('M d, Y h:i A') 
                        : Carbon::now()->addMinutes(intval($customMinutes ?: 5))->format('M d, Y h:i A');

                    $waRes = WhatsAppNotificationService::sendExpiryReminder(
                        $phone,
                        $customerName,
                        $planName,
                        $daysText,
                        $expFormatted,
                        $renewalUrl,
                        $productName,
                        $agency ?? null
                    );
                    $res['whatsapp_sent'] = !empty($waRes['success']);
                    if (!$res['whatsapp_sent'] && !empty($waRes['error'])) {
                        $res['errors'][] = "WhatsApp: " . $waRes['error'];
                    }
                } catch (\Throwable $waEx) {
                    $res['errors'][] = "WhatsApp: " . $waEx->getMessage();
                }
            } else {
                $res['errors'][] = "No phone number found for WB customer";
            }

            // 2. Email Warning
            if (!empty($email)) {
                try {
                    $subject = "⚠️ Action Required: Your {$productName} subscription expires in {$daysText}";
                    $body = "
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e5e7eb; border-radius: 12px; background-color: #ffffff;'>
                            <div style='text-align: center; margin-bottom: 24px;'>
                                <h2 style='color: #ef4444; margin: 0;'>Subscription Expiry Notice</h2>
                                <p style='color: #6b7280; font-size: 14px;'>{$productName} Platform</p>
                            </div>
                            <p style='color: #374151; font-size: 16px;'>Hello <strong>{$customerName}</strong>,</p>
                            <p style='color: #374151; font-size: 15px; line-height: 1.6;'>
                                Your active website subscription <strong>({$planName})</strong> is expiring in <span style='color: #ef4444; font-weight: bold;'>{$daysText}</span>.
                            </p>
                            <div style='background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 16px; margin: 20px 0; border-radius: 4px;'>
                                <p style='margin: 0; color: #991b1b; font-size: 14px;'>
                                    <strong>Important:</strong> If not renewed, your public website will be temporarily suspended until payment is received.
                                </p>
                            </div>
                            <div style='text-align: center; margin: 30px 0;'>
                                <a href='{$renewalUrl}' style='display: inline-block; padding: 14px 28px; background: #6366f1; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 15px;'>
                                    Renew Subscription Now
                                </a>
                            </div>
                        </div>
                    ";
                    BasicMailer::sendMail(['recipient' => $email, 'subject' => $subject, 'body' => $body]);
                    $res['email_sent'] = true;
                } catch (\Throwable $emEx) {
                    $res['errors'][] = "Email: " . $emEx->getMessage();
                }
            } else {
                $res['errors'][] = "No email address found for WB customer";
            }
        } catch (\Throwable $e) {
            $res['errors'][] = $e->getMessage();
        }

        return $res;
    }

    public static function setExpiryForWbCustomer($customer, int $minutes = 5)
    {
        $exp = Carbon::now()->addMinutes($minutes)->format('Y-m-d H:i:s');
        $customer->expire_date = $exp;
        $customer->status = 1;

        if (!empty($customer->raw_user_id)) {
            try {
                $m = \App\Models\Membership::where('user_id', $customer->raw_user_id)->orderBy('expire_date', 'desc')->first();
                if ($m) {
                    $m->expire_date = $exp;
                    $m->status = 1;
                    $m->save();
                }
            } catch (\Throwable $e) {}
        }

        if ($customer instanceof \Illuminate\Database\Eloquent\Model) {
            $customer->save();
        } else {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                    DB::table('wb_customers')->where('id', $customer->id)->update(['expire_date' => $exp, 'status' => 1]);
                }
            } catch (\Throwable $e) {}

            $dbName = $customer->_db_name ?? null;
            if ($dbName) {
                self::executeDb($dbName, "UPDATE wb_customers SET expire_date = ?, status = 1 WHERE id = ?", [$exp, $customer->id]);
            }
        }
        try {
            if (class_exists(\App\Models\WebsiteBuilder\WbCustomer::class)) {
                \App\Models\WebsiteBuilder\WbCustomer::where('id', $customer->id)
                    ->orWhere('subdomain', $customer->subdomain ?? '')
                    ->update(['expire_date' => $exp, 'status' => 1]);
            }
        } catch (\Throwable $e) {}
        return $customer;
    }

    public static function expireWbCustomerImmediately($customer)
    {
        $past = Carbon::now()->subMinutes(2)->format('Y-m-d H:i:s');
        $customer->expire_date = $past;

        if (!empty($customer->raw_user_id)) {
            try {
                $m = \App\Models\Membership::where('user_id', $customer->raw_user_id)->orderBy('expire_date', 'desc')->first();
                if ($m) {
                    $m->expire_date = $past;
                    $m->save();
                }
            } catch (\Throwable $e) {}
        }

        if ($customer instanceof \Illuminate\Database\Eloquent\Model) {
            $customer->save();
        } else {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                    DB::table('wb_customers')->where('id', $customer->id)->update(['expire_date' => $past]);
                }
            } catch (\Throwable $e) {}

            $dbName = $customer->_db_name ?? null;
            if ($dbName) {
                self::executeDb($dbName, "UPDATE wb_customers SET expire_date = ? WHERE id = ?", [$past, $customer->id]);
            }
        }
        try {
            if (class_exists(\App\Models\WebsiteBuilder\WbCustomer::class)) {
                \App\Models\WebsiteBuilder\WbCustomer::where('id', $customer->id)
                    ->orWhere('subdomain', $customer->subdomain ?? '')
                    ->update(['expire_date' => $past]);
            }
        } catch (\Throwable $e) {}
        return $customer;
    }

    public static function reactivateWbCustomer($customer, int $days = 30)
    {
        $exp = Carbon::now()->addDays($days)->format('Y-m-d H:i:s');
        $customer->expire_date = $exp;
        $customer->status = 1;

        if (!empty($customer->raw_user_id)) {
            try {
                $m = \App\Models\Membership::where('user_id', $customer->raw_user_id)->orderBy('expire_date', 'desc')->first();
                if ($m) {
                    $m->expire_date = $exp;
                    $m->status = 1;
                    $m->save();
                }
            } catch (\Throwable $e) {}
        }

        if ($customer instanceof \Illuminate\Database\Eloquent\Model) {
            $customer->save();
        } else {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                    DB::table('wb_customers')->where('id', $customer->id)->update(['expire_date' => $exp, 'status' => 1]);
                }
            } catch (\Throwable $e) {}

            $dbName = $customer->_db_name ?? null;
            if ($dbName) {
                self::executeDb($dbName, "UPDATE wb_customers SET expire_date = ?, status = 1 WHERE id = ?", [$exp, $customer->id]);
            }
        }
        try {
            if (class_exists(\App\Models\WebsiteBuilder\WbCustomer::class)) {
                \App\Models\WebsiteBuilder\WbCustomer::where('id', $customer->id)
                    ->orWhere('subdomain', $customer->subdomain ?? '')
                    ->update(['expire_date' => $exp, 'status' => 1]);
            }
        } catch (\Throwable $e) {}
        return $customer;
    }

    public static function findWbCustomer($id)
    {
        // 0. Check if platform user format "u_12"
        // 1. Try Eloquent
        if (class_exists(\App\Models\WebsiteBuilder\WbCustomer::class)) {
            try {
                $c = \App\Models\WebsiteBuilder\WbCustomer::find($id);
                if ($c) return $c;
            } catch (\Throwable $e) {}
        }

        // 2. Try DB::table('wb_customers')
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                $row = DB::table('wb_customers')->where('id', $id)->first();
                if ($row) {
                    $row->package = (object)[
                        'name'          => 'Standard Plan',
                        'title'         => 'Standard Plan',
                        'monthly_price' => 29,
                        'yearly_price'  => 290,
                    ];
                    return $row;
                }
            }
        } catch (\Throwable $e) {}

        // 3. Search across customer candidate databases
        $candidateDbs = self::getDiscoveredWbDatabases();
        foreach ($candidateDbs as $db) {
            $rows = self::queryDb($db, "SELECT * FROM wb_customers WHERE id = ? OR subdomain = ? LIMIT 1", [$id, $id]);
            if (!empty($rows)) {
                $row = $rows[0];
                $row->_db_name = $db;
                $row->name = !empty($row->name) ? $row->name : (!empty($row->subdomain) ? $row->subdomain : "Client #{$row->id}");
                $pkgName = 'Agency Plan';
                if (!empty($row->package_id)) {
                    $pRows = self::queryDb($db, "SELECT name, monthly_price, yearly_price FROM wb_packages WHERE id = ? LIMIT 1", [$row->package_id]);
                    if (!empty($pRows)) {
                        $pkgName = $pRows[0]->name;
                    }
                }
                $row->package = (object)[
                    'name'          => $pkgName,
                    'title'         => $pkgName,
                    'monthly_price' => 29,
                    'yearly_price'  => 290,
                ];
                return $row;
            }
        }

        // 4. Search in list
        foreach (self::getWebsiteBuilderCustomers() as $item) {
            if ((string)($item->id ?? '') === (string)$id || (string)($item->subdomain ?? '') === (string)$id) {
                return $item;
            }
        }

        return null;
    }

    protected static function getDiscoveredLsDatabases(): array
    {
        $cpanelUser = env('CPANEL_USER', 'nooryak');
        $dbs = [
            config('database.connections.mysql.database', 'nooryak_launchshopp'),
            "{$cpanelUser}_launchshopp",
            "{$cpanelUser}_launchshop",
            "{$cpanelUser}_ps_youverse_launchshop",
            "bazaarwa_ps_youverse_launchshop",
            "nooryak_launchshop",
        ];

        try {
            $sassAdminDb = env('SASS_ADMIN_DB', 'nooryak_saas_admin');
            $sassPdo = self::getPdoForDb($sassAdminDb);
            if ($sassPdo) {
                $stmt = $sassPdo->query("SELECT DISTINCT ap.db_name FROM agency_products ap JOIN products p ON p.id = ap.product_id WHERE (p.slug LIKE '%launchshop%' OR p.slug LIKE '%ecom%') AND ap.db_name IS NOT NULL AND ap.db_name != ''");
                if ($stmt) {
                    $found = $stmt->fetchAll(\PDO::FETCH_COLUMN);
                    if (!empty($found)) {
                        $dbs = array_merge($dbs, $found);
                    }
                }
            }
        } catch (\Throwable $e) {}

        return array_values(array_unique(array_filter($dbs)));
    }

    public static function getLaunchShopUsers(): array
    {
        $users = [];
        $seen = [];
        $candidateDbs = self::getDiscoveredLsDatabases();

        // 1. Eloquent/Default connection
        try {
            $rows = \App\Models\User::orderBy('id', 'desc')->take(60)->get();
            if ($rows && $rows->isNotEmpty()) {
                foreach ($rows as $u) {
                    $k = strtolower(trim($u->username ?: $u->email));
                    if (isset($seen[$k])) continue;
                    $seen[$k] = true;

                    $mem = null;
                    try {
                        $mem = \App\Models\Membership::where('user_id', $u->id)->orderBy('expire_date', 'desc')->first();
                    } catch (\Throwable $me) {}

                    $users[] = (object)[
                        'id'            => $u->id,
                        'username'      => $u->username,
                        'name'          => trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')) ?: $u->username,
                        'email'         => $u->email,
                        'phone'         => $u->phone ?: ($u->phone_number ?: ''),
                        'expire_date'   => $mem ? $mem->expire_date : null,
                        'package_title' => $mem && $mem->package ? ($mem->package->title ?? $mem->package->name) : 'Ecom Store Plan',
                        'status'        => $u->status ?? 1,
                        '_db_name'      => config('database.connections.mysql.database'),
                    ];
                }
            }
        } catch (\Throwable $e) {}

        // 2. Query discovered LS agency databases
        foreach ($candidateDbs as $db) {
            $pdo = self::getPdoForDb($db);
            if ($pdo) {
                try {
                    $sql = "SELECT u.id, u.username, u.first_name, u.last_name, u.email, u.phone, u.status,
                                   m.expire_date, p.title as package_title
                            FROM users u
                            LEFT JOIN memberships m ON m.user_id = u.id AND m.status = 1
                            LEFT JOIN packages p ON p.id = m.package_id
                            ORDER BY u.id DESC LIMIT 60";
                    $stmt = $pdo->query($sql);
                    if (!$stmt) {
                        $stmt = $pdo->query("SELECT id, username, first_name, last_name, email, phone, status FROM users ORDER BY id DESC LIMIT 60");
                    }
                    if ($stmt) {
                        $rows = $stmt->fetchAll(\PDO::FETCH_OBJ);
                        foreach ($rows as $row) {
                            $k = strtolower(trim($row->username ?: $row->email));
                            if (isset($seen[$k])) continue;
                            $seen[$k] = true;

                            $row->_db_name = $db;
                            $row->name = trim(($row->first_name ?? '') . ' ' . ($row->last_name ?? '')) ?: $row->username;
                            $row->package_title = $row->package_title ?? 'Ecom Store Plan';
                            $row->expire_date = $row->expire_date ?? null;
                            $users[] = $row;
                        }
                    }
                } catch (\Throwable $e) {}
            }
        }

        return $users;
    }

    public static function findLaunchShopUser($identifier)
    {
        try {
            $u = \App\Models\User::find($identifier) ?: \App\Models\User::where('username', $identifier)->first();
            if ($u) {
                $mem = \App\Models\Membership::where('user_id', $u->id)->orderBy('expire_date', 'desc')->first();
                $u->name = trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')) ?: $u->username;
                $u->expire_date = $mem ? $mem->expire_date : null;
                $u->package_title = $mem && $mem->package ? ($mem->package->title ?? $mem->package->name) : 'Ecom Store Plan';
                return $u;
            }
        } catch (\Throwable $e) {}

        foreach (self::getLaunchShopUsers() as $u) {
            if ((string)($u->id ?? '') === (string)$identifier || (string)($u->username ?? '') === (string)$identifier) {
                return $u;
            }
        }

        return null;
    }

    protected static function getDiscoveredWbDatabases(): array
    {
        $cpanelUser = env('CPANEL_USER', 'nooryak');

        // Dynamically resolve from current request host or session
        $host = strtolower(request()->getHost() ?? '');
        $cleanHost = preg_replace('/^(launchshop|checkout|app|www|websitebuilder|website-builder|whatsappbulk|whatsapp)\./i', '', $host);
        $parts = explode('.', $cleanHost);
        $reqSlug = !empty($parts[0]) && !in_array($parts[0], ['localhost', '127', 'saasreselling', 'cockroachjantaparty']) ? str_replace('-', '_', strtolower($parts[0])) : 'youverse';

        $dbs = [
            "{$cpanelUser}_ps_{$reqSlug}_webbuild",
            "{$cpanelUser}_ps_{$reqSlug}_websitebuilder",
            "{$cpanelUser}_ps_{$reqSlug}_websitebuild",
            "{$cpanelUser}_ps_{$reqSlug}_website_buil",
            "{$cpanelUser}_ps_{$reqSlug}_website_b",
            "{$cpanelUser}_{$reqSlug}_websitebuilder",
            'nooryak_ps_youverse_webbuild',
            'nooryak_ps_youverse_websitebuilder',
            'nooryak_ps_youverse_websitebuild',
            'nooryak_ps_youverse_website_buil',
            'nooryak_ps_youverse_website_b',
            'nooryak_youverse_websitebuilder',
            'bazaarwa_ps_youverse_webbuild',
            'bazaarwa_ps_youverse_websitebuild',
            'bazaarwa_ps_youverse_websitebuilder',
            'nooryak_websitebuilder',
            'nooryak_webbuilder',
        ];

        try {
            $sassPdo = self::getSassAdminPdo();
            if ($sassPdo) {
                $stmt = $sassPdo->query("SELECT DISTINCT ap.db_name FROM agency_products ap JOIN products p ON p.id = ap.product_id WHERE (p.slug LIKE '%website%' OR p.slug LIKE '%webbuild%') AND ap.db_name IS NOT NULL AND ap.db_name != ''");
                if ($stmt) {
                    $found = $stmt->fetchAll(\PDO::FETCH_COLUMN);
                    if (!empty($found)) {
                        $dbs = array_merge($dbs, $found);
                    }
                }
            }
        } catch (\Throwable $e) {}

        // Discover from INFORMATION_SCHEMA if accessible
        try {
            $found = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME LIKE '%websitebuild%' OR SCHEMA_NAME LIKE '%webbuild%'");
            if (!empty($found)) {
                $dbs = array_merge($dbs, array_column($found, 'SCHEMA_NAME'));
            }
        } catch (\Throwable $e) {}

        return array_values(array_unique(array_filter($dbs)));
    }

    protected static function getDiscoveredWaDatabases(): array
    {
        $dbs = [
            'nooryak_ps_youverse_whatsappbulk',
            'nooryak_ps_youverse_whatsapp',
            'bazaarwa_ps_youverse_whatsappbulk',
            'bazaarwa_ps_youverse_whatsapp',
            'nooryak_whatsappbulk',
            'nooryak_whatautomation',
            'nooryak_replix_automation',
            'replix_automation',
            'whatsappautomation',
            'nooryak_whatsapp',
        ];

        try {
            $found = DB::select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME LIKE '%whatsapp%' OR SCHEMA_NAME LIKE '%whatautomation%' OR SCHEMA_NAME LIKE '%replix%' OR SCHEMA_NAME LIKE '%stackposts%'");
            if (!empty($found)) {
                $dbs = array_merge($dbs, array_column($found, 'SCHEMA_NAME'));
            }
        } catch (\Throwable $e) {}

        try {
            $sassPdo = self::getSassAdminPdo();
            if ($sassPdo) {
                $stmt = $sassPdo->query("SELECT DISTINCT ap.db_name FROM agency_products ap JOIN products p ON p.id = ap.product_id WHERE (p.slug LIKE '%whatsapp%' OR p.slug LIKE '%wa%') AND ap.db_name IS NOT NULL AND ap.db_name != ''");
                if ($stmt) {
                    $found = $stmt->fetchAll(\PDO::FETCH_COLUMN);
                    if (!empty($found)) {
                        $dbs = array_merge($dbs, $found);
                    }
                }
            }
        } catch (\Throwable $e) {}

        return array_values(array_unique(array_filter($dbs)));
    }

    public static function getWebsiteBuilderCustomers(): array
    {
        $customers = [];
        $seenEmails = [];

        // 1. Eloquent WbCustomer table on default connection
        try {
            if (class_exists(\App\Models\WebsiteBuilder\WbCustomer::class)) {
                \App\Models\WebsiteBuilder\WbCustomer::ensureColumnsExist();
                $rows = \App\Models\WebsiteBuilder\WbCustomer::orderBy('id', 'desc')->take(60)->get();
                if ($rows && $rows->isNotEmpty()) {
                    foreach ($rows as $r) {
                        $emailKey = strtolower(trim(($r->email ?? '') ?: (($r->subdomain ?? '') . '@wb')));
                        if (!isset($seenEmails[$emailKey])) {
                            $seenEmails[$emailKey] = true;
                            if (empty($r->package)) {
                                try {
                                    if ($r->package_id && class_exists(\App\Models\WebsiteBuilder\WbPackage::class)) {
                                        $pkg = \App\Models\WebsiteBuilder\WbPackage::find($r->package_id);
                                        if ($pkg) $r->package = $pkg;
                                    }
                                } catch (\Throwable $pe) {}
                                if (empty($r->package)) {
                                    $r->package = (object)[
                                        'name'          => 'Agency Pro',
                                        'title'         => 'Agency Pro',
                                        'monthly_price' => 29,
                                        'yearly_price'  => 290,
                                    ];
                                }
                            }
                            $customers[] = $r;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {}

        // 2. Query DB::table('wb_customers') directly on active connection
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
                $rawRows = DB::table('wb_customers')->orderBy('id', 'desc')->take(60)->get();
                foreach ($rawRows as $row) {
                    $emailKey = strtolower(trim(($row->email ?? '') ?: (($row->subdomain ?? '') . '@wb')));
                    if (isset($seenEmails[$emailKey])) continue;
                    $seenEmails[$emailKey] = true;
                    $row->name = !empty($row->name) ? $row->name : (!empty($row->subdomain) ? $row->subdomain : "Client #{$row->id}");
                    $row->package = (object)[
                        'name'          => 'Agency Plan',
                        'title'         => 'Agency Plan',
                        'monthly_price' => 29,
                        'yearly_price'  => 290,
                    ];
                    $customers[] = $row;
                }
            }
        } catch (\Throwable $e) {}

        // 3. Query DB::table('wb_template_purchases')
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('wb_template_purchases')) {
                $purchases = DB::table('wb_template_purchases')->orderBy('id', 'desc')->take(30)->get();
                foreach ($purchases as $p) {
                    $emailKey = strtolower(trim($p->customer_email ?: ""));
                    if (!empty($emailKey) && !isset($seenEmails[$emailKey])) {
                        $seenEmails[$emailKey] = true;
                        $customers[] = (object)[
                            'id'            => $p->customer_id ?: (1000 + $p->id),
                            'name'          => $p->customer_name ?: 'Client',
                            'subdomain'     => $p->template_slug ?: 'site',
                            'custom_domain' => '',
                            'email'         => $p->customer_email,
                            'phone'         => $p->customer_phone ?: '',
                            'expire_date'   => Carbon::now()->addDays(30)->format('Y-m-d H:i:s'),
                            'status'        => 1,
                            'package_id'    => $p->package_id ?: 1,
                            'package'       => (object)['name' => $p->template_name ?: 'Website Plan', 'title' => $p->template_name ?: 'Website Plan'],
                        ];
                    }
                }
            }
        } catch (\Throwable $e) {}

        // 4. Query candidate databases (using cross-DB select, dynamic connection, or isolated PDO)
        $candidateDbs = self::getDiscoveredWbDatabases();
        foreach ($candidateDbs as $db) {
            $rows = self::queryDb($db, "SELECT * FROM wb_customers ORDER BY id DESC LIMIT 60");
            if (!empty($rows)) {
                // Fetch packages mapping from this DB
                $pkgMap = [];
                $pkgRows = self::queryDb($db, "SELECT id, name, monthly_price, yearly_price FROM wb_packages");
                foreach ($pkgRows as $p) {
                    $pkgMap[$p->id] = $p;
                }

                foreach ($rows as $row) {
                    $emailKey = strtolower(trim(($row->email ?? '') ?: (($row->subdomain ?? '') . '@wb')));
                    if (isset($seenEmails[$emailKey])) continue;
                    $seenEmails[$emailKey] = true;

                    $row->_db_name = $db;
                    $row->name = !empty($row->name) ? $row->name : (!empty($row->subdomain) ? $row->subdomain : "Client #{$row->id}");
                    $pkgId = $row->package_id ?? null;
                    $pkgObj = ($pkgId && isset($pkgMap[$pkgId])) ? $pkgMap[$pkgId] : null;
                    $pkgName = $pkgObj ? $pkgObj->name : 'Agency Plan';

                    $row->package = (object)[
                        'name'          => $pkgName,
                        'title'         => $pkgName,
                        'monthly_price' => $pkgObj ? ($pkgObj->monthly_price ?? 29) : 29,
                        'yearly_price'  => $pkgObj ? ($pkgObj->yearly_price ?? 290) : 290,
                    ];
                    if (empty($row->expire_date)) {
                        $row->expire_date = Carbon::now()->addDays(30)->format('Y-m-d H:i:s');
                    }
                    $customers[] = $row;
                }
            }
        }

        // Return ONLY real registered Website Builder clients - never fake or cross-product accounts
        return $customers;
    }

    public static function getWhatsAppBulkUsers(): array
    {
        $candidateDbs = self::getDiscoveredWaDatabases();
        $seen = [];
        $users = [];

        foreach ($candidateDbs as $dbName) {
            $sql = "SELECT u.id, u.fullname, u.username, u.email, u.phone, u.expiration_date, u.status,
                           p.name as package_name
                    FROM users u
                    LEFT JOIN plans p ON p.id = u.plan_id
                    ORDER BY u.id DESC LIMIT 60";
            $rows = self::queryDb($dbName, $sql);
            if (empty($rows)) {
                $rows = self::queryDb($dbName, "SELECT id, fullname, username, email, phone, expiration_date, status FROM users ORDER BY id DESC LIMIT 60");
            }
            if (!empty($rows)) {
                foreach ($rows as $u) {
                    $k = strtolower(trim(($u->email ?? '') ?: ($u->username ?? '')));
                    if (isset($seen[$k])) continue;
                    $seen[$k] = true;
                    $u->_db_name = $dbName;
                    $u->package_name = $u->package_name ?? 'WhatsApp Pro Plan';
                    $users[] = $u;
                }
            }
        }

        // Return ONLY real registered WhatsApp Bulk clients - never fake or cross-product accounts
        return $users;
    }

    public static function findWhatsAppBulkUser($identifier)
    {
        $candidateDbs = self::getDiscoveredWaDatabases();
        foreach ($candidateDbs as $db) {
            $pdo = self::getPdoForDb($db);
            if ($pdo) {
                try {
                    $sql = "SELECT u.id, u.fullname, u.username, u.email, u.phone, u.expiration_date, u.status,
                                   p.name as package_name
                            FROM users u
                            LEFT JOIN plans p ON p.id = u.plan_id
                            WHERE u.username = ? OR u.email = ? OR u.phone = ? OR u.id = ? 
                            LIMIT 1";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$identifier, $identifier, $identifier, is_numeric($identifier) ? $identifier : 0]);
                    $user = $stmt->fetch(\PDO::FETCH_OBJ);
                    if ($user) {
                        $user->_db_name = $db;
                        $user->package_name = $user->package_name ?? 'WhatsApp Pro Plan';
                        return $user;
                    }
                } catch (\Throwable $e) {}
            }
        }

        return null;
    }

    public static function sendExpiryWarningForWaUser($waUser, $customMinutes = null): array
    {
        $res = [
            'whatsapp_sent' => false,
            'email_sent'    => false,
            'phone'         => null,
            'email'         => null,
            'time_text'     => null,
            'errors'        => [],
        ];

        try {
            if (!$waUser) {
                $res['errors'][] = "WhatsApp user not found";
                return $res;
            }

            $customerName = !empty($waUser->fullname) ? $waUser->fullname : $waUser->username;
            $phone = $waUser->phone ?? null;
            $email = $waUser->email ?? null;
            $planName = 'WhatsApp Bulk Automation Plan';
            $productName = 'WhatsApp Automation';

            $res['phone'] = $phone;
            $res['email'] = $email;

            if ($customMinutes !== null) {
                $mins = intval($customMinutes);
                $daysText = $mins <= 1 ? "1 minute" : "{$mins} minutes";
            } elseif (!empty($waUser->expiration_date)) {
                $secs = $waUser->expiration_date - time();
                if ($secs <= 0) {
                    $daysText = "today (EXPIRED)";
                } else {
                    $hours = round($secs / 3600);
                    $daysText = $hours <= 24 ? "{$hours} hours" : round($hours / 24) . " days";
                }
            } else {
                $daysText = "soon";
            }
            $res['time_text'] = $daysText;

            $cleanHost = preg_replace('/^(launchshop|checkout|app|www|websitebuilder|website-builder)\./i', '', request()->getHost() ?: 'youverse.in');
            $renewalUrl = "https://whatsappbulk.{$cleanHost}/app/profile/plan";

            // 1. WhatsApp Warning
            if (!empty($phone)) {
                try {
                    $expFormatted = Carbon::now()->addMinutes(intval($customMinutes ?: 5))->format('M d, Y h:i A');

                    $waRes = WhatsAppNotificationService::sendExpiryReminder(
                        $phone,
                        $customerName,
                        $planName,
                        $daysText,
                        $expFormatted,
                        $renewalUrl,
                        $productName,
                        null
                    );
                    $res['whatsapp_sent'] = !empty($waRes['success']);
                    if (!$res['whatsapp_sent'] && !empty($waRes['error'])) {
                        $res['errors'][] = "WhatsApp: " . $waRes['error'];
                    }
                } catch (\Throwable $waEx) {
                    $res['errors'][] = "WhatsApp: " . $waEx->getMessage();
                }
            } else {
                $res['errors'][] = "No phone number found for WA user";
            }

            // 2. Email Warning
            if (!empty($email)) {
                try {
                    $subject = "⚠️ Action Required: Your {$productName} subscription expires in {$daysText}";
                    $body = "
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e5e7eb; border-radius: 12px; background-color: #ffffff;'>
                            <div style='text-align: center; margin-bottom: 24px;'>
                                <h2 style='color: #ef4444; margin: 0;'>Subscription Expiry Notice</h2>
                                <p style='color: #6b7280; font-size: 14px;'>{$productName} Platform</p>
                            </div>
                            <p style='color: #374151; font-size: 16px;'>Hello <strong>{$customerName}</strong>,</p>
                            <p style='color: #374151; font-size: 15px; line-height: 1.6;'>
                                Your active WhatsApp bulk sending account subscription is expiring in <span style='color: #ef4444; font-weight: bold;'>{$daysText}</span>.
                            </p>
                            <div style='background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 16px; margin: 20px 0; border-radius: 4px;'>
                                <p style='margin: 0; color: #991b1b; font-size: 14px;'>
                                    <strong>Important:</strong> Upon expiration, message broadcasts, autoresponders, and chatbot triggers will be paused until renewal.
                                </p>
                            </div>
                            <div style='text-align: center; margin: 30px 0;'>
                                <a href='{$renewalUrl}' style='display: inline-block; padding: 14px 28px; background: #6366f1; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 15px;'>
                                    Renew Subscription Now
                                </a>
                            </div>
                        </div>
                    ";
                    BasicMailer::sendMail(['recipient' => $email, 'subject' => $subject, 'body' => $body]);
                    $res['email_sent'] = true;
                } catch (\Throwable $emEx) {
                    $res['errors'][] = "Email: " . $emEx->getMessage();
                }
            } else {
                $res['errors'][] = "No email address found for WA user";
            }
        } catch (\Throwable $e) {
            $res['errors'][] = $e->getMessage();
        }

        return $res;
    }

    public static function setExpiryForWaUser($waUserId, int $minutes = 5)
    {
        $candidateDbs = self::getDiscoveredWaDatabases();
        $expTimestamp = time() + ($minutes * 60);

        foreach ($candidateDbs as $db) {
            $pdo = self::getPdoForDb($db);
            if ($pdo) {
                try {
                    $stmt = $pdo->prepare("UPDATE users SET expiration_date = ?, status = 1 WHERE id = ?");
                    if ($stmt->execute([$expTimestamp, $waUserId]) && $stmt->rowCount() > 0) {
                        return true;
                    }
                } catch (\Throwable $e) {}
            }
        }
        return false;
    }

    public static function expireWaUserImmediately($waUserId)
    {
        $candidateDbs = self::getDiscoveredWaDatabases();
        $pastTimestamp = time() - 120;

        foreach ($candidateDbs as $db) {
            $pdo = self::getPdoForDb($db);
            if ($pdo) {
                try {
                    $stmt = $pdo->prepare("UPDATE users SET expiration_date = ? WHERE id = ?");
                    if ($stmt->execute([$pastTimestamp, $waUserId]) && $stmt->rowCount() > 0) {
                        return true;
                    }
                } catch (\Throwable $e) {}
            }
        }
        return false;
    }

    public static function reactivateWaUser($waUserId, int $days = 30)
    {
        $candidateDbs = self::getDiscoveredWaDatabases();
        $futureTimestamp = time() + ($days * 86400);

        foreach ($candidateDbs as $db) {
            $pdo = self::getPdoForDb($db);
            if ($pdo) {
                try {
                    $stmt = $pdo->prepare("UPDATE users SET expiration_date = ?, status = 1 WHERE id = ?");
                    if ($stmt->execute([$futureTimestamp, $waUserId]) && $stmt->rowCount() > 0) {
                        return true;
                    }
                } catch (\Throwable $e) {}
            }
        }
        return false;
    }
}
