<?php

namespace App\Console\Commands;

use App\Http\Helpers\Common;
use App\Models\BasicExtended;
use App\Models\Membership;
use App\Models\Package;
use App\Models\User;
use App\Models\WebsiteBuilder\WbCustomer;
use App\Services\SubscriptionExpiryService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TestSubscriptionExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:test 
                            {username : Store username, subdomain, email or phone to test} 
                            {--product=auto : Product: auto, launchshop, websitebuilder (wb), whatsappbulk (wa)}
                            {--action=simulate-full : Action: simulate-full, set-expiry, send-warning, expire-now, reactivate, test-renewal}
                            {--minutes=5 : Minutes until expiration for testing}
                            {--days=30 : Days to add for reactivation}
                            {--phone= : Override test phone number for WhatsApp}
                            {--email= : Override test email address}';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Fast testing tool for subscription expiry, site blocking, reminders & renewals across LaunchShop, Website Builder, and WhatsApp Bulk';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $identifier = $this->argument('username');
        $product = strtolower($this->option('product') ?: 'auto');
        $action = $this->option('action') ?: 'simulate-full';
        $minutes = intval($this->option('minutes') ?: 5);
        $days = intval($this->option('days') ?: 30);

        // Normalize product alias
        if (in_array($product, ['wb', 'websitebuilder', 'website-builder', 'builder'])) {
            $product = 'websitebuilder';
        } elseif (in_array($product, ['wa', 'whatsapp', 'whatsappbulk', 'whatsapp-bulk'])) {
            $product = 'whatsappbulk';
        } elseif (in_array($product, ['ls', 'launchshop', 'ecom', 'ecombuilder'])) {
            $product = 'launchshop';
        }

        // Auto-detect product if 'auto'
        if ($product === 'auto') {
            $lsUser = User::where('username', $identifier)
                ->orWhere('email', $identifier)
                ->orWhere('id', is_numeric($identifier) ? $identifier : 0)
                ->first();

            if ($lsUser) {
                $product = 'launchshop';
            } else {
                $wbCust = null;
                if (class_exists(WbCustomer::class)) {
                    $wbCust = WbCustomer::where('subdomain', $identifier)
                        ->orWhere('email', $identifier)
                        ->orWhere('id', is_numeric($identifier) ? $identifier : 0)
                        ->first();
                }
                if ($wbCust) {
                    $product = 'websitebuilder';
                } else {
                    $waUser = SubscriptionExpiryService::findWhatsAppBulkUser($identifier);
                    if ($waUser) {
                        $product = 'whatsappbulk';
                    } else {
                        $product = 'launchshop'; // default fallback
                    }
                }
            }
        }

        $this->info("════════════════════════════════════════════════════════════");
        $this->info("  🚀 MULTI-PRODUCT SUBSCRIPTION EXPIRY SIMULATOR");
        $this->info("════════════════════════════════════════════════════════════");
        $this->line("• Target Product: <info>" . strtoupper($product) . "</info>");
        $this->line("• Identifier:     <comment>{$identifier}</comment>");
        $this->line("• Action:         <comment>{$action}</comment>");
        $this->info("────────────────────────────────────────────────────────────");

        switch ($product) {
            case 'websitebuilder':
                return $this->handleWebsiteBuilder($identifier, $action, $minutes, $days);

            case 'whatsappbulk':
                return $this->handleWhatsAppBulk($identifier, $action, $minutes, $days);

            case 'launchshop':
            default:
                return $this->handleLaunchShop($identifier, $action, $minutes, $days);
        }
    }

    // ─────────────────────────────────────────────────────────────────
    // 1. LAUNCHSHOP HANDLER
    // ─────────────────────────────────────────────────────────────────
    protected function handleLaunchShop($identifier, $action, $minutes, $days)
    {
        $user = User::where('username', $identifier)
            ->orWhere('email', $identifier)
            ->orWhere('id', is_numeric($identifier) ? $identifier : 0)
            ->first();

        if (!$user) {
            $this->error("❌ LaunchShop user not found for: {$identifier}");
            return 1;
        }

        if ($this->option('phone')) $user->phone = $this->option('phone');
        if ($this->option('email')) $user->email = $this->option('email');

        $storeHost = env('WEBSITE_HOST', 'launchshop.youverse.in');
        $storeUrl = "https://{$storeHost}/{$user->username}";

        $this->line("• Store Username: <comment>{$user->username}</comment>");
        $this->line("• Registered WA:  <comment>" . ($user->phone ?: ($user->phone_number ?: 'Not set')) . "</comment>");
        $this->line("• Email Address:  <comment>{$user->email}</comment>");

        switch ($action) {
            case 'set-expiry':
                $m = SubscriptionExpiryService::setExpiryInMinutes($user, $minutes);
                $this->info("✅ Plan expiry set to {$minutes} minutes ({$m->expire_date})");
                $this->line("• Live Store URL: <info>{$storeUrl}</info>");
                break;

            case 'send-warning':
                $res = SubscriptionExpiryService::sendExpiryWarningForUser($user, $minutes, true);
                $this->renderNoticeResults($res);
                break;

            case 'expire-now':
                $m = SubscriptionExpiryService::expireUserImmediately($user);
                $this->warn("⚠️ Plan EXPIRED immediately! Store is now BLOCKED.");
                $this->line("• Visit Store URL to verify suspension: <info>{$storeUrl}</info>");
                break;

            case 'reactivate':
                $m = SubscriptionExpiryService::reactivateUser($user, $days);
                $this->info("🎉 Plan REACTIVATED! Store unblocked for {$days} days.");
                $this->line("• Live Store URL: <info>{$storeUrl}</info>");
                break;

            case 'test-renewal':
            case 'simulate-full':
            default:
                $m = SubscriptionExpiryService::setExpiryInMinutes($user, $minutes);
                $res = SubscriptionExpiryService::sendExpiryWarningForUser($user, $minutes, true);
                $this->renderNoticeResults($res);
                $this->info("3️⃣ Testing Verification Links:");
                $this->line("   • Live Store:     <info>{$storeUrl}</info> (Active for next {$minutes} min)");
                $this->line("   • Immediate Block: <comment>php artisan subscription:test {$user->username} --action=expire-now</comment>");
                $this->line("   • Reactivate:      <comment>php artisan subscription:test {$user->username} --action=reactivate</comment>");
                break;
        }

        $this->info("════════════════════════════════════════════════════════════\n");
        return 0;
    }

    // ─────────────────────────────────────────────────────────────────
    // 2. WEBSITE BUILDER HANDLER
    // ─────────────────────────────────────────────────────────────────
    protected function handleWebsiteBuilder($identifier, $action, $minutes, $days)
    {
        $customer = WbCustomer::where('subdomain', $identifier)
            ->orWhere('email', $identifier)
            ->orWhere('id', is_numeric($identifier) ? $identifier : 0)
            ->first();

        if (!$customer) {
            $this->error("❌ Website Builder customer not found for: {$identifier}");
            return 1;
        }

        if ($this->option('phone')) $customer->phone = $this->option('phone');
        if ($this->option('email')) $customer->email = $this->option('email');

        $siteUrl = url("/website-builder/{$customer->subdomain}");

        $this->line("• Customer Name: <comment>{$customer->name}</comment>");
        $this->line("• Subdomain:     <comment>{$customer->subdomain}</comment>");
        $this->line("• Phone (WA):    <comment>{$customer->phone}</comment>");
        $this->line("• Email:         <comment>{$customer->email}</comment>");

        switch ($action) {
            case 'set-expiry':
                SubscriptionExpiryService::setExpiryForWbCustomer($customer, $minutes);
                $this->info("✅ WB site set to expire in {$minutes} minutes ({$customer->expire_date})");
                $this->line("• Website URL: <info>{$siteUrl}</info>");
                break;

            case 'send-warning':
                $res = SubscriptionExpiryService::sendExpiryWarningForWbCustomer($customer, $minutes);
                $this->renderNoticeResults($res);
                break;

            case 'expire-now':
                SubscriptionExpiryService::expireWbCustomerImmediately($customer);
                $this->warn("⚠️ WB Site EXPIRED immediately! Public site is now BLOCKED.");
                $this->line("• Visit Website URL to verify suspension: <info>{$siteUrl}</info>");
                break;

            case 'reactivate':
                SubscriptionExpiryService::reactivateWbCustomer($customer, $days);
                $this->info("🎉 WB Customer REACTIVATED for {$days} days! Site UNBLOCKED.");
                $this->line("• Website URL: <info>{$siteUrl}</info>");
                break;

            case 'simulate-full':
            default:
                SubscriptionExpiryService::setExpiryForWbCustomer($customer, $minutes);
                $res = SubscriptionExpiryService::sendExpiryWarningForWbCustomer($customer, $minutes);
                $this->renderNoticeResults($res);
                $this->info("3️⃣ Testing Verification Links:");
                $this->line("   • Public Site:    <info>{$siteUrl}</info> (Active for next {$minutes} min)");
                $this->line("   • Immediate Block: <comment>php artisan subscription:test {$customer->subdomain} --product=wb --action=expire-now</comment>");
                $this->line("   • Reactivate:      <comment>php artisan subscription:test {$customer->subdomain} --product=wb --action=reactivate</comment>");
                break;
        }

        $this->info("════════════════════════════════════════════════════════════\n");
        return 0;
    }

    // ─────────────────────────────────────────────────────────────────
    // 3. WHATSAPP BULK HANDLER
    // ─────────────────────────────────────────────────────────────────
    protected function handleWhatsAppBulk($identifier, $action, $minutes, $days)
    {
        $waUser = SubscriptionExpiryService::findWhatsAppBulkUser($identifier);

        if (!$waUser) {
            $this->error("❌ WhatsApp Bulk user not found in nooryak_whatautomation for: {$identifier}");
            return 1;
        }

        if ($this->option('phone')) $waUser->phone = $this->option('phone');
        if ($this->option('email')) $waUser->email = $this->option('email');

        $this->line("• Full Name:     <comment>{$waUser->fullname}</comment>");
        $this->line("• Username:      <comment>{$waUser->username}</comment>");
        $this->line("• Phone (WA):    <comment>{$waUser->phone}</comment>");
        $this->line("• Email:         <comment>{$waUser->email}</comment>");

        switch ($action) {
            case 'set-expiry':
                SubscriptionExpiryService::setExpiryForWaUser($waUser->id, $minutes);
                $expStr = date('Y-m-d H:i:s', time() + ($minutes * 60));
                $this->info("✅ WA Bulk user set to expire in {$minutes} minutes ({$expStr})");
                break;

            case 'send-warning':
                $res = SubscriptionExpiryService::sendExpiryWarningForWaUser($waUser, $minutes);
                $this->renderNoticeResults($res);
                break;

            case 'expire-now':
                SubscriptionExpiryService::expireWaUserImmediately($waUser->id);
                $this->warn("⚠️ WA Bulk user EXPIRED immediately! Automation sending paused.");
                break;

            case 'reactivate':
                SubscriptionExpiryService::reactivateWaUser($waUser->id, $days);
                $this->info("🎉 WA Bulk account REACTIVATED for {$days} days! Active and unblocked.");
                break;

            case 'simulate-full':
            default:
                SubscriptionExpiryService::setExpiryForWaUser($waUser->id, $minutes);
                $res = SubscriptionExpiryService::sendExpiryWarningForWaUser($waUser, $minutes);
                $this->renderNoticeResults($res);
                $this->info("3️⃣ Testing Controls:");
                $this->line("   • Immediate Block: <comment>php artisan subscription:test {$waUser->username} --product=wa --action=expire-now</comment>");
                $this->line("   • Reactivate:      <comment>php artisan subscription:test {$waUser->username} --product=wa --action=reactivate</comment>");
                break;
        }

        $this->info("════════════════════════════════════════════════════════════\n");
        return 0;
    }

    protected function renderNoticeResults(array $res)
    {
        if (!empty($res['whatsapp_sent'])) {
            $this->info("   ✅ WhatsApp warning SENT to {$res['phone']}");
        } else {
            $this->warn("   ⚠️ WhatsApp warning not sent: " . implode(', ', $res['errors'] ?: ['Phone unavailable']));
        }

        if (!empty($res['email_sent'])) {
            $this->info("   ✅ Email warning SENT to {$res['email']}");
        } else {
            $this->warn("   ⚠️ Email warning not sent: " . implode(', ', $res['errors'] ?: ['Email unavailable']));
        }
    }
}