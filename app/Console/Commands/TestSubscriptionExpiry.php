<?php

namespace App\Console\Commands;

use App\Http\Helpers\Common;
use App\Models\BasicExtended;
use App\Models\Membership;
use App\Models\Package;
use App\Models\User;
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
                            {username : Store username or email to test} 
                            {--action=simulate-full : Action: simulate-full, set-expiry, send-warning, expire-now, reactivate, test-renewal}
                            {--minutes=5 : Minutes until expiration for testing}
                            {--days=30 : Days to add for reactivation}
                            {--phone= : Override test phone number for WhatsApp}
                            {--email= : Override test email address}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fast testing tool for subscription expiry, store blocking, WhatsApp/Email reminders, and renewals';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $identifier = $this->argument('username');
        $action = $this->option('action') ?: 'simulate-full';
        $minutes = intval($this->option('minutes') ?: 5);
        $days = intval($this->option('days') ?: 30);

        // Find user by username, email, or id
        $user = User::where('username', $identifier)
            ->orWhere('email', $identifier)
            ->orWhere('id', is_numeric($identifier) ? $identifier : 0)
            ->first();

        if (!$user) {
            $this->error("❌ User not found with username/email/id: {$identifier}");
            return 1;
        }

        // Apply phone or email overrides if specified
        if ($this->option('phone')) {
            $user->phone = $this->option('phone');
        }
        if ($this->option('email')) {
            $user->email = $this->option('email');
        }

        $this->info("════════════════════════════════════════════════════════════");
        $this->info("  🚀 SUBSCRIPTION EXPIRY TESTING UTILITY");
        $this->info("════════════════════════════════════════════════════════════");
        $this->line("• User ID:       <comment>{$user->id}</comment>");
        $this->line("• Username:      <comment>{$user->username}</comment>");
        $this->line("• Email:         <comment>{$user->email}</comment>");
        $this->line("• Phone (WA):    <comment>" . ($user->phone ?: ($user->phone_number ?: 'Not set')) . "</comment>");
        $this->line("• Action:        <comment>{$action}</comment>");
        $this->info("────────────────────────────────────────────────────────────");

        $storeHost = env('WEBSITE_HOST', 'launchshop.youverse.in');
        $storeUrl = "https://{$storeHost}/{$user->username}";

        switch ($action) {
            case 'set-expiry':
                $m = SubscriptionExpiryService::setExpiryInMinutes($user, $minutes);
                $this->info("✅ Membership updated to expire in {$minutes} minutes!");
                $this->line("• Expiration Timestamp: <comment>{$m->expire_date}</comment>");
                $this->line("• Live Store URL:       <info>{$storeUrl}</info>");
                break;

            case 'send-warning':
                $this->info("⏳ Sending Expiry Warning notification (WhatsApp & Email)...");
                $res = SubscriptionExpiryService::sendExpiryWarningForUser($user, $minutes, true);
                $this->renderNoticeResults($res);
                break;

            case 'expire-now':
                $m = SubscriptionExpiryService::expireUserImmediately($user);
                $this->warn("⚠️ Subscription has been EXPIRED immediately (set to past)!");
                $this->line("• Expiration Timestamp: <comment>{$m->expire_date}</comment>");
                $this->line("• Test Website Blocking now by visiting: <info>{$storeUrl}</info>");
                $this->line("• Result: Should display 'Store Temporarily Inactive' (Suspension Screen)");
                break;

            case 'reactivate':
                $m = SubscriptionExpiryService::reactivateUser($user, $days);
                $this->info("🎉 User subscription REACTIVATED successfully by Admin!");
                $this->line("• Valid Until:    <comment>{$m->expire_date}</comment>");
                $this->line("• Status:         <comment>Active (1)</comment>");
                $this->line("• Live Store URL: <info>{$storeUrl}</info> (Unlocked and Live)");
                break;

            case 'test-renewal':
                $this->info("💳 Simulating Customer Renewal Payment (+{$minutes} minutes)...");
                $m = SubscriptionExpiryService::setExpiryInMinutes($user, $minutes);
                
                // Trigger Invoice via WhatsApp & Email
                try {
                    $be = BasicExtended::first();
                    $pkg = Package::find($m->package_id) ?? Package::first();
                    $pkgTitle = $pkg->title ?? 'Store Plan Renewal';
                    $txId = 'TEST_RENEW_' . strtoupper(substr(uniqid(), 7));

                    $reqData = [
                        'start_date' => $m->start_date,
                        'expire_date' => $m->expire_date,
                        'payment_method' => 'Test Mode / Gateway'
                    ];

                    $invoiceFile = Common::makeInvoice(
                        $reqData,
                        "extend",
                        $user,
                        "test_pass",
                        $m->price ?: 499,
                        "Test Gateway",
                        $user->phone ?: $user->phone_number,
                        $be->base_currency_text_position ?? 'left',
                        $be->base_currency_symbol ?? '₹',
                        $be->base_currency_text ?? 'INR',
                        $txId,
                        $pkgTitle,
                        1
                    );

                    $this->info("✅ Renewal payment processed!");
                    $this->line("• Invoice Generated: <comment>{$invoiceFile}</comment>");
                    $this->line("• WhatsApp Invoice:  Sent to {$user->phone}");
                    $this->line("• Validity Extended: To {$m->expire_date}");
                    $this->line("• Store Status:      ACTIVE and UNBLOCKED");
                } catch (\Throwable $invEx) {
                    $this->error("Invoice dispatch notice: " . $invEx->getMessage());
                }
                break;

            case 'simulate-full':
            default:
                $this->info("1️⃣ Setting user subscription to expire in {$minutes} minutes...");
                $m = SubscriptionExpiryService::setExpiryInMinutes($user, $minutes);
                $this->line("   • Expiry Timestamp: <comment>{$m->expire_date}</comment>");

                $this->info("2️⃣ Sending WhatsApp and Email Expiry Warning...");
                $res = SubscriptionExpiryService::sendExpiryWarningForUser($user, $minutes, true);
                $this->renderNoticeResults($res);

                $this->info("3️⃣ Testing Verification Links:");
                $this->line("   • Live Store:       <info>{$storeUrl}</info> (Active right now for next {$minutes} minutes)");
                $this->line("   • Merchant Renew:   <info>https://{$storeHost}/user/membership/extend-membership</info>");
                $this->line("   • Store Expiry:     In {$minutes} minutes, visit <info>{$storeUrl}</info> to see the Suspension Screen!");
                $this->line("   • Immediate Block:  Run <comment>php artisan subscription:test {$user->username} --action=expire-now</comment>");
                $this->line("   • Reactivate Store: Run <comment>php artisan subscription:test {$user->username} --action=reactivate</comment>");
                break;
        }

        $this->info("════════════════════════════════════════════════════════════\n");
        return 0;
    }

    protected function renderNoticeResults(array $res)
    {
        if ($res['whatsapp_sent']) {
            $this->info("   ✅ WhatsApp warning SENT to {$res['phone']}");
        } else {
            $this->warn("   ⚠️ WhatsApp warning not sent: " . implode(', ', $res['errors'] ?: ['Phone unavailable or gateway inactive']));
        }

        if ($res['email_sent']) {
            $this->info("   ✅ Email warning SENT to {$res['email']}");
        } else {
            $this->warn("   ⚠️ Email warning not sent: " . implode(', ', $res['errors'] ?: ['Email not configured']));
        }
    }
}