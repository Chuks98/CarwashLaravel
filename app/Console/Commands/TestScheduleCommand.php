<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestScheduleCommand extends Command
{
    protected $signature = 'test:schedule';
    protected $description = 'Just logs something to test the scheduler';

    public function handle()
    {
        Log::info('✅ TestScheduleCommand executed at ' . now());
        $this->info('✅ TestScheduleCommand executed at ' . now());
    }
}
