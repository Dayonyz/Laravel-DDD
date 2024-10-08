<?php

namespace App\Domains\Shared\Application\Providers;

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
        //
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
