<?php

namespace App\Domains\Deal\Application\Providers;

use App\Domains\Shared\Application\Bus\Event\EventBus;
use App\Domains\Shared\Application\Bus\Event\EventHandlingExpectant as InterfaceEventEchoExpectant;
use App\Domains\Shared\Application\Bus\Event\EventPublisher as InterfaceEventPublisher;
use App\Domains\Shared\Application\Bus\Event\OuterEventSubscriber as InterfaceOuterEventSubscriber;
use App\Domains\Shared\Application\Services\Messaging\MessageReceiver;
use App\Domains\Shared\Application\Services\Messaging\Messenger;
use App\Domains\Shared\Application\Services\Messaging\Redis\RedisMessageReceiver;
use App\Domains\Shared\Application\Services\Messaging\Redis\RedisMessenger;
use App\Domains\Deal\Application\Bus\Event\EventBus as ConcreteEventBus;
use App\Domains\Deal\Application\Bus\Event\EventEchoExpectant;
use App\Domains\Deal\Application\Bus\Event\EventPublisher;
use App\Domains\Deal\Application\Bus\Event\OuterEventSubscriber;
use Illuminate\Support\ServiceProvider;

class EventBusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->when(EventPublisher::class)
            ->needs(Messenger::class)
            ->give(function () {
                return new RedisMessenger(config('event-source.db_prefix'));
            });

        $this->app->when(EventEchoExpectant::class)
            ->needs(MessageReceiver::class)
            ->give(function () {
                return new RedisMessageReceiver(config('event-source.db_prefix'));
            });

        $this->app->singleton(InterfaceEventPublisher::class, EventPublisher::class);
        $this->app->singleton(InterfaceEventEchoExpectant::class, EventEchoExpectant::class);
        $this->app->singleton(InterfaceOuterEventSubscriber::class, OuterEventSubscriber::class);
        $this->app->singleton(EventBus::class, ConcreteEventBus::class);
    }
}
