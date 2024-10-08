<?php

namespace App\Domains\Shared\Domain;

interface ConvertableEvent
{
    public function toArray(): array;
}