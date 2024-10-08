<?php

namespace App\Domains\Shared\Application\Bus\Command;

interface CommandContract
{
    public static function dispatch(...$dispatchArgs): void;
}