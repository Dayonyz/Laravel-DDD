<?php

namespace App\Domains\Deal\Application\Bus\Event\OuterEventPayloads;

use App\Domains\Shared\Application\Bus\ConvertablePayload;

class IdentityAccessBusinessAccountRegistered
{
    use ConvertablePayload;

    public function __construct(public string $name, public string $email)
    {
    }
}