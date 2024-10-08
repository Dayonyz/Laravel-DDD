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
            $reflection = new \ReflectionMethod(get_called_class(), 'handle');
        } catch (ReflectionException $exception) {
            throw new CommandDispatcherException($exception->getMessage());
        }

        $handleMethodParams = $reflection->getParameters();
        $payloadInstancePassed = false;

        if (count($handleMethodParams) === 0) {
            throw new CommandDispatcherException('Command handler has no any payload.');
        }

        if (count($dispatchArgs) === 1 &&
            isset($dispatchArgs[0]) &&
            is_object($dispatchArgs[0]) &&
            get_parent_class($dispatchArgs[0]) === BaseCommandPayload::class
        ) {
            $payload = $dispatchArgs[0];
            $payloadInstancePassed = true;
        }

        $expectedPayloadClass = $handleMethodParams[0]->getType()->getName();

        if (!in_array(CommandPayloadContract::class, class_implements($expectedPayloadClass))) {
            throw new CommandDispatcherException('Invalid command payload definition.');
        }

        if (!$payloadInstancePassed) {
            $payload = call_user_func($expectedPayloadClass . '::fromArray', $dispatchArgs);
        }

        if ($expectedPayloadClass !== $payload::class) {
            throw new CommandDispatcherException('Invalid command payload definition.');
        }

        $commandBus = app(CommandBus::class);
        $instance = new $reflection->class;

        $handler = function () use ($instance, $payload) {
            App::call([$instance, 'handle'], ['payload' => $payload]);
        };

        $commandBus->pushCommand($handler);
    }
}