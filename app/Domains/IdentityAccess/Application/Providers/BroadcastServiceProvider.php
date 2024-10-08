<?php

namespace App\Domains\IdentityAccess\Application\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        Broadcast::routes(['middleware' => ['api']]);

        require base_path("routes/channels.php");
    }
}
