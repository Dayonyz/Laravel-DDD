<?php

namespace App\Domains\IdentityAccess\Domain\Enums;

enum UserAccountTypeEnum: string
{
    case ADMIN = 'admin';
    case CUSTOMER = 'customer';
    case BUSINESS = 'business';
}