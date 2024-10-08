<?php

namespace App\Domains\Deal\Application\Providers;

use App\Domains\Shared\Application\Bus\Command\CommandBus as CommandBusInterface;
use App\Domains\Deal\Application\Bus\Command\CommandBus;
use Illuminate\Support\ServiceProvider;

class CommandBusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CommandBusInterface::class, CommandBus::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
