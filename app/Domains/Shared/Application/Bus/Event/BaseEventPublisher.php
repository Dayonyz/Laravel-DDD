<?php

namespace App\Domains\Shared\Application\Bus\Event;

use App\Domains\Shared\Application\Services\Messaging\Messenger;
use App\Domains\Shared\Domain\ConvertableEvent;

abstract class BaseEventPublisher implements EventPublisher
{
    private Messenger $messenger;

    public function __construct(Messenger $messenger)
    {
        $this->messenger = $messenger;
    }

    abstract function getChannel(): string;

    public function publish(ConvertableEvent $event): int|string
    {
        return $this->messenger->send($this->getChannel(), $event->toArray());
    }
}