<?php

namespace App\Domains\Shared\Application\Enum;

enum EventHandledStatusEnum: string
{
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case DECLINED = 'declined';
}