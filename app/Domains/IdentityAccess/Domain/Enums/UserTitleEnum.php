<?php

namespace App\Domains\IdentityAccess\Domain\Enums;

enum UserTitleEnum: string
{
    case MR = 'Mr';
    case MRS = 'Mrs';
    case MS = 'Ms';
    case MISS = 'Miss';
    case MX_ETC = 'Mx etc';
}