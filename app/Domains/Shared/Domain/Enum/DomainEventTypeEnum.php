<?php

namespace App\Domains\Shared\Domain\Enum;

enum DomainEventTypeEnum: string
{
    case SOURCE = 'source';
    case HANDLER = 'handler';
}