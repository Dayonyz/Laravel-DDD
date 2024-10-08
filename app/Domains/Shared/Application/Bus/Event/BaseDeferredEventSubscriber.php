<?php

namespace App\Domains\Shared\Application\Bus\Event;

use App\Domains\Shared\Domain\DomainEvent;
use Illuminate\Support\Facades\App;
use JetBrains\PhpStorm\NoReturn;
use ReflectionException;

abstract class BaseDeferredEventSubscriber implements DeferredEventSubscriber
{
    protected array $events = [];

    /**
     * @throws ReflectionException
     */
    #[NoReturn]
    public function subscribe(callable $handler): void
    {
        $reflection = new \ReflectionFunction($handler);
        $params = $reflection->getParameters();
        if (count($params) === 0) {
            throw new \InvalidArgumentException('Event handler has no event as parameter');
        }

        $param = $params[0];
        $eventClass = $param->getType()->getName();

        if(get_parent_class($eventClass) !== DomainEvent::class) {
            throw new \InvalidArgumentException("Invalid event '$eventClass' passed to event handler");
        }

        $this->events[$eventClass][] = $handler;
    }

    /**
     * @throws ReflectionException
     */
    public function handle(DomainEvent $event)
    {
        foreach ($this->events[$event::class] as $handler) {
            $reflection = new \ReflectionFunction($handler);
            $params = $reflection->getParameters();
            $param = $params[0];
            $eventParamName = $param->getName();

            App::call($handler, [$eventParamName => $event]);
        }
    }

    public function isSubscribedTo(string $eventClass): bool
    {
        return isset($this->events[$eventClass]);
    }
}