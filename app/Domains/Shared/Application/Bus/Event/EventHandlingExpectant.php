<?php

namespace App\Domains\Shared\Application\Bus\Event;

use App\Domains\Shared\Domain\DomainEvent;

interface EventHandlingExpectant
{
    public function pending(mixed $id, DomainEvent $event);
}