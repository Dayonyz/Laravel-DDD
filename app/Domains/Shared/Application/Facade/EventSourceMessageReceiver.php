<?php

namespace App\Domains\Shared\Application\Facade;

use Illuminate\Support\Facades\Facade;

class EventSourceMessageReceiver extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'event_source_message_receiver';
    }
}