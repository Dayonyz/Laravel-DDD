<?php

namespace App\Domains\Shared\Application\Bus\Event;

use App\Domains\Shared\Application\Enum\EventHandledStatusEnum;
use App\Domains\Shared\Application\Exceptions\EventHandlingException;
use App\Domains\Shared\Application\Exceptions\EventHandlingSuccessException;
use App\Domains\Shared\Application\Facade\EventSourceMessageReceiver;
use App\Domains\Shared\Domain\DomainEvent;

abstract class BaseEventHandlingExpectant implements EventHandlingExpectant
{
    protected const TIMEOUT = 3000;

    public function pending(mixed $id, DomainEvent $event): void
    {
        $successDomainHandlers = [];
        $start = hrtime(true);

        while (true) {
            $messages = EventSourceMessageReceiver::getMessages(config('event-source.channel'), $id);
            foreach ($messages as $handledId => $message) {
                if (isset($message['handledEventId']) &&
                    $message['handledEventId'] === $id &&
                    isset($message['status'])
                ) {
                    if ($message['status'] !== EventHandledStatusEnum::SUCCESS->value) {
                        throw new EventHandlingException("Domain '{$message['domain']}' did not handle the event " .
                        "'{$message['handledEvent']}', status: '{$message['status']}', message: '{$message['message']}'");
                    } else {
                        $successDomainHandlers[$message['domain']] = true;
                    }
                }
            }

            if (empty(array_diff($event->getDomainHandlers(), array_keys($successDomainHandlers)))) {
                throw new EventHandlingSuccessException("Event '{$event->getEventName()}' handled successfully, id: {$id}");
            }

            if ((hrtime(true) - $start)/1000000 > static::TIMEOUT)
                throw new EventHandlingException("Domain event '{$event->getEventName()}' handling timeout exceeded");
        }
    }
}