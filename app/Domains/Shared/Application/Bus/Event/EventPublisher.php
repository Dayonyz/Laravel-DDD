<?php

namespace App\Domains\Shared\Application\Bus\Event;

use App\Domains\Shared\Domain\ConvertableEvent;

interface EventPublisher
{
    public function publish(ConvertableEvent $event): int|string;
}