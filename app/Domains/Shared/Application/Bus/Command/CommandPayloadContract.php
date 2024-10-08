<?php

namespace App\Domains\Shared\Application\Bus\Command;

interface CommandPayloadContract
{
    public static function fromArray(array $payload): static;
}