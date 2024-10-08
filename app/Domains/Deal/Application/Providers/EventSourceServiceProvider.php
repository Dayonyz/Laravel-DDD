<?php

namespace App\Domains\Deal\Application\Providers;

use App\Domains\Shared\Application\Bus\Event\EventHandlingExpectant;
use App\Domains\Shared\Application\Bus\Event\EventPublisher;
use App\Domains\Shared\Application\Services\Messaging\Redis\RedisMessageReceiver;
use Illuminate\Support\ServiceProvider;

class EventSourceServiceProvider extends ServiceProvider
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
        $this->app->bind('event_source_message_receiver', function () {
            return new RedisMessageReceiver(config('event-source.db_prefix'));
        });

        $this->app->bind('event_source_publisher', function ($app) {
            return $app->make(EventPublisher::class);
        });

        $this->app->bind('event_source_expectant', function ($app) {
            return $app->make(EventHandlingExpectant::class);
        });
    }
}
