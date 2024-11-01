<?php

namespace App\Domains\IdentityAccess\Application\Bus\Command\Dto;

class CreateBusinessAccountDto extends CommandDto
{
    public function __construct(
        public readonly string        $businessName,
        public readonly string        $businessLogo,
        public readonly string        $businessEmail,
        public readonly string        $businessPhoneCode,
        public readonly string        $businessPhone,
        public readonly string        $businessPostCode,
        public readonly string        $businessCountryIso,
        public readonly string        $businessCity,
        public readonly string        $businessAddressLine1,
        public readonly string        $businessWebsite,
        public readonly bool          $businessIsActive,
        public readonly string        $userTitle,
        public readonly string        $userFirstName,
        public readonly string        $userLastName,
        public readonly string        $userPassword,

        public readonly ?string       $businessAddressLine2 = null,
        public readonly ?string       $userEmail = null,
        public readonly ?string       $userPhoneCode = null,
        public readonly ?string       $userPhone = null,
        public readonly ?string       $userAvatar = null,
        public readonly ?string       $userDisplayName = null,
    ){}
}