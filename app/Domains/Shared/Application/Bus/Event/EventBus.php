<?php

namespace App\Domains\Shared\Application\Bus\Event;

use App\Domains\Shared\Domain\DomainEvent;

interface EventBus
{
    public function publishEvents();

    public function discardEvents();

    public function registerEvents(...$events);

    public function registerEvent(DomainEvent $event);

    public function registerSubscriber(DeferredEventSubscriber $subscriber);

    public function handleDeferredSubscribers();
}