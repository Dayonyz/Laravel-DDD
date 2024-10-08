<?php

namespace App\Domains\IdentityAccess\Domain\Entities\Business;

use App\Domains\Shared\Domain\Entity;
use App\Domains\IdentityAccess\Application\Bus\Command\CreateBusinessAccountPayload;
use App\Domains\IdentityAccess\Domain\Entities\User\UserAvatar;
use App\Domains\IdentityAccess\Domain\Entities\User\UserDisplayName;
use App\Domains\IdentityAccess\Domain\Entities\User\UserEmail;
use App\Domains\IdentityAccess\Domain\Entities\User\UserFullName;
use App\Domains\IdentityAccess\Domain\Entities\User\UserPassword;
use App\Domains\IdentityAccess\Domain\Entities\User\UserPhone;
use App\Domains\IdentityAccess\Domain\Entities\User\UserUuid;
use App\Domains\IdentityAccess\Domain\Enums\UserTitleEnum;
use App\Domains\IdentityAccess\Domain\Factories\Account;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

abstract class Business extends Entity implements Account
{
    protected string $businessUuid;
    protected BusinessName $businessName;
    protected BusinessLogo $businessLogo;
    protected BusinessEmail $businessEmail;
    protected BusinessPhone $businessPhone;
    protected BusinessAddress $businessAddress;
    protected BusinessWebsite $businessWebsite;
    protected Collection $businessUsers;
    protected bool $businessIsActive;

    private function __construct(
        BusinessName    $businessName,
        BusinessLogo    $businessLogo,
        BusinessEmail   $businessEmail,
        BusinessPhone   $businessPhone,
        BusinessAddress $businessAddress,
        BusinessWebsite $businessWebsite,
        bool            $businessIsActive
    ) {
        parent::__construct();
        $this->setBusinessUuid(BusinessUuid::random());
        $this->setBusinessName($businessName);
        $this->setBusinessLogo($businessLogo);
        $this->setBusinessEmail($businessEmail);
        $this->setBusinessPhone($businessPhone);
        $this->setBusinessAddress($businessAddress);
        $this->setBusinessWebsite($businessWebsite);
        $this->businessUsers = new ArrayCollection();
        $this->businessIsActive = $businessIsActive;
    }

    private function __clone(): void
    {
        // TODO: Implement __clone() method.
    }

    public function getBusinessUsers(): Collection
    {
        return $this->businessUsers;
    }

    abstract protected function createBusinessUser(
        UserTitleEnum    $userTitle,
        UserFullName     $userFullName,
        UserEmail        $userEmail,
        UserPhone        $userPhone,
        UserPassword     $userPassword,
        ?UserAvatar      $userAvatar,
        ?UserDisplayName $displayName,
        ?bool             $userIsActive,
    ): BusinessUser;

    public static function createBusinessAccount(CreateBusinessAccountPayload $dto): static {
        $business = new static(
            new BusinessName($dto->businessName),
            new BusinessLogo($dto->businessLogo),
            BusinessEmail::createVerified($dto->businessEmail),
            BusinessPhone::createVerified($dto->businessPhoneCode, $dto->businessPhone),
            new BusinessAddress(
                $dto->businessPostCode,
                $dto->businessCountryIso,
                $dto->businessCity,
                $dto->businessAddressLine1,
                $dto->businessAddressLine2
            ),
            new BusinessWebsite($dto->businessWebsite),
            $dto->businessIsActive,
        );

        $user = $business->createBusinessUser(
            UserTitleEnum::tryFrom($dto->userTitle),
            new UserFullName($dto->userFirstName, $dto->userLastName),
            $dto->userEmail ?  new UserEmail($dto->userEmail) : new UserEmail($dto->businessEmail),
            $dto->userPhone && $dto->userPhoneCode ?
                new UserPhone($dto->userPhoneCode, $dto->userPhone) :
                new UserPhone($dto->businessPhoneCode, $dto->businessPhone),
            new UserPassword($dto->userPassword),
            $dto->userAvatar ? new UserAvatar($dto->userAvatar) : null,
            $dto->userDisplayName ? new UserDisplayName($dto->userDisplayName) : null,
            $dto->businessIsActive,
        );

        $business->addUser($user);

        return $business;
    }

    protected function setBusinessUuid(BusinessUuid $businessUuid) {
        $this->businessUuid = $businessUuid->toString();
    }

    public function getBusinessUuid(): string
    {
        return $this->businessUuid;
    }

    protected function setBusinessName(BusinessName $businessName): void
    {
        $this->businessName = $businessName;
    }

    public function getBusinessName(): BusinessName
    {
        return $this->businessName;
    }

    protected function setBusinessLogo(BusinessLogo $logo): void
    {
        $this->businessLogo = $logo;
    }

    public function getBusinessLogo(): ?BusinessLogo
    {
        return $this->businessLogo;
    }

    protected function setBusinessEmail(BusinessEmail $businessEmail): void
    {
        $this->businessEmail = $businessEmail;
    }

    public function getBusinessEmail(): BusinessEmail
    {
        return $this->businessEmail;
    }

    protected function setBusinessPhone(BusinessPhone $businessPhone): void
    {
        $this->businessPhone = $businessPhone;
    }

    public function getBusinessPhone(): BusinessPhone
    {
        return $this->businessPhone;
    }

    protected function setBusinessAddress(BusinessAddress $businessAddress): void
    {
        $this->businessAddress = $businessAddress;
    }

    public function getBusinessAddress(): BusinessAddress
    {
        return $this->businessAddress;
    }

    protected function setBusinessWebsite(BusinessWebsite $businessWebsite): void
    {
        $this->businessWebsite = $businessWebsite;
    }

    public function getBusinessWebsite(): BusinessWebsite
    {
        return $this->businessWebsite;
    }

    public function addUser(BusinessUser $businessUser): void
    {
        if (!$this->businessUsers->contains($businessUser)) {
            $this->businessUsers->add($businessUser);
        }
    }

    public function removeUser(UserUuid $uuid): void
    {
        $this->businessUsers->remove($uuid->toString());
    }

    public function getBusinessIsActive(): bool
    {
        return $this->businessIsActive;
    }

    public function activate(): void
    {
       $this->businessIsActive = true;
    }

    public function deactivate(): void
    {
        $this->businessIsActive = false;
    }
}