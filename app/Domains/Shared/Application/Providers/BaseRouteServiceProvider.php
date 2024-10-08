<?php

namespace App\Domains\Shared\Application\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

abstract class BaseRouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $binder = app()->make('binder');

        $this->routes(function () use ($binder) {
            Route::middleware('api')
                ->prefix('api')
                ->group(isset($_SERVER['REQUEST_URI']) ?
                    $binder->getCurrentDomainPath('Framework/routes/api.php') :
                    base_path('routes/api.php')
                );
        });

        $this->routes(function () use ($binder) {
            Route::middleware('web')
                ->group(isset($_SERVER['REQUEST_URI']) ?
                    $binder->getCurrentDomainPath('Framework/routes/web.php') :
                    base_path('routes/web.php')
                );
        });
    }
}