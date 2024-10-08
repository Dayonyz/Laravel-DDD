<?php

namespace App\Domains\Shared\Application\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

abstract class BaseKernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     * @throws BindingResolutionException
     */
    protected function commands(): void
    {
        $binder = app()->make('binder');

        $this->load($binder->getCurrentDomainPath('Application/Console/Commands'));

        require $binder->getCurrentDomainPath('Framework/routes/console.php');
    }

}
