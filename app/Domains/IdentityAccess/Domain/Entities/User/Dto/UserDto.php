<?php

namespace App\Domains\IdentityAccess\Domain\Entities\User\Dto;

use App\Domains\IdentityAccess\Domain\Enums\UserTitleEnum;

class UserDto
{
    public function __construct(
        public readonly UserTitleEnum  $title,
        public readonly string         $firstName,
        public readonly string         $lastName,
        public readonly string         $email,
        public readonly string         $phoneCode,
        public readonly string         $phoneNumber,
        public readonly string         $password,
        public readonly ?string        $avatarUrl,
        public readonly ?string        $displayName,
        public readonly bool           $isActive = false
    ){}
}