<?php

namespace App\Domains\Deal\Application\Bus\Event;

use App\Domains\Shared\Application\Bus\Event\BaseEventPublisher;

class EventPublisher extends BaseEventPublisher
{
    function getChannel(): string
    {
        return config('event-source.channel');
    }
}