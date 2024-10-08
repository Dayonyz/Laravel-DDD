<?php

namespace App\Domains\IdentityAccess\Domain\Events;

use App\Domains\Shared\Domain\DomainEvent;

class BusinessAccountRegistrationDiscarded extends DomainEvent
{

    public function getDomainHandlers(): array
    {
        return [];
    }

    public function getDiscardEvent(): ?DomainEvent
    {
        return null;
    }
}