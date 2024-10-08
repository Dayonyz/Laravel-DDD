<?php

namespace App\Domains\Deal\Application\Bus\Event;

use App\Domains\Shared\Application\Bus\Event\BaseOuterEventSubscriber;
use App\Domains\Deal\Application\Bus\Event\OuterEventPayloads\IdentityAccessBusinessAccountRegistered;
use Illuminate\Support\Facades\Log;

class OuterEventSubscriber extends BaseOuterEventSubscriber
{
    public function identityAccessBusinessAccountRegistered(IdentityAccessBusinessAccountRegistered $payload): void
    {
        Log::info($payload->name);
        Log::info($payload->email);
    }
}