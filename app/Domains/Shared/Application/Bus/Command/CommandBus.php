<?php

namespace App\Domains\Shared\Application\Bus\Command;

interface CommandBus
{
    public function pushCommand(callable $handler);

    public function executeCommands();
}