<?php

namespace App\Domains\Shared\Application\Providers;

use App\Domains\Shared\Application\Bus\Command\BaseCommandBus;
use App\Domains\Shared\Application\Bus\Command\CommandBus;
use Illuminate\Support\ServiceProvider;

class CommandBusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(CommandBus::class, BaseCommandBus::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
