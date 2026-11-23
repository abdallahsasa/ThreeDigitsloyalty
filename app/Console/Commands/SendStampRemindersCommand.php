<?php

namespace App\Console\Commands;

use App\Models\Shop;
use App\Services\WalletPassService;
use Illuminate\Console\Command;

class SendStampRemindersCommand extends Command
{
    protected $signature = 'loyalty:send-stamp-reminders {--shop= : Optional Shop ID}';

    protected $description = 'Send Wallet push reminders to customers with incomplete stamp cards';

    public function handle(WalletPassService $walletService): int
    {
        $query = Shop::where('status', 'active');
        if ($shopId = $this->option('shop')) {
            $query->where('id', (int) $shopId);
        }

        $shops = $query->get();
        $totalApple = 0;
        $totalGoogle = 0;

        foreach ($shops as $shop) {
            $result = $walletService->sendStampCompletionReminders($shop);
            $totalApple += $result['apple_sent'];
            $totalGoogle += $result['google_sent'];
        }

        $this->info("Reminders sent. Apple: {$totalApple}, Google: {$totalGoogle}");

        return self::SUCCESS;
    }
}
