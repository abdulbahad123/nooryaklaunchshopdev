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
            $renewalUrl = "https://saasreselling.com/pricing";

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
    protected static function getPdoForDb(string $dbName): ?\PDO
    {
        static $pdoCache = [];
        if (isset($pdoCache[$dbName])) {
            return $pdoCache[$dbName];
        }

        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', '3306');

        $userPairs = array_values(array_filter([
            ['user' => env('SASS_ADMIN_DB_USER') ?: env('DB_USERNAME_admin'), 'pass' => env('SASS_ADMIN_DB_PASS') ?: env('DB_PASSWORD_admin', '')],
            ['user' => config('database.connections.mysql.username'), 'pass' => config('database.connections.mysql.password')],
            ['user' => env('DB_USERNAME'), 'pass' => env('DB_PASSWORD', '')],
            ['user' => 'root', 'pass' => ''],
        ], function ($item) {
            return !empty($item['user']) || $item['user'] === '';
        }));

        foreach ($userPairs as $pair) {
            try {
                $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4";
                $pdo = new \PDO($dsn, $pair['user'], $pair['pass'], [
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_SILENT,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                    \PDO::ATTR_TIMEOUT            => 2,
                ]);
                $pdoCache[$dbName] = $pdo;
                return $pdo;
            } catch (\Throwable $e) {}
        }

        return null;
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
}