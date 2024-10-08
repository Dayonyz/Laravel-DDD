<?php

namespace App\Domains\IdentityAccess\Application\Console;

use App\Domains\Shared\Application\Console\BaseKernel;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends BaseKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }
}
