<?php

namespace App\Domains\Shared\Application\Bus\Command;

interface CommandDtoContract
{
    public static function fromArray(array $payload): static;
}