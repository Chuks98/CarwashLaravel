<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Subscription;
use Carbon\Carbon;

class AutoBillingCommand extends Command
{
    protected $signature = 'billing:auto-renew';
    protected $description = 'Run auto-billing for due subscriptions';

    public function handle()
    {
        Log::info('🔁 Running scheduled auto-billing task at 2 AM...');

        try {
            $today = Carbon::now();

            // ✅ Find subscriptions due for renewal
            $subscriptionsDue = Subscription::where('nextBillingDate', '<=', $today)
                                            ->where('status', 'active')
                                            ->with('user') // assuming relation `user()`
                                            ->get();

            if ($subscriptionsDue->isEmpty()) {
                Log::info('ℹ️ No subscriptions due for renewal today.');
                return 0;
            }

            foreach ($subscriptionsDue as $sub) {
                $user = $sub->user;

                // ✅ Skip if user disabled auto-billing
                if (!$user->autoBilling) {
                    Log::info("⏭️ Auto-billing skipped for {$user->email} (autoBilling disabled)");
                    continue;
                }

                // ✅ Ensure billing info exists
                if (!$user->authorizationCode || !$user->email) {
                    Log::warning("⚠️ Missing billing info for user: {$user->id}");
                    continue;
                }

                try {
                    $newReference = 'auto_renew_' . now()->timestamp . '_' . uniqid();

                    // ✅ Charge via Paystack
                    $response = Http::withToken(env('PAYSTACK_SECRET_KEY'))
                        ->post('https://api.paystack.co/transaction/charge_authorization', [
                            'email' => $user->email,
                            'authorization_code' => $user->authorizationCode,
                            'amount' => $sub->price * 100, // Paystack expects kobo
                            'reference' => $newReference,
                            'metadata' => [
                                'userId' => $user->id,
                                'plan' => $sub->plan,
                                'previousReference' => $sub->reference,
                                'autoRenew' => true
                            ]
                        ]);

                    Log::info("✅ Auto-billing initiated for {$user->email} (Plan: {$sub->plan})");
                    Log::info("Paystack Response → " . $response->json()['message']);

                    // ✅ Let webhook handle DB updates & email confirmations

                } catch (\Exception $e) {
                    Log::error("❌ Billing error for {$user->email}: " . $e->getMessage());
                }
            }
        } catch (\Exception $error) {
            Log::error('❌ Cron job failed: ' . $error->getMessage());
        }

        return 0;
    }
}
