<?php

namespace App\Console\Commands;

use App\Services\SubscriptionExpiryService;
use Illuminate\Console\Command;

class ExpiredUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expire:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and send 2-day and 1-day subscription expiry reminders across all products and agencies';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Starting automated subscription expiry checks across all products and agencies...");
        $stats = SubscriptionExpiryService::runDailyChecks();
        $this->info("Completed. Scanned {$stats['agencies_scanned']} agencies. Notified: LaunchShop={$stats['launchshop_notified']}, WB={$stats['websitebuilder_notified']}, WA={$stats['whatsappbulk_notified']}.");
        return 0;
    }
}