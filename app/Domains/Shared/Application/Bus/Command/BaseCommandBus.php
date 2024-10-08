<?php

namespace App\Domains\Shared\Application\Bus\Command;

abstract class BaseCommandBus implements CommandBus
{
    public function __construct(
        private array $commands = [],
    ){
    }

    public function pushCommand(callable $handler): void
    {
        $this->commands[] = $handler;
    }

    public function executeCommands(): void
    {
        foreach ($this->commands as $handler) {
            $handler();
        }

        $this->commands = [];
    }
}