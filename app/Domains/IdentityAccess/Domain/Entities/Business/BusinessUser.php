<?php

namespace App\Domains\IdentityAccess\Domain\Entities\Business;

use App\Domains\IdentityAccess\Domain\Aggregates\BusinessAggregate;
use App\Domains\IdentityAccess\Domain\Entities\User\Dto\UserDto;
use App\Domains\IdentityAccess\Domain\Entities\User\User;
use App\Domains\IdentityAccess\Domain\Enums\UserAccountTypeEnum;

class BusinessUser extends User
{
    protected ?int $businessId;
    protected BusinessAggregate $business;

    private function __construct(
        BusinessAggregate   $business,
        UserDto             $userDto
    ) {
        parent::__construct(
            $userDto
        );

        $this->setBusinessId($business->getId());
        $this->setBusiness($business);
    }

    public function getBusinessId(): string
    {
        return $this->businessId;
    }

    protected function setBusinessId(?int $businessId): void
    {
        $this->businessId = $businessId;
    }

    public static function getUserAccountTypeEnum(): UserAccountTypeEnum
    {
        return UserAccountTypeEnum::BUSINESS;
    }

    public static function createBusinessUser(
        BusinessAggregate $business,
        UserDto           $userDto
    ): static {
       return new static(
           $business,
           $userDto
       );
    }

    protected function setBusiness(BusinessAggregate $business)
    {
        $this->business = $business;
    }

    public function getBusiness(): ?BusinessAggregate
    {
        return $this->business;
    }
}