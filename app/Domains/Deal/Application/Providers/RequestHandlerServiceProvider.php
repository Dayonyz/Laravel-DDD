<?php

namespace App\Domains\Deal\Application\Providers;

use App\Domains\Deal\Application\Services\RequestHandlerService;
use Illuminate\Support\ServiceProvider;

class RequestHandlerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind('request_handler', function ($app) {
            return $app->make(RequestHandlerService::class);
        });
    }
}
