<?php

namespace App\Domains\Shared\Domain\Enum;

enum PasswordLevelEnum: string
{
    case WEAK = "/^(?=.*?[a-z])(?=.*?[0-9]).{8,}$/";
    case MEDIUM = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/";
    case STRONG = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";
}