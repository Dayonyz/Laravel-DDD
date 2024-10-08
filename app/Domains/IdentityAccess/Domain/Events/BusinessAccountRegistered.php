<?php

namespace App\Domains\IdentityAccess\Domain\Events;

use App\Domains\Shared\Application\Enum\DomainEnum;
use App\Domains\Shared\Domain\DomainEvent;

class BusinessAccountRegistered extends DomainEvent
{
    public function getDomainHandlers(): array
    {
        return [DomainEnum::DEAL->value];
    }
}