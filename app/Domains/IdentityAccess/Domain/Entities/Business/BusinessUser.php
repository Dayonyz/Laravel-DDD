<?php

namespace App\Domains\IdentityAccess\Domain\Entities\Business;

use App\Domains\IdentityAccess\Domain\Aggregates\BusinessAggregate;
use App\Domains\IdentityAccess\Domain\Entities\User\User;
use App\Domains\IdentityAccess\Domain\Entities\User\UserAvatar;
use App\Domains\IdentityAccess\Domain\Entities\User\UserDisplayName;
use App\Domains\IdentityAccess\Domain\Entities\User\UserEmail;
use App\Domains\IdentityAccess\Domain\Entities\User\UserFullName;
use App\Domains\IdentityAccess\Domain\Entities\User\UserPassword;
use App\Domains\IdentityAccess\Domain\Entities\User\UserPhone;
use App\Domains\IdentityAccess\Domain\Enums\UserAccountTypeEnum;
use App\Domains\IdentityAccess\Domain\Enums\UserTitleEnum;
use App\Domains\IdentityAccess\Domain\Exceptions\UserEmailNotDefinedException;

class BusinessUser extends User
{
    protected ?int $businessId;
    protected BusinessAggregate $business;
    protected bool $userIsActive;

    private function __construct(
        BusinessAggregate   $business,
        UserTitleEnum       $userTitle,
        UserFullName        $userFullName,
        UserEmail           $userEmail,
        UserPhone           $userPhone,
        UserPassword        $userPassword,
        ?UserAvatar         $userAvatar,
        ?UserDisplayName    $userDisplayName,
        ?bool               $userIsActive,
    )
    {
        parent::__construct(
            $userTitle,
            $userFullName,
            $userEmail,
            $userPhone,
            $userPassword,
            $userAvatar,
            $userDisplayName
        );

        $this->setBusinessId($business->getId());
        $this->setBusiness($business);
        $this->setBusinessUserIsActive(!!$userIsActive);
    }

    public function getBusinessId(): string
    {
        return $this->businessId;
    }

    public function setBusinessId(?int $businessId): void
    {
        $this->businessId = $businessId;
    }

    public static function getUserAccountTypeEnum(): UserAccountTypeEnum
    {
        return UserAccountTypeEnum::BUSINESS;
    }

    /**
     * @throws UserEmailNotDefinedException
     */
    public static function createBusinessUser(
        BusinessAggregate $business,
        UserTitleEnum     $userTitle,
        UserFullName      $userFullName,
        UserEmail         $userEmail,
        UserPhone         $userPhone,
        UserPassword      $userPassword,
        ?UserAvatar       $userAvatar,
        ?UserDisplayName  $userDisplayName,
        ?bool             $userIsActive = null,
    ): static {
       return new static(
           $business,
           $userTitle,
           $userFullName,
           $userEmail,
           $userPhone,
           $userPassword,
           $userAvatar,
           $userDisplayName,
           (bool)$userIsActive
       );
    }

    public function setBusiness(BusinessAggregate $business)
    {
        $this->business = $business;
    }

    public function getBusiness(): ?BusinessAggregate
    {
        return $this->business;
    }

    protected function setBusinessUserIsActive(bool $isActive)
    {
        $this->userIsActive = $isActive;
    }

    public function getBusinessUserIsActive(): bool
    {
        return $this->userIsActive;
    }
}