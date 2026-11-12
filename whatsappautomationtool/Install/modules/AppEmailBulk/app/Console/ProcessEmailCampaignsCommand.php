<?php

namespace Modules\AppEmailBulk\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\AppEmailBulk\Services\EmailCampaignProcessor;

class ProcessEmailCampaignsCommand extends Command
{
    protected $signature = 'email:process-campaigns {--campaign= : Optional campaign id_secure} {--limit=10 : Max campaigns to process per run} {--tenant_db= : Optional tenant database name}';
    protected $description = 'Process running bulk email campaigns in scalable batches';

    public function handle(): int
    {
        $tenantDb = trim((string) $this->option('tenant_db'));
        if ($tenantDb !== '' && preg_match('/^[a-zA-Z0-9_]+$/', $tenantDb)) {
            config(['database.connections.mysql.database' => $tenantDb]);
            DB::purge('mysql');
            DB::reconnect('mysql');
        }

        $campaignId = $this->option('campaign') ?: null;
        $limit = max(1, (int) $this->option('limit'));

        $result = EmailCampaignProcessor::processDueCampaigns($limit, $campaignId);

        $this->info(sprintf(
            'Checked: %d | Sent: %d | Queued: %d | Failed: %d',
            $result['campaigns_checked'],
            $result['sent'],
            $result['queued'],
            $result['failed']
        ));

        foreach ($result['messages'] as $msg) {
            $this->line(' - ' . $msg);
        }

        return self::SUCCESS;
    }
}

