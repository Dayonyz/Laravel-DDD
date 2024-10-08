<?php

namespace App\Domains\Shared\Application\Bus\Event;

interface OuterEventSubscriber
{
    public function __call(string $handleMethod, array $arguments);
}