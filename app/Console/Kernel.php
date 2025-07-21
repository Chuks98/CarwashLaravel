<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // // ✅ Run Auto-Billing every 5 minutes - For testing
        // $schedule->command('billing:auto-renew')->cron('*/5 * * * *')
        //     ->appendOutputTo(storage_path('logs/schedulerForAutoBilling.log'));

        // // ✅ Expire overdue subscriptions every 5 minutes - For testing
        // $schedule->command('subscriptions:expire')->cron('*/5 * * * *')
        //     ->appendOutputTo(storage_path('logs/schedulerForExpiredSubs.log'));




        // ✅ Run Auto-Billing every day at 2 AM
        $schedule->command('billing:auto-renew')
            ->dailyAt('02:00')
            ->appendOutputTo(storage_path('logs/schedulerForAutoBilling.log'));

        // ✅ Expire overdue subscriptions every day at midnight
        $schedule->command('subscriptions:expire')
            ->dailyAt('00:00')
            ->appendOutputTo(storage_path('logs/schedulerForExpiredSubs.log'));






        // ✅ Quick Test: Log every minute (remove later)
        // $schedule->call(function () {
        //     \Log::info('✅ Scheduler Test: running at ' . now());
        // })->everyMinute();

        // $schedule->command('test:schedule')->everyMinute()->appendOutputTo(storage_path('logs/scheduler.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}