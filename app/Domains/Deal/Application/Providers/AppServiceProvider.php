<?php

namespace App\Domains\Deal\Application\Providers;

use App\Domains\Shared\Application\Bus\Event\EventBus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\NullStore;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(Collection::class, ArrayCollection::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cache::extend( 'none', function( $app ) {
            return Cache::repository( new NullStore );
        } );
    }
}
