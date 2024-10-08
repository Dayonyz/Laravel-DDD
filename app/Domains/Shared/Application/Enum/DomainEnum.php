<?php

namespace App\Domains\Shared\Application\Enum;

enum DomainEnum: string
{
    case DEAL = 'Deal';
    case IDENTITY_ACCESS = 'IdentityAccess';
    case SHARED = 'Shared';
    case BINDER = 'Binder';
}