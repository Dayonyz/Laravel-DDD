<?php

namespace App\Domains\Shared\Application\Bus\Event;

//use App\Domains\Shared\Application\Exceptions\EventHandlingException;
//use App\Domains\Shared\Application\Exceptions\EventHandlingSuccessException;
//use App\Domains\Shared\Application\Facade\EventSourceHandlingExpectant;
use App\Domains\Shared\Application\Facade\EventSourcePublisher;
use App\Domains\Shared\Domain\DomainEvent;

abstract class BaseEventBus implements EventBus
{
    /**
     * @var $events DomainEvent []
     */
    protected array $events = [];
    protected array $publishedEvents = [];
    protected array $handledEvents = [];
    protected array $subscribers = [];

    public function publishEvents()
    {
        foreach ($this->events as $event) {
            $id = EventSourcePublisher::publish($event);
            //dd($id);
            $this->publishedEvents[$id] = $event;

//            if (!empty($event->getDomainHandlers())) {
//                try {
//                    EventSourceHandlingExpectant::pending($id, $event);
//                }
//                catch (EventHandlingSuccessException $exception) {
//                    $this->handledEvents[] = $event;
//                }
//            }
        }
    }

    public function discardEvents()
    {
        foreach ($this->handledEvents as $event) {
            EventSourcePublisher::publish($event->getDiscardEvent());
        }
    }

    public function registerEvents(...$events): void
    {
        foreach ($events as $event) {
            $this->registerEvent($event);
        }
    }

    public function registerEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    public function registerSubscriber(DeferredEventSubscriber $subscriber)
    {
        $this->subscribers[] = $subscriber;
    }

    public function handleDeferredSubscribers(): void
    {
        foreach ($this->events as $event) {
            foreach ($this->subscribers as $subscriber) {
                if ($subscriber->isSubscribedTo($event::class)) {
                    $subscriber->handle($event);
                }
            }
        }

        $this->events = [];
    }
}