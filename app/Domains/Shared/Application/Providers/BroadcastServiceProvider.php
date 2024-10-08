<?php

namespace App\Domains\Shared\Application\Providers;

use App\Services\DomainLocatorService;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Broadcast::routes(['middleware' => ['api']]);

        $domain  = DomainLocatorService::getName();

        require base_path("app/$domain/Interface/routes/channels.php");
    }
}
