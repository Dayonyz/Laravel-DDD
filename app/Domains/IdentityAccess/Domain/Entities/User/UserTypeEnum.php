<?php

namespace App\Domains\IdentityAccess\Domain\Entities\User;

enum UserTypeEnum: string
{
    case ADMIN = 'admin';
    case CUSTOMER = 'customer';
    case BUSINESS = 'business';
}