<?php

namespace App\Domains\Shared\Application\Bus\Command;

use App\Domains\Shared\Application\Exceptions\CommandDispatcherException;
use Illuminate\Support\Facades\App;
use ReflectionException;

abstract class BaseCommand implements CommandContract
{
    /**
     * @throws ReflectionException
     */
    public static function dispatch(...$dispatchArgs): void
    {
        try {
            $commandHandlerReflection = new \ReflectionMethod(get_called_class(), 'handle');
        } catch (ReflectionException $exception) {
            throw new CommandDispatcherException($exception->getMessage());
        }

        $handlerParams = $commandHandlerReflection->getParameters();

        if (count($handlerParams) === 0) {
            throw new CommandDispatcherException('Command handler has no any payload.');
        }

        $handlerPayloadClass = $handlerParams[0]->getType()->getName();

        if (!in_array(CommandDtoContract::class, class_implements($handlerPayloadClass))) {
            throw new CommandDispatcherException('Invalid command handler payload definition.');
        }

        $payload = count($dispatchArgs) === 1 && is_object($dispatchArgs[0]) ?
            $dispatchArgs[0] :
            call_user_func($handlerPayloadClass . '::fromArray', $dispatchArgs);

        if (get_class($payload) !== $handlerPayloadClass) {
            throw new CommandDispatcherException('Invalid command dispatcher payload definition.');
        }

        $commandBus = app(CommandBus::class);
        $instance = new $commandHandlerReflection->class;

        $handler = function () use ($instance, $payload) {
            App::call([$instance, 'handle'], ['payload' => $payload]);
        };

        $commandBus->pushCommand($handler);
    }
}