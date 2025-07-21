<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Subscription;
use Carbon\Carbon;

class ExpireSubscriptionsCommand extends Command
{
    protected $signature = 'subscriptions:expire';
    protected $description = 'Expire subscriptions that passed their billing date';

    public function handle()
    {
        Log::info('⏳ Running subscription expiration check...');

        $today = Carbon::now();

        $expiredSubs = Subscription::where('nextBillingDate', '<', $today)
                                   ->where('status', 'active')
                                   ->get();

        foreach ($expiredSubs as $sub) {
            Log::warning("❌ Expiring subscription: {$sub->id} for user {$sub->userId}");

            $sub->update(['status' => 'expired']);

            // Also downgrade user
            User::where('id', $sub->userId)->update([
                'status' => 'inactive'
            ]);
        }

        Log::info("✅ Expiration check complete → {$expiredSubs->count()} subscriptions expired");

        return 0;
    }
}
