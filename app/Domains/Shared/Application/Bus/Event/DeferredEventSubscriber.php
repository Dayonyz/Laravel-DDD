<?php

namespace App\Domains\Shared\Application\Bus\Event;

use App\Domains\Shared\Domain\DomainEvent;

interface DeferredEventSubscriber
{
    public function subscribe(callable $handler);

    public function handle(DomainEvent $event);

    public function isSubscribedTo(string $eventClass);
}