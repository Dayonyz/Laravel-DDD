<?php

namespace App\Domains\IdentityAccess\Domain\Entities\User\Enums;

enum UserAccountTypeEnum: string
{
    case ADMIN = 'admin';
    case CUSTOMER = 'customer';
    case BUSINESS = 'business';
}